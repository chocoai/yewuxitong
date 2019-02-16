<?php

namespace app\workflow\controller;

use think\Db;
use think\Request;
use app\workflow\model\WorkflowProc as ProcModel;
use app\workflow\model\WorkflowEntry;
use Workflow\Workflow;

/**
 * Class Proc
 * @package app\workflow\controller
 * @author: bordon
 */
class Proc extends Base
{

    /**进程详细
     * @param Request $request
     */
    public function index(Request $request)
    {
        $entry_id = $request->get('entry_id', 0);
        $entry = WorkflowEntry::findOrFail($entry_id);
        if ($entry->pid > 0) {
            $entry_id = $entry->pid;
        }
        $procs = ProcModel::field("id,entry_id,process_id,process_name,user_name,auditor_name,status,content,update_time")
            ->with('entry')->where(['entry_id' => $entry_id, 'is_deleted' => 1])->order('id', 'ASC')->select();
//        $procs = ProcModel::field("min(id) id,entry_id,process_id,process_name,GROUP_CONCAT(user_name) user_name,auditor_name,status,content,max(update_time) update_time")
//            ->with('entry')->where(['entry_id' => $entry_id, 'is_deleted' => 1])->group('process_id', 'concurrence', 'circle')->order('id', 'ASC')->select();
        return view('', ['procs' => $procs]);
    }


    /**通过
     * @param Request $request
     * @param $id $proc_id
     * @return \think\response\Json
     */
    public function pass($id)
    {
        $option = [
            'user_id' => session('user.id'),
            'user_name' => session('user.username'),
            'proc_id' => $id,
            'order_sn' => 'JYDB2018060012',
            'content' => input('content')
        ];
        $Workflow = new Workflow($option);
        Db::startTrans();
        try {
            $Workflow->pass();
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return json(['code' => 0, 'msg' => $e->getMessage()]);
        }
        return json(['code' => 1, 'url' => url('flow/index'), 'msg' => '操作成功']);
    }

    /**未通过
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     */
    public function unpass($id)
    {
        $option = [
            'user_id' => session('user.id'),
            'user_name' => session('user.username'),
            'proc_id' => $id,
            'content' => input('content'),
//            'backtoback' => '28',
            'back_proc_id' => '1',  // 退回节点id
        ];
        $workflow = new Workflow($option);
        Db::startTrans();
        try {
            $workflow->unpass();
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return json(['code' => 0, 'msg' => $e->getMessage()]);
        }
        return json(['code' => 1, 'url' => url('flow/index'), 'msg' => '操作成功']);
    }

    public function resend($entry_id)
    {
        $workflow = new Workflow();
        Db::startTrans();
        try {
            $workflow->resend($entry_id,session('user.id'));
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return json(['code' => 0, 'msg' => $e->getMessage()]);
        }
        return json(['code' => 1, 'url' => url('flow/index'), 'msg' => '操作成功']);
    }


    /**审批
     * @param $id
     * @return
     */
    public function show($id)
    {
        $proc = ProcModel::findOrFail($id);
        $entry = WorkflowEntry::findOrFail($proc->entry_id);
        $option = [
            'user_id' => session('user.id'),
            'user_name' => session('user.username'),
            'proc_id' => $id,
        ];
        $workflow = new Workflow($option);
        $list = $workflow->workflowInfo();
//        $res = $workflow->afterAction($proc);
//       echo $res;exit;
        return view('', ['proc' => $proc, 'entry' => $entry, 'list' => $list]);
    }


}
