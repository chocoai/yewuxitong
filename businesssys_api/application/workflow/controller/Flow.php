<?php

namespace app\workflow\controller;

use app\workflow\model\WorkflowEntry;
use app\workflow\model\WorkflowFlow as FlowModel;
use app\workflow\model\WorkflowFlow;
use app\workflow\model\WorkflowFlowlink;
use app\workflow\model\WorkflowProc;
use app\workflow\model\WorkflowProcess;
use app\workflow\model\WorkflowProcessVar;
use think\Db;
use think\Request;
use Workflow\Workflow;
use Workflow\service\ProcService;

class Flow extends Base
{

    //
    public function index()
    {
        $entries = WorkflowEntry::alias('e')
            ->join('__WORKFLOW_PROCESS__ p', 'p.id=e.process_id')
            ->where(['e.user_id' => session('user.id'), 'e.pid' => 0])
            ->field('e.*,p.flow_id,p.process_name,p.type,p.process_to')
            ->paginate(10);
        //我的待办
//        $this->updateInspector();
//        halt(ProcService::getApprovalPendInfo('INFO_FEE','DQJK2018070004','28','1480'));
        $procs = WorkflowProc::where(['user_id' => session('user.id'), 'status' => 0, 'current' => 1, 'is_deleted' => 1])->order('is_read asc')->paginate(10);
        $flow = FlowModel::paginate(10);
//        $this->updateWorkflow();
//        $this->updateFlowDesc();
        return view('', [
            'flow' => $flow,
            'entries' => $entries,
            'procs' =>$procs
        ]);
    }


    /**更新订单审查员--临时使用
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author: bordon
     */
    public function updateInspector()
    {
        $order = \app\model\Order::all();
        foreach ($order as &$item){
            $proc = WorkflowProc::alias('wp')
                ->join('workflow_flow wf','wf.id=wp.flow_id')
                ->join('workflow_entry we','we.id = wp.entry_id')
                ->field('wp.*')
                ->where([
                    'wp.order_sn'=>$item->order_sn,
                    'wf.type'=>$item->type.'_RISK',
                ])->find();
            $inspector_id = $this->getInspector($proc, 1004);
            $item->inspector_id = $inspector_id;
            $item->save();
        }
    }

    private function getInspector($proc, $status)
    {
        $inspector_id = WorkflowProc::alias('wp')->where([
            'wp.entry_id' => $proc['entry_id'],
            'wp.flow_id' => $proc['flow_id'],
            'wp.order_sn' => $proc['order_sn'],
            'wp.is_deleted' => 1,
            'wps.wf_status' => $status
        ])->join('__WORKFLOW_PROCESS__ wps', 'wps.id=wp.process_id')->order('wp.finish_time desc')
            ->value('wp.user_id');
        return $inspector_id ? $inspector_id : false;
    }

    public function updateFlowDesc()
    {
        $res = WorkflowProc::where('is_deleted', 1)->select();
        foreach ($res as $item) {
            $item->status_desc = WorkflowProc::$approvalStatus[$item->status];
            if ($item->back_proc_id) {
                $item->status_desc = $item->status_desc . '：' . WorkflowProc::find($item->back_proc_id)->process_name;
            }
            $item->save();
        }
    }

    /**更新流程流转判断字段
     * @return Request
     */
    public function updateWorkflow()
    {
        $var = WorkflowProcessVar::where('table_name', 'order_guarantee')->where('expression_field', 'total_amount')->select();
        foreach ($var as $item) {
            $item->expression_field = 'out_account_total';
            $item->save();
        }
        unset($item);
        $var1 = WorkflowProcessVar::where('table_name', 'order')->where('expression_field', 'total_amount')->select();
        foreach ($var1 as $item) {
            $item->table_name = 'order_guarantee';
            $item->expression_field = 'out_account_total';
            $item->save();
        }
        unset($item);
        $var2 = WorkflowFlowlink::all();
        foreach ($var2 as &$item) {
            $item->expression = trim(str_replace('total_amount', 'out_account_total', $item->expression));
            $item->save();
        }
    }

    /**
     * @param $type 订单类型   ['JYDB','FINANCIAL']
     * @param $order_sn 订单编号
     * @param $dispatch_id  派单表id
     */
    public function getDispatchProcId($type, $order_sn, $dispatch_id)
    {
        $type = implode('_', $type);
        $where = [
            'wf.type' => $type,
            'wf.status' => 1,
            'wf.is_publish' => 1,
            'we.order_sn' => $order_sn,
            'we.mid' => $dispatch_id
        ];
        $info = Db::name('WorkflowFlow')->alias('wf')
            ->join('__WORKFLOW_ENTRY__ we', 'we.flow_id = wf.id')
            ->where($where)->field('we.id as entry_id,we.flow_id')
            ->findOrFail();
        $proc = Db::name('WorkflowProc')->where($info)->where([
            'status' => 0,
            'is_back' => 0,
            'is_deleted' => 1
        ])->findOrFail();
        return $proc['id'];

    }

    public function getBackProcId($order_status, $order_sn, $flow_id)
    {
        if (in_array($order_status, ['203', '204', '205', '206', '207'])) {
//            退回赎楼经理 202
            $where['wp.wf_status'] = '202';
        }
        if (in_array($order_status, ['202'])) {
//            退回待派赎楼员 201
            $where['wp.wf_status'] = '201';
        }
        $where['wc.is_deleted'] = 1;
        $where['wc.is_back'] = 0;
        $where['wc.order_sn'] = $order_sn;
        $where['wc.flow_id'] = $flow_id;
        $res = WorkflowProcess::alias('wp')
            ->join('__WORKFLOW_PROC__ wc', 'wc.process_id=wp.id')
            ->where($where)->value('wc.id');
        return $res;
    }

    //发起流程
    public function test(Request $request)
    {
        $data = $request->get();
        $orde_sn = 'JYDB2018080001';
        $order_id = 22;
        $workflow = new Workflow();
        Db::startTrans();
        try {
            $params = [
                'flow_id' => $data['flow_id'],
                'user_id' => session('user.id'),
                'order_sn' => $orde_sn,
                'mid' => $order_id
            ];
            $workflow->init($params);
            Db::commit();
            return redirect("pass");
        } catch (\Exception $e) {
            Db::rollback();
//            $this->error('操作失败' . $e->getMessage());
        }
    }

    /**发布流程
     * @param Request $request
     */
    public function publish(Request $request)
    {
        try {
            $flow_id = $request->post('flow_id', 0);
            $flow = FlowModel::findOrFail($flow_id);
            if (WorkflowFlowlink::where(['flow_id' => $flow->id, 'type' => 'Condition'])->count() <= 1) {
                throw new \Exception("发布失败，至少两个步骤");
            }
            if (WorkflowFlowlink::where(['flow_id' => $flow->id, 'type' => 'Condition', 'next_process_id' => -1])->count() > 1) {
                throw new \Exception("发布失败，有步骤没有连线");
            }
            if (!$this->check_step(['fl.flow_id' => $flow_id, 'po.position' => 0])) {
                throw new \Exception("发布失败，请设置起始步骤");
            }
            if (!$this->check_step(['fl.flow_id' => $flow_id, 'po.position' => 9])) {
                throw new \Exception("发布失败，请设置结束步骤");
            }
            $flowlinks = $this->check_step(['fl.flow_id' => $flow_id, 'fl.type' => 'Condition', 'po.position' => ['neq', 0]]);
            foreach ($flowlinks as $v) {
                if (!$this->check_step(['fl.flow_id' => $flow_id, 'fl.process_id' => $v['process_id'], 'fl.type' => ['<>', 'Condition'], 'po.position' => ['<>', 0]])) {
                    throw new \Exception("发布失败，请给设置步骤审批权限");
                }
            }
            $flow->is_publish = 1;
            $flow->save();
            return json([
                'code' => 1,
                'msg' => '发布成功'
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**检查流程是否设置步骤
     * @param $where
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function check_step($where)
    {
        $flowlink = WorkflowFlowlink::alias('fl')->join('__WORKFLOW_PROCESS__ po', 'po.id=fl.process_id')
            ->where($where)
            ->field('fl.*,po.position')
            ->find();
        return $flowlink;
    }

    /**设计流程
     * @param Request $request
     * @param $id
     */
    public function design($id = 0)
    {
        $flow = FlowModel::findOrFail($id);
        return view('', ['flow' => $flow]);
    }

    /**添加/编辑流程
     * @param Request $request
     */
    public function create(Request $request)
    {
        $data = $request->post();
        if (isset($data['id'])) {
            $result = FlowModel::update($data);
        } else {
            $flow = FlowModel::where('type', $data['type'])->where('status', '1')->find();
            if ($flow) {
                $this->error('操作失败，请先禁用重复流程再添加！', 'flow/index');
            }
            $result = FlowModel::create($data);
        }
        if ($result !== false) {
            $this->success('操作成功', 'flow/index');
        }
        $this->error('数据保存失败, 请稍候再试!');
    }

    /**
     * 审批
     * @param $id
     */
    public function show($id)
    {
        $proc = WorkflowProc::findOrFail($id);
        $entry = WorkflowEntry::findOrFail($proc->entry_id);
        return view('');
    }

    public function add()
    {
        return view('', ['flow_type' => FlowModel::$flowTypeMap]);
    }

    /**编辑流程信息
     * @param $id
     * @return \think\response\View
     */
    public function edit($id)
    {
        $flow = FlowModel::findOrFail($id);
        return view('add', ['flow' => $flow,'flow_type'=>WorkflowFlow::$flowTypeMap]);
    }

    /**删除流程
     * @param $id
     */
    public function del($id)
    {
        $flow = FlowModel::findOrFail($id);
        if (WorkflowEntry::where('flow_id', $flow->id)->first()) {
            $this->error('该流程已经被使用，禁止删除');
        }

        if (WorkflowProcess::where('child_flow_id', $flow->id)->first()) {
            $this->error('该流程已经被使用，禁止删除');
        }
        FlowModel::del(['id' => $id]);
        $this->success('删除成功');
    }

    /**
     * 禁用
     */
    public function forbid($id)
    {
        if (FlowModel::where('id', $id)->setField('status', '0')) {
            $this->success("禁用成功!");
        }
        $this->error("禁用失败, 请稍候再试!");
    }

    /**
     * 启用
     */
    public function resume($id)
    {
        if (FlowModel::where('id', $id)->setField('status', '1')) {
            $this->success("启用成功!");
        }
        $this->error("启用失败, 请稍候再试!");
    }

    public function pass()
    {
        return view();
    }

}
