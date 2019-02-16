<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/9/6
 * Time: 16:13
 */
namespace app\admin\controller;

use app\model\OrderGuarantee;
use app\util\ReturnCode;
use think\Db;
use think\Loader;
use app\model\OrderOther;
use app\model\OrderOtherAccount;
use app\util\OrderComponents;
use app\model\Dictionary;
use Workflow\Workflow;
use app\model\WorkflowProc;
use app\workflow\model\WorkflowEntry;
use app\model\SystemUser;
use Workflow\service\ProcService;
use app\model\OrderOtherExhibition;
use app\util\FinancialBack;
use app\model\OrderCommunicate;
use app\model\OrderCommunicateReply;

class CancellationsApply extends Base
{
    private $process_type; //费用申请类型
    private $id;  //其他业务表id
    private $time;
    private $summoney = 0;  //费用总计
    private $checkData = array(
        'accountInfo' => [], //账户信息
    ); //校验数据

    public function _initialize()
    {
        parent::_initialize();
        $this->orderother = new OrderOther();
        $this->orderotheraccount = new OrderOtherAccount();
    }

    /**
     * @api {post} admin/CancellationsApply/addCancellatApply 添加撤单申请[admin/CancellationsApply/addCancellatApply]
     * @apiVersion 1.0.0
     * @apiName addCancellatApply
     * @apiGroup CancellationsApply
     * @apiSampleRequest admin/CancellationsApply/addCancellatApply
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  process_type   撤单类型 10(退担保费) 11(不退担保费) 12(保费调整)
     * @apiParam {string}  reason  撤单原因(申请原因)
     * @apiParam {arr}  attachment  附件材料[1,2,3]
     *
     * @apiParam {array} accountinfo  支付账户信息(具体参数在下面)
     * @apiParam {string}   bank_account  银行户名
     * @apiParam {string}  bank_card   银行卡号
     * @apiParam {string}  bank  开户银行
     * @apiParam {string}  bank_branch  开户支行
     * @apiParam {float}  money  退款金额
     */

    public function addCancellatApply()
    {
        $orderSn = input('order_sn', null, 'trim');
        $this->process_type = input('process_type/d', null, 'trim');
        $reason = input('reason', null, 'trim');
        if(empty($orderSn) || empty($this->process_type)) return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数不能为空!');
        if(!in_array($this->process_type,[10,11,12])) return $this->buildFailed(ReturnCode::PARAM_INVALID, '无效的添加类型!');
        $this->time = time();

        if($this->process_type == 10){
            //校验账户信息
            $resAccinfo = $this->checkAccountinfo();
            if ($resAccinfo !== true)
                return $this->buildFailed(ReturnCode::PARAM_INVALID, $resAccinfo);
        }

        Db::startTrans();
        try {
            $costInfo['process_sn'] = $this->getProcesssn();
            if ($costInfo['process_sn'] === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '流程编号生成失败');
            }

            $costInfo['money'] = $this->summoney;  //初始支付金额
            $costInfo['order_sn'] = $orderSn; //订单编号
            $costInfo['process_type'] = $this->DeliveryStatus(); //撤单类型
            $costInfo['reason'] = $reason;  //撤单原因
            $costInfo['stage'] = 301; //初始状态
            $costInfo['create_uid'] = $this->userInfo['id'];
            $costInfo['create_time'] = $costInfo['update_time'] = $this->time;
            //查询出订单的分类
            $costInfo['category'] = Db::name('order')->where(['order_sn' => $orderSn])->value('category');
            //添加申请信息
            if (($this->id = $this->orderother->insertGetId($costInfo)) === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '申请信息添加失败');
            }

            if($this->process_type == 10){
                //添加支付账户信息
                $accountInfo = $this->addAccount();
                if ($accountInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $accountInfo);
                }
            }

            //添加附件
            $attachmentInfo = $this->addAttachment();
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }

            //流程初始化
            $resInitInfo = $this->initProcess($costInfo['order_sn']);
            if ($resInitInfo['code'] == -1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
            }

            //添加操作日志
            $logInfo = $this->orderother->addOperationLog($this->process_type, $this->userInfo, $orderSn);
            if (empty($logInfo)) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '添加日志失败!');
            }

            Db::commit();
            return $this->buildSuccess();

        } catch (\Exception $e) {
            Db::rollback();
            trace('费用申请错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '费用申请添加失败' . $e->getMessage());
        }
    }


    /*
    * 流程初始化
    * */

    private function initProcess($order_sn) {
        $workflow = new Workflow();
        $flow_id = $workflow->getFlowId('CANCEL_ORDER');
        if (empty($flow_id))
            return (['code' => -1, 'msg' => "添加订单流程初始化获取flow_id失败"]);

        $params['flow_id'] = $flow_id;
        $params['user_id'] = $this->userInfo['id'];
        $params['order_sn'] = $order_sn;
        $params['mid'] = $this->id;
        $workflow->init($params);
    }


    /**
     * 校验撤单账户信息
     */
    private function checkAccountinfo() {
        //验证账户信息
        $accountData= input('post.accountinfo/a');
        if(empty($accountData)) return "账户信息不能为空";
        $validate = loader::validate('ValidCost');
        foreach ($accountData as $k => $v) {
            if (!$validate->scene('cancellaccount')->check($v)) {
                return $validate->getError();
            }
            $this->summoney += $v['money'];
        }
        $this->checkData['accountInfo'] = $accountData;
        unset($accountData);
        return true;
    }

    /**
     * 添加类型
     * @param $type
     * @return string
     */
    private function DeliveryStatus() {
        switch ($this->process_type) {
            case 10 :
                return 'CANCEL_ORDER_REFUND';
                break;
            case 11 :
                return 'CANCEL_ORDER_NOREFUND';
                break;
            case 12 :
                return 'CANCEL_ORDER_PREMIUM';
                break;
            default:
                return '';
        }
    }

    /**
     * 添加账户信息
     * @return int|string
     * @throws \Exception
     */
    private function addAccount() {
        $accountInfo = array_map(function($v) {
            $v['order_other_id'] = $this->id;
            $v['create_uid'] = $this->userInfo['id'];
            $v['create_time'] = $v['update_time'] = $this->time;
            return $v;
        }, $this ->checkData['accountInfo']);
        if ($this->orderotheraccount->saveAll($accountInfo) > 0) {
            unset($accountInfo);
            return 1;
        }
        unset($accountInfo);
        return '账户信息添加失败';
    }

    //添加附件
    private function addAttachment() {
        $attach = $this->request->post('attachment/a');
        if(count($attach) > 6) return "附件上传仅限6张!";
        if (isset($attach) && !empty($attach)) {
            $attachArr = [];
            foreach ($attach as $key => $att) {
                $attachArr[$key]['order_other_id'] = $this->id;
                $attachArr[$key]['attachment_id'] = $att;
                $attachArr[$key]['create_time'] = $this->time;
            }
            if (Db::name('order_other_attachment')->insertAll($attachArr) > 0) {
                unset($attachArr);
                return 1;
            }
            unset($attachArr);
            return '附件添加失败';
        } else {
            return 1;
        }
    }

    /*
    * 生成流程编号
    *
    */
    private function getProcesssn(){
        $date = date('Ymd',time());
        $process_sn = $this->orderother->where(['process_sn' => ['like',$date.'%']])->order('id desc')->value('process_sn');
        if($process_sn){
            $num = substr($process_sn, -4);
            if($num == 9999){
                return $date.'10000';
            }
            return $process_sn + 1;
        }else{
            return $date.'0001';
        }

    }


    /**
     * @api {post} admin/CancellationsApply/cancellationsList 撤单申请列表[admin/CancellationsApply/cancellationsList]
     * @apiVersion 1.0.0
     * @apiName cancellationsList
     * @apiGroup CancellationsApply
     * @apiSampleRequest admin/CancellationsApply/cancellationsList
     *
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} refund_type     退费类型 10(退担保费) 11(不退担保费) 12(保费调整)
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  stage_code   审批状态
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * "data": {
    "total": 3,
    "per_page": 10,
    "current_page": 1,
    "last_page": 1,
    "data": [
    {
    "id": 8                          其他业务表主键id
    "process_sn": "201808150008",   流程单号
    "order_sn": "DQJK2018070004",   业务单号
    "finance_sn": "100000023",      财务编号
    "type": "DQJK",                 业务类型
    "estate_name": null,           房产名称
    "stage": "10001",
    "create_time": "2018-08-15 20:26:11",   申请时间
    "name": "管理员"                        理财经理
    "type_text": "短期借款",                业务类型
    "stage_text": "待核算专员审批"          审批状态
     "refund_type": "保费调整"       退费类型
    },
    {
    "process_sn": "201808150007",
    "order_sn": "PDXJ2018070002",
    "finance_sn": "100000011",
    "type": "PDXJ",
    "estate_name": "大芬油画苑C栋0单元902",
    "estate_owner": "毛淑荣",
    "money": "0.00",
    "stage": "10001",
    "create_time": "2018-08-15 20:25:52",
    "name": "管理员"
    }
    ]
    }
     *
     */

    public function cancellationsList(){
        $managerId = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
        $refundType = $this->request->post('refund_type','','int');
        $startTime = strtotime($this->request->post('start_time'));
        $endTime = strtotime($this->request->post('end_time'));
        $stage = $this->request->post('stage_code','','int');
        $searchText = $this->request->post('search_text','','trim');
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
        $userId = $this->userInfo['id'];

        $map = [];
        $userStr = SystemUser::getOrderPowerStr($userId, $this->userInfo['ranking'], $this->userInfo['deptid']);
        if ($userStr != 'super') {
            $map['o.financing_manager_id|x.create_uid'] = ['in', $userStr]; //理财经理或者提交人
        }
        if ($managerId != '0') {
            if ($subordinates == '0') {
                $map['o.financing_manager_id'] = $managerId;
            } else {
                $managerStr = SystemUser::getOrderPowerStr($managerId);
                if ($managerStr != 'super')
                    $map['o.financing_manager_id'] = ['in', $managerStr];
            }
        }

        if($startTime && $endTime){
            if($startTime > $endTime){
                $startTime = $startTime+86399;
                $map['x.create_time'] = array(array('egt', $endTime), array('elt', $startTime));
            }else{
                $endTime = $endTime+86399;
                $map['x.create_time'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        }elseif($startTime){
            $map['x.create_time'] = ['egt',$startTime];
        }elseif($endTime){
            $endTime = $endTime+86399;
            $map['x.create_time'] = ['elt',$endTime];
        }

        $stage && $map['x.stage'] = $stage;
        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];
        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['x.process_type'] = ['in','CANCEL_ORDER_REFUND,CANCEL_ORDER_NOREFUND,CANCEL_ORDER_PREMIUM'];
        if(!empty($refundType)){
            $refundType == 10?$map['x.process_type'] = 'CANCEL_ORDER_REFUND':'';
            $refundType == 11?$map['x.process_type'] = 'CANCEL_ORDER_NOREFUND':'';
            $refundType == 12?$map['x.process_type'] = 'CANCEL_ORDER_PREMIUM':'';
        }

        $field = 'x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::cancellationsList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CancellationsApply/editCancell 编辑撤单申请[admin/CancellationsApply/editCancell]
     * @apiVersion 1.0.0
     * @apiName editCancell
     * @apiGroup CancellationsApply
     * @apiSampleRequest admin/CancellationsApply/editCancell
     *
     *
     * @apiParam {int}  id    其他业务表主键id
     * @apiParam {string}  reason  撤单原因(申请原因)
     * @apiParam {arr}  attachment  附件材料[1,2,3]
     *
     * @apiParam {array} accountinfo  支付账户信息(具体参数在下面)
     * @apiParam {int}   id  账户id
     * @apiParam {string}   bank_account  银行户名
     * @apiParam {string}  bank_card   银行卡号
     * @apiParam {string}  bank  开户银行
     * @apiParam {string}  bank_branch  开户支行
     * @apiParam {float}  money  退款金额
     */

    public function editCancell(){
        $this->id = $this->request->post('id');
        $reason = input('reason', null, 'trim');
        $otherInfo = $this->orderother->where(['id' => $this->id, 'status' => 1])->field('process_type,order_sn')->find();
        if(empty($otherInfo)) return $this->buildFailed(ReturnCode::PARAM_INVALID, "不存在该撤单申请信息!");

        $this->time = time();

        //校验账户信息
        if($otherInfo['process_type'] == 'CANCEL_ORDER_REFUND'){
            $resAccinfo = $this->checkAccountinfo();
            if ($resAccinfo !== true)
                return $this->buildFailed(ReturnCode::PARAM_INVALID, $resAccinfo);
        }

        Db::startTrans();
        try{
            $costInfo['reason'] = $reason;  //撤单原因
            $costInfo['update_time'] = $this->time;
            //更改申请信息
            if (($this->orderother->where(['id' => $this->id, 'stage' => 301])->update($costInfo)) <= 0) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '申请信息更新失败');
            }

            if($otherInfo['process_type'] == 'CANCEL_ORDER_REFUND'){
                //更改支付账户信息
                $accountInfo = $this->updateAccount();
                if ($accountInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $accountInfo);
                }
            }


            //更改附件
            $attachmentInfo = $this->updateAttachment();
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }

            //编辑订单流程初始化
            $resInitInfo = $this->editProcess($otherInfo['order_sn']);
            if ($resInitInfo['code'] == -1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
            }

            Db::commit();
            return $this->buildSuccess();
        }catch (\Exception $e){
            Db::rollback();
            trace('编辑撤单申请错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '撤单申请编辑失败'.$e->getMessage());
        }
    }

    //编辑信息费申请初始化
    /* @Param {int}  id   订单表id
     * @Param {string}  $order_sn  订单号
     * */
    private function editProcess($order_sn) {
        $workflow = new Workflow();
        $flow_id = $workflow->getFlowId('CANCEL_ORDER');
        $entry_id = WorkflowEntry::where(['mid' => $this->id, 'order_sn' => $order_sn, 'status' => -1, 'flow_id' => $flow_id])->value('id');
        if (isset($entry_id) && !empty($entry_id)) {
            $workflow->resend($entry_id, $this->userInfo['id']);
        } else {
            return ['code' => -1, 'msg' => "编辑撤单申请信息流程初始化获取flow_id失败"];
        }
    }


    /**
     * 更改账户信息
     * @return int|string
     * @throws \Exception
     */
    private function updateAccount() {
        //将所有的账户信息删除
        if($this->orderotheraccount->where(['order_other_id' => $this->id])->update(['status' => -1, 'delete_time' => time()]) < 0) return "账户信息删除失败!";
        $accountInfo = $this ->checkData['accountInfo'];
        foreach ($accountInfo as $key => $val){
            $updateid = isset($val['id'])?$val['id']:'';
            if(empty($updateid)){   //添加
                $val['order_other_id'] = $this->id;
                $val['create_uid'] = $this->userInfo['id'];
                $val['create_time'] = $val['update_time'] = $this->time;
                if($this->orderotheraccount->insert($val) === false) return "账户信息添加失败!";
            }else{ //更新
                $val['status'] = 1;
                $val['delete_time'] = null;
                unset($val['id']);
                if(Db::name('order_other_account')->where(['id' => $updateid])->update($val) === false) return "银行账户信息更新失败!";
            }
        }
        unset($accountInfo);
        return 1;
    }

    //更改附件
    private function updateAttachment() {
        //将以前的附件全部删除
        $res = Db::name('order_other_attachment')->where(['order_other_id' => $this->id])->delete();
        if(empty($res)) return '删除附件失败';
        if($msg = $this->addAttachment() !== 1) return $msg;
        return 1;
    }


    /**
     * @api {post} admin/CancellationsApply/getCancellOrderInfo 撤单申请页面订单基本信息[admin/CancellationsApply/getCancellOrderInfo]
     * @apiVersion 1.0.0
     * @apiName getCancellOrderInfo
     * @apiGroup CancellationsApply
     * @apiSampleRequest admin/CancellationsApply/getCancellOrderInfo
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  process_type   撤单类型 10(退担保费) 11(不退担保费) 12(保费调整)
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * "data": {
            "orderinfo": {                        订单基本信息
            "order_sn": "JYDB2018070002",       业务单号
            "finance_sn": "100000001",          财务编号
            "money": "7800000.00",              担保金额
            "financing_dept_id": 24,
            "stage": "1013",
            "name": "徐霞",                     理财经理
            "sname": "担保业务01部",            所属部门
            "financing_mobile": "13554767498",       理财经理联系电话
            "stage_str": "待派单",                   业务订单状态
            "dept_manager_name": "刘传英",            部门经理
            "dept_mobile": "13570871906",             部门经理联系电话
            "estateinfo": "竹盛花园（三期）A座12"     房产名称
            "refund_type": "退担保费"                 退费类型
        }
    "costInfo": {                         费用信息
            "guarantee_fee": "3000.00",       担保费(保费调整)
            "ac_guarantee_fee": "3000.00",    实收担保费(退担保费)
            "ac_fee": "100.00",               手续费
            "ac_self_financing": "0.00",      自筹金额
            "ac_short_loan_interest": "0.00", 短贷利息
            "ac_default_interest": "0.00",    罚息
            "ac_transfer_fee": "0.00",        过账手续费
            "ac_deposit": "0.00",             保证金
            "ac_other_money": "0.00"          其他
            "sum_money": 6000                 费用总计
        }
    }
     */
    public function getCancellOrderInfo(){
        $order_sn = $this->request->Post('order_sn', null, 'trim');
        $this->process_type = $this->request->Post('process_type', null, 'trim');
        if(empty($order_sn) || empty($this->process_type)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空');
        if(!in_array($this->process_type,[10,11,12])) return $this->buildFailed(ReturnCode::PARAM_INVALID, '无效的添加类型!');

        $returnInfo = [];
        //获取订单基本信息
        $returnInfo['orderinfo'] = $this->orderother->getOrderInfo('CANCEL_ORDER_REFUND',$order_sn);
        if($this->process_type == 10) $returnInfo['orderinfo']['refund_type'] = '退担保费';
        if($this->process_type == 11) $returnInfo['orderinfo']['refund_type'] = '不退保费';
        if($this->process_type == 12) $returnInfo['orderinfo']['refund_type'] = '保费调整';

        if(in_array($this->process_type,[10,12])){
            //获取费用信息
            $returnInfo['costInfo'] = $this->orderother->getCostInfo($order_sn);
        }

        return $this->buildSuccess($returnInfo);
    }

    /**
     * @api {post} admin/CancellationsApply/cancellApprovalList 撤单申请审核列表[admin/CancellationsApply/cancellApprovalList]
     * @apiVersion 1.0.0
     * @apiName cancellApprovalList
     * @apiGroup CancellationsApply
     * @apiSampleRequest admin/CancellationsApply/cancellApprovalList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type     业务类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  stage_code   审批状态
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * "data": {
    "total": 3,
    "per_page": 10,
    "current_page": 1,
    "last_page": 1,
    "data": [
    {
    "proc_id": 5960,                  处理明细表主键id
    "id": 8                          其他业务表主键id
    "process_sn": "201808150008",   流程编号
    "order_sn": "DQJK2018070004",   业务单号
    "finance_sn": "100000023",      财务编号
    "type": "DQJK",
    "estate_name": null,           房产名称
    "create_time": "2018-08-15",   申请时间
    "name": "管理员"                  理财经理
    "process_type_text": "退担保费",   退费类型
    "stage_text": "待核算专员审批"        审批状态
    "type_text": "交易担保"             业务类型
    },
    {
    "process_sn": "201808150007",
    "order_sn": "PDXJ2018070002",
    "finance_sn": "100000011",
    "type": "PDXJ",
    "estate_name": "大芬油画苑C栋0单元902",
    "estate_owner": "毛淑荣",
    "money": "0.00",
    "stage": "10001",
    "create_time": "2018-08-15 20:25:52",
    "name": "管理员"
    }
    ]
    }
     *
     */

    public function cancellApprovalList(){
        $createUid = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
        $type = $this->request->post('type',0,'trim');
        $startTime = strtotime($this->request->post('start_time'));
        $endTime = strtotime($this->request->post('end_time'));
        $stage = $this->request->post('stage_code','','int');
        $searchText = $this->request->post('search_text','','trim');
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');

        $map = [];
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['o.financing_manager_id'] = ['in', $userStr];
        }

        if($startTime && $endTime){
            if($startTime > $endTime){
                $startTime = $startTime+86399;
                $map['x.create_time'] = array(array('egt', $endTime), array('elt', $startTime));
            }else{
                $endTime = $endTime+86399;
                $map['x.create_time'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        }elseif($startTime){
            $map['x.create_time'] = ['egt',$startTime];
        }elseif($endTime){
            $endTime = $endTime+86399;
            $map['x.create_time'] = ['elt',$endTime];
        }

        $stage && $map['x.stage'] = $stage;
        $type && $map['o.type'] = $type;
        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];
        $map['x.process_type'] = ['in','CANCEL_ORDER_REFUND,CANCEL_ORDER_NOREFUND,CANCEL_ORDER_PREMIUM'];
        $map['wf.type']= 'CANCEL_ORDER';
        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['d.is_back'] = 0;
        $map['d.is_deleted'] = 1;
        $map['d.user_id']= $this->userInfo['id'];
        $map['d.status'] = ['in','0,9'];
        $field = 'max(d.id) proc_id,x.id,x.process_sn,x.process_type,x.stage,o.order_sn,o.finance_sn,o.type,y.estate_name,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::cancellApprovalList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CancellationsApply/cancellApplyDetail 撤单审批(退费)详情[admin/CancellationsApply/cancellApplyDetail]
     * @apiVersion 1.0.0
     * @apiName cancellApplyDetail
     * @apiGroup CancellationsApply
     * @apiSampleRequest admin/CancellationsApply/cancellApplyDetail
     *
     * @apiParam {int}  id   其他业务表主键id
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * "data": {
    "orderinfo": {                        订单基本信息
    "order_sn": "JYDB2018070002",       业务单号
    "finance_sn": "100000001",          财务编号
    "money": "7800000.00",              担保金额
    "financing_dept_id": 24,
    "stage": "1013",
    "name": "徐霞",                     理财经理
    "sname": "担保业务01部",            所属部门
    "financing_mobile": "13554767498",       理财经理联系电话
    "stage_str": "待派单",                   业务订单状态
    "dept_manager_name": "刘传英",            部门经理
    "dept_mobile": "13570871906",             部门经理联系电话
    "estateinfo": "竹盛花园（三期）A座12"     房产名称
    "refund_type": "退担保费"                 退费类型
    }
    "costInfo": {                         费用信息
    "guarantee_fee": "3000.00",       担保费(保费调整)
    "ac_guarantee_fee": "3000.00",    实收担保费(退担保费)
    "ac_fee": "100.00",               手续费
    "ac_self_financing": "0.00",      自筹金额
    "ac_short_loan_interest": "0.00", 短贷利息
    "ac_default_interest": "0.00",    罚息
    "ac_transfer_fee": "0.00",        过账手续费
    "ac_deposit": "0.00",             保证金
    "ac_other_money": "0.00"          其他
    "sum_money": 6000                 费用总计
    }
     "applyforinfo": {                     申请信息
    "id": 10,                               其他业务表主键id
    "process_sn": "201809060023",           流程编号
    "reason": "测试元原因",                 撤单原因
    "attachment": [                         附件材料
    {
    "id": 5,                        附件id
    "url": "/uploads/20180717/7a07d619c7f9ffb82527db5d386513e5.png",   附件地址
    "name": "毕圆明.png",                 附件名称
    "thum1": "uploads/thum/20180717/7a07d619c7f9ffb82527db5d386513e5.png",  附件缩略图地址
    "ext": "png"                          附件后缀
    },
    {
    "id": 6,
    "url": "/uploads/20180717/36a1b7c84079d280c9f6058c98bf1659.jpg",
    "name": "身份证复印件.jpg",
    "thum1": "uploads/thum/20180717/36a1b7c84079d280c9f6058c98bf1659.jpg",
    "ext": "jpg"
    }
    ]
    }
    "accountinfo": [                支付账户(退费账户)(放款账户)信息
    {
    "id": 11,                     账户信息id
    "bank_account": "张三",       银行户名
    "bank_card": "4521368",       银行卡号
    "bank": "中国银行",            开户银行
    "bank_branch": "车公庙支行",   开户支行
    "money": "12458.00",            退款金额
    "actual_payment": null,      实退金额
    "expense_taxation": null     手续费
    }
    ]
    "approval_info": [                          审批记录
    {
    "order_sn": "JYDB2018070002",
    "create_time": 1531800077,            审批记录的时间
    "process_name": "待业务报单",          审批节点
    "auditor_name": "杨振亚1",            操作人员名称
    "auditor_dept": "权证部",            操作人员部门
    "status": "通过",                   操作
    "content": null                     审批意见
    },
    {
    "order_sn": "JYDB2018070002",
    "create_time": 1531800077,
    "process_name": "待部门经理审批",
    "auditor_name": "甘雯",
    "auditor_dept": "担保业务02部",
    "status": "通过",
    "content": ""
    }
    ]
    }
     *
     */

    public function cancellApplyDetail(){
        $id = $this->request->Post('id', null, 'int');
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空');

        $otherInfo = $this->orderother->where(['id' => $id])->field('order_sn,process_type,process_sn,stage')->find();
        if(empty($otherInfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '不存在此条费用申请信息!');

        try{
            $returnInfo = [];
            $returnInfo['orderinfo'] = $this->orderother->getOrderInfo('CANCEL_ORDER_REFUND',$otherInfo['order_sn']);
            if($otherInfo['process_type'] == 'CANCEL_ORDER_REFUND') $returnInfo['orderinfo']['refund_type'] = '退担保费';
            if($otherInfo['process_type'] == 'CANCEL_ORDER_NOREFUND') $returnInfo['orderinfo']['refund_type'] = '不退保费';
            if($otherInfo['process_type'] == 'CANCEL_ORDER_PREMIUM') $returnInfo['orderinfo']['refund_type'] = '保费调整';

            if(in_array($otherInfo['process_type'],['CANCEL_ORDER_REFUND','CANCEL_ORDER_PREMIUM'])){
                //获取费用信息
                $returnInfo['costInfo'] = $this->orderother->getCostInfo($otherInfo['order_sn']);
            }

            //是否显示处理审批
            $returnInfo['orderinfo']['is_show_approval'] = $this->orderother->isShowApproval($this->userInfo['id'],$otherInfo,$id);

            //获取申请信息
            $returnInfo['applyforinfo'] = $this->orderother->getCancellInfo($id);

            //获取支付账户信息(退费账户信息)
            $returnInfo['accountinfo'] = $this->orderother->getAccountsInfo($id);

            //获取审批记录
            $approvalInfo = $this->orderother->getApprovalRecords($otherInfo['order_sn'],'CANCEL_ORDER',$id);
            $returnInfo['approval_info'] = $approvalInfo;

            return $this->buildSuccess($returnInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '查询失败'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CancellationsApply/cancellManagementList 撤单申请退费管理列表[admin/CancellationsApply/cancellManagementList]
     * @apiVersion 1.0.0
     * @apiName cancellManagementList
     * @apiGroup CancellationsApply
     * @apiSampleRequest admin/CancellationsApply/cancellManagementList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type     业务类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  stage_code   审批状态
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * "data": {
    "total": 3,
    "per_page": 10,
    "current_page": 1,
    "last_page": 1,
    "data": [
    {
    "proc_id": 5960,                  处理明细表主键id
    "id": 8                          其他业务表主键id
    "process_sn": "201808150008",   流程编号
    "order_sn": "DQJK2018070004",   业务单号
    "finance_sn": "100000023",      财务编号
    "money": "2000",                退款金额
    "estate_name": null,           房产名称
    "create_time": "2018-08-15",   申请时间
    "name": "管理员"                  理财经理
    "process_type_text": "退担保费",   退费类型
    "stage_text": "待核算专员审批"        审批状态
    },
    {
    "process_sn": "201808150007",
    "order_sn": "PDXJ2018070002",
    "finance_sn": "100000011",
    "type": "PDXJ",
    "estate_name": "大芬油画苑C栋0单元902",
    "estate_owner": "毛淑荣",
    "money": "0.00",
    "stage": "10001",
    "create_time": "2018-08-15 20:25:52",
    "name": "管理员"
    }
    ]
    }
     *
     */

    public function cancellManagementList(){
        $createUid = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
        $type = $this->request->post('type',0,'trim');
        $startTime = strtotime($this->request->post('start_time'));
        $endTime = strtotime($this->request->post('end_time'));
        $stage = $this->request->post('stage_code','','int');
        $searchText = $this->request->post('search_text','','trim');
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');

        $map = [];
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['o.financing_manager_id'] = ['in', $userStr];
        }

        if($startTime && $endTime){
            if($startTime > $endTime){
                $startTime = $startTime+86399;
                $map['x.create_time'] = array(array('egt', $endTime), array('elt', $startTime));
            }else{
                $endTime = $endTime+86399;
                $map['x.create_time'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        }elseif($startTime){
            $map['x.create_time'] = ['egt',$startTime];
        }elseif($endTime){
            $endTime = $endTime+86399;
            $map['x.create_time'] = ['elt',$endTime];
        }

        $stage && $map['x.stage'] = $stage;
        $type && $map['o.type'] = $type;
        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];
        $map['x.process_type'] = ['in','CANCEL_ORDER_REFUND'];
        $map['wf.type']= 'CANCEL_ORDER';
        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['d.is_back'] = 0;
        $map['d.is_deleted'] = 1;
        $map['d.user_id']= $this->userInfo['id'];
        $map['d.status'] = ['in','0,9'];
        $field = 'max(d.id) proc_id,x.id,x.process_sn,x.process_type,x.stage,x.money,o.order_sn,o.finance_sn,y.estate_name,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::cancellManagementlList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CancellationsApply/addCancellRefund 撤单确定退费[admin/CancellationsApply/addCancellRefund]
     * @apiVersion 1.0.0
     * @apiName addCancellRefund
     * @apiGroup CancellationsApply
     * @apiSampleRequest admin/CancellationsApply/addCancellRefund
     *
     * @apiParam {array} accountinfo  账户信息(具体参数在下面)
     * @apiParam {int}  id   账户信息id
     * @apiParam {float}  actual_payment  实付金额
     * @apiParam {float}  expense_taxation  手续费
     */

    public function addCancellRefund(){
        $accountData = $this->request->post('accountinfo/a');
        if(empty($accountData) || !isset($accountData)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '账户信息不能为空');

        //根据账户id查询出其它信息表类型
        $otherInfo = $this->orderotheraccount->alias('ooa')
            ->join('order_other oo', 'ooa.order_other_id = oo.id')
            ->where(['ooa.id' => $accountData[0]['id']])
            ->field('oo.id,oo.process_type,oo.order_sn,oo.process_sn')
            ->find();
        if(empty($otherInfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '账户信息有问题!');

        //校验费用支付
        foreach ($accountData as $k => $v){
            $moneyinfo = $this->orderotheraccount->where(['id' => $v['id']])->field('money')->find();
            $sumMoney = $v['actual_payment'] + $v['expense_taxation'];
            if($moneyinfo['money'] != $sumMoney) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '存在账户实付金额加手续费不等于支付金额!');
        }

        $procInfo = ProcService::getDispatchProcId('CANCEL_ORDER',$otherInfo['order_sn'],$otherInfo['id'],1,$this->userInfo['id']);
        $config = [
            'user_id' => $this->userInfo['id'], // 用户id
            'user_name' => $this->userInfo['name'], // 用户姓名
            'proc_id' => $procInfo['id'],  // 当前步骤id
            'order_sn' => $otherInfo['order_sn']
        ];

        // 启动事务
        Db::startTrans();
        try{
            //更新账户表
            $accInfo = $this->orderotheraccount->saveAll($accountData);
            if($accInfo <= 0){
                // 回滚事务
                Db::rollback();
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '费用支付失败!');
            }

            //添加操作日志
            $logInfo = $this->orderother->addSubLog($this->Deliverynum($otherInfo['process_type']), $this->userInfo,$otherInfo['order_sn'],1);
            if (empty($logInfo)) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '添加日志失败!');
            }

            $workflow = new Workflow($config);
            // 审批通过 走审批流
            $workflow->pass();

            // 提交事务
            Db::commit();
            return $this->buildSuccess();
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '确认退费失败'.$e->getMessage());
        }
    }


    /**
     * 转换类型
     * @param $type
     * @return string
     */
    private function Deliverynum($processtype) {
        switch ($processtype) {
            case 'CANCEL_ORDER_REFUND' :
                return 10;
                break;
            case 'CANCEL_ORDER_NOREFUND' :
                return 11;
                break;
            case 'CANCEL_ORDER_PREMIUM' :
                return 12;
                break;
            default:
                return '';
        }
    }



}