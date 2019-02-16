<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/8/14
 * Time: 17:02
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
use app\model\TrialProcess;

class CostApply extends Base {
    private $orderother;
    private $orderotheraccount;
    private $process_type; //费用申请类型
    private $time;
    private $id;  //其他业务表id
    private $summoney = 0;  //费用总计
    private $checkData = array(
        'costInfo' => [], //申请信息
        'accountInfo' => [], //账户信息
    ); //校验数据

    public function _initialize() {
        parent::_initialize();
        $this->orderother = new OrderOther();
        $this->orderotheraccount = new OrderOtherAccount();
        $this->orderotherexhibition = new OrderOtherExhibition();
        $this->ordercommunicate = new OrderCommunicate();
        $this->ordercommunicatereply = new OrderCommunicateReply();
    }

    /**
     * @api {post} admin/CostApply/addCostApply 添加费用申请[admin/CostApply/addCostApply]
     * @apiVersion 1.0.0
     * @apiName addCostApply
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/addCostApply
     *
     * @apiParam {int}  process_type   添加类型 1信息费支付(INFO_FEE) 2首期转账(SQ_TRANSFER) 3退保证金(DEPOSIT)
     * 4现金按天退担保费(XJ_GUARANTEE_FEE) 5额度退担保费(ED_GUARANTEE_FEE) 6额度类订单放尾款申请  7现金类订单放尾款申请
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  loan_way   放款方式 1转账
     * @apiParam {int}  transfer_type   到账类型 1实时 2普通
     * @apiParam {float}   info_fee_rate  信息费费率
     * @apiParam {int}  info_fee   信息费金额
     * @apiParam {string}  collector  信息费收取人
     * @apiParam {int}  mobile  联系电话
     * @apiParam {string}  reason  支付原因(申请原因)
     * @apiParam {arr}  attachment  附件材料[1,2,3]
     * @apiParam {int}  order_type  订单类型 1内单 2外单
     * @apiParam {float}  return_money  额度放尾款（退赎楼金额） 现金放尾款（退回款金额）
     * @apiParam {float}  default_interest  退罚息金额
     * @apiParam {float}  short_loan  退短贷金额
     * @apiParam {float}  used_interest  已用罚息金额
     * @apiParam {float}  short_loan  已用短贷利息
     *
     * @apiParam {array} accountinfo  支付账户信息(具体参数在下面)
     * @apiParam {string}   bank_account  银行户名
     * @apiParam {string}  bank_card   银行卡号
     * @apiParam {string}  bank  开户银行
     * @apiParam {string}  bank_branch  开户支行
     * @apiParam {float}  money  信息费(支付金额) 首期款(转账金额) 保证金(应退金额) 按天退担保费(应退担保金额) 额度退担保费,额度类订单放尾款,现金类订单放尾款(退款金额)
     * @apiParam {int}  account_type  账户类型 1业主 2客户 3收款确定书
     * @apiParam {int}  account_source  账户来源 1合同 2财务确认书 3其他
     * @apiParam {float}  exhibition_fee  应退展期费(按天退担保费申请专有)
     */

    public function addCostApply(){
        $this->process_type = input('process_type/d', null, 'trim');
        $orderSn = input('order_sn', null, 'trim');
        $this->time = time();
        if(!in_array($this->process_type,[1,2,3,4,5,6,7])) return $this->buildFailed(ReturnCode::PARAM_INVALID, '无效的添加类型!');
        $orderType = substr($orderSn, 0, 4);
        if($this->process_type === 6 && $orderType != 'JYDB') return $this->buildFailed(ReturnCode::PARAM_INVALID, '现金类订单不能添加额度类订单放尾款申请!');
        if($this->process_type === 7 && $orderType == 'JYDB') return $this->buildFailed(ReturnCode::PARAM_INVALID, '额度类订单不能添加现金类订单放尾款申请!');
        //校验申请信息
        $resAppinfo = $this->checkApplicationinfo();
        if ($resAppinfo !== true)
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $resAppinfo);

        //校验账户信息
        $resAccinfo = $this->checkAccountinfo();
        if ($resAccinfo !== true)
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $resAccinfo);

        $costInfo = $this ->checkData['costInfo'];
        Db::startTrans();
        try{
            $costInfo['process_sn'] = $this->getProcesssn();
            if($costInfo['process_sn'] === false){
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '流程编号生成失败');
            }

            $costInfo['money'] = $this->summoney;  //初始支付金额
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

            //添加支付账户信息
            $accountInfo = $this->addAccount();
            if ($accountInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $accountInfo);
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
            $logInfo = $this->orderother->addOperationLog($this->process_type,$this->userInfo,$orderSn);
            if (empty($logInfo)) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '添加日志失败!');
            }

            Db::commit();
            return $this->buildSuccess();

        }catch (\Exception $e){
            Db::rollback();
            trace('费用申请错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '费用申请添加失败'.$e->getMessage());
        }
    }

    /*
    * 流程初始化
    * */

    private function initProcess($order_sn) {
        $workflow = new Workflow();
        if($this->process_type == 7){
            $flow_id = $workflow->getFlowId('ED_TAIL');
        }else{
            $flow_id = $workflow->getFlowId($this->DeliveryStatus());
        }

        if (empty($flow_id))
            return (['code' => -1, 'msg' => "添加订单流程初始化获取flow_id失败"]);
        $params['flow_id'] = $flow_id;
        $params['user_id'] = $this->userInfo['id'];
        $params['order_sn'] = $order_sn;
        $params['mid'] = $this->id;
        //var_dump($params);exit;
        $workflow->init($params);
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
     * 校验信息费申请信息
     * @return array
     */
    private function checkApplicationinfo() {
        //验证申请信息
        $applicData['order_sn'] = $this->request->post('order_sn', '', 'trim'); //订单编号
        $applicData['process_type'] = $this->DeliveryStatus(); //流程类型
        $applicData['loan_way'] = $this->request->post('loan_way'); //放款方式
        $applicData['transfer_type'] = $this->request->post('transfer_type'); //到账类型
        $applicData['reason'] = $this->request->post('reason');
        if($this->process_type === 1){
            $applicData['info_fee_rate'] = $this->request->post('info_fee_rate'); //信息费费率
            //查询出该订单的信息费金额
            $infoFee = Db::name('order_guarantee')->where(['order_sn' => $applicData['order_sn']])->value('info_fee');
            $applicData['info_fee'] = $infoFee;   //信息费金额
            $applicData['collector'] = $this->request->post('collector');  //信息费收取人
            $applicData['mobile'] = $this->request->post('mobile');
            $validate = loader::validate('ValidCost');
            if (!$validate->scene('addinfocosts')->check($applicData)) {
                return $validate->getError();
            }

        }elseif($this->process_type === 6){
            $applicData['order_type'] = $this->request->post('order_type');
            $applicData['return_money'] = $this->request->post('return_money'); //退赎楼金额
            $applicData['default_interest'] = $this->request->post('default_interest'); //退罚息金额
            $applicData['short_loan'] = $this->request->post('short_loan'); //退短贷金额
            $applicData['used_interest'] = $this->request->post('used_interest'); //已用罚息金额
            $applicData['used_short_loan'] = $this->request->post('used_short_loan'); //已用短贷利息
            $applicData['money'] = sprintf("%.2f", $applicData['return_money']) + sprintf("%.2f", $applicData['default_interest']) + sprintf("%.2f", $applicData['short_loan']);
            $validate = loader::validate('ValidCost');
            //var_dump($applicData);exit;
            $returnInfo = $this->orderother->showTailSection($applicData['order_sn'], $this->id);
            $applicData['back_floor'] = $returnInfo['linesInfo'][0]['back_floor'];
            $applicData['can_back_money'] = $returnInfo['canBackInfo'][0]['can_back_money'];
            $applicData['can_back_short_loan'] = $returnInfo['shortLoan'][0]['can_back_short_loan'];
            if (!$validate->scene('addlinesinfo')->check($applicData)) {
                return $validate->getError();
            }
            unset($applicData['back_floor']);
            unset($applicData['can_back_money']);
            unset($applicData['can_back_short_loan']);
            //获取实收罚息金额 和实收短贷利息
            $defaultInterest = $returnInfo['canBackInfo'][0]['ac_default_interest'];
            $shortLoanInterest = $returnInfo['shortLoan'][0]['ac_short_loan_interest'];
            if($applicData['used_interest'] > $defaultInterest) return '已用罚息金额不能大于实收罚息金额';
            if($applicData['used_short_loan'] > $shortLoanInterest) return '已用短贷利息不能大于实收短贷利息';
        }elseif($this->process_type === 7){
            $applicData['order_type'] = $this->request->post('order_type');
            $applicData['return_money'] = $this->request->post('return_money'); //退回款金额
            $applicData['default_interest'] = $this->request->post('default_interest'); //退罚息金额
            $applicData['used_interest'] = $this->request->post('used_interest'); //已用罚息金额
            $returnInfo = $this->orderother->showRemainingCash($applicData['order_sn'], $this->id);
            $applicData['back_floor'] = $returnInfo['linesInfo'][0]['back_floor'];
            $applicData['can_back_money'] = $returnInfo['canBackInfo'][0]['can_back_money'];
            $applicData['money'] = sprintf("%.2f", $applicData['return_money']) + sprintf("%.2f", $applicData['default_interest']);
            $validate = loader::validate('ValidCost');
            if (!$validate->scene('addCashinfo')->check($applicData)) {
                return $validate->getError();
            }
            unset($applicData['back_floor']);
            unset($applicData['can_back_money']);
            //获取实收罚息金额
            $defaultInterest = $returnInfo['canBackInfo'][0]['ac_default_interest'];
            if($applicData['used_interest'] > $defaultInterest) return '已用罚息金额不能大于实收罚息金额';
        }else{
            $applicData['order_type'] = $this->request->post('order_type');
            $validate = loader::validate('ValidCost');
            if (!$validate->scene('addotherinfo')->check($applicData)) {
                return $validate->getError();
            }

        }

        //验证是否存在该订单
        $orderid = Db::name('order')->where(['order_sn' => $applicData['order_sn'],'status' => 1])->value('id');
        if(empty($orderid)) return "该订单不存在";

        $this->checkData['costInfo'] = $applicData;
        unset($applicData);
        return true;
    }

    /**
     * 添加类型
     * @param $type
     * @return string
     */
    private function DeliveryStatus() {
        switch ($this->process_type) {
            case 1 :
                return 'INFO_FEE';
                break;
            case 2 :
                return 'SQ_TRANSFER';
                break;
            case 3 :
                return 'DEPOSIT';
                break;
            case 4 :
                return 'XJ_GUARANTEE_FEE';
                break;
            case 5 :
                return 'ED_GUARANTEE_FEE';
                break;
            case 6 :
                return 'ED_TAIL';
                break;
            case 7 :
                return 'XJ_TAIL';
                break;
            case 8 :
                return 'EXHIBITION';
                break;
            default:
                return '';
        }
    }

    /**
     * 校验信息费账户信息
     */
    private function checkAccountinfo() {
        //验证账户信息
        $accountData= input('post.accountinfo/a');
        $orderSn = $this->request->post('order_sn','','trim');
        if(empty($accountData)) return "账户信息不能为空";
        $validate = loader::validate('ValidCost');
        //查询出实收担保费和实收展期费
        $guaranteeInfo = Db::name('order_guarantee')->where(['order_sn' => $orderSn, 'status' => 1])->field('ac_guarantee_fee,ac_exhibition_fee,money,ac_deposit,info_fee')->find();
        if($this->process_type === 1){
            foreach ($accountData as $k => $v){
                if (!$validate->scene('infozccount')->check($v)) {
                    return $validate->getError();
                }
                $this->summoney += $v['money'];
            }
            if($this->summoney > $guaranteeInfo['info_fee']) return '支付金额不能大于信息费金额!';

        }elseif (in_array($this->process_type,[2,3,5,6,7])){
            foreach ($accountData as $k => $v) {
                if (!$validate->scene('qitaaccount')->check($v)) {
                    return $validate->getError();
                }
                $this->summoney += $v['money'];
            }
            if($this->process_type === 3){
                if($this->summoney > $guaranteeInfo['ac_deposit']) return '应退金额不得大于实收保证金!';
            }

            if($this->process_type === 5){
                if($this->summoney > $guaranteeInfo['ac_guarantee_fee']) return '应退担保费不得大于实收担保费!';
            }

            if(in_array($this->process_type,[6,7])){
                if($this->summoney != $this ->checkData['costInfo']['money']) return '退款金额必须等于退款总计!';
            }

        }else{
            $sumsMoneys = 0;
            $sumsexhibitionFee = 0;
            foreach ($accountData as $k => $v) {
                if (!$validate->scene('cashaccount')->check($v)) {
                    return $validate->getError();
                }
                $this->summoney = $this->summoney + $v['money'] + $v['exhibition_fee'];
                $sumsMoneys += $v['money'];
                $sumsexhibitionFee += $v['exhibition_fee'];
            }
            //应退担保费不能大于实收担保费，应退展期费不能大于展期费
            if(empty($guaranteeInfo)) return '该订单存在问题!';
            if($sumsMoneys > $guaranteeInfo['ac_guarantee_fee']) return '应退担保费不能大于实收担保费!';
            if($sumsexhibitionFee > $guaranteeInfo['ac_exhibition_fee']) return '应退展期费不能大于实收展期费!';
        }
        $this->checkData['accountInfo'] = $accountData;
        unset($accountData);
        return true;
    }

    /**
     * @api {post} admin/CostApply/infoCostList 信息费支付申请列表[admin/CostApply/infoCostList]
     * @apiVersion 1.0.0
     * @apiName infoCostList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/infoCostList
     *
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
                "estate_owner": null,          业主姓名
                "money": "0.00",               支付金额
                "stage": "10001",
                "create_time": "2018-08-15 20:26:11",   申请时间
                "name": "管理员"                        申请人
                "type_text": "短期借款",                业务类型
                "stage_text": "待核算专员审批"          审批状态
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

    public function infoCostList(){
        $managerId = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
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
        $searchText && $map['x.order_sn|x.process_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['x.process_type'] = 'INFO_FEE';

        $field = 'x.id,x.process_sn,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::infoPayList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/getOrderInfo 添加费用申请页面订单基本信息[admin/CostApply/getOrderInfo]
     * @apiVersion 1.0.0
     * @apiName getOrderInfo
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/getOrderInfo
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  process_type   添加类型 1信息费支付 2首期转账 3退保证金
     * 4现金按天退担保费 5额度退担保费 6额度类订单放尾款申请 7现金类订单放尾款申请 8展期申请
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * "data": {
        "orderinfo": {                        订单基本信息
        "order_sn": "JYDB2018070002",       业务单号
        "finance_sn": "100000001",          财务编号
        "money": "7800000.00",              担保金额
        "order_source": 2,
        "source_info": "中原地产",           来源机构
        "name": "梁小健",                    理财经理
        "sname": "担保业务02部",             所属部门
        "info_fee": "21504.00",             信息费金额(预计信息费)
        "estateinfo": "绿海湾花园A座1单元1601,绿海湾花园A座1单元1601a",    房产名称
        "order_source_str": "银行介绍",                 业务来源
        "costomerinfo": "杨丽娟,梁玉生,孙士钧,刘佩铃"       担保申请人
        "paymatters": "首期款转账",                        付款事项
        "estateOwner": "杨丽娟,杨丽娟",                    业主姓名
        "associated": "TMXJ2018070003,JYXJ2018070005,JYXJ2018070006"  关联订单
        "ac_deposit": "1.00",                      实收保证金
        "ac_guarantee_fee": "62400.00",            担保费(实收担保费)
        "dept_manager_name": "刘传英",             部门经理
        "guarantee_fee": "45000.00",               预收担保费
        "ac_fee": "0.00",                           手续费
        "ac_exhibition_fee": "0.00",                展期费
        "ac_overdue_money": "0.00",                 逾期金额
        "guarantee_rate": 0.8,                     担保费率
         "seller": "张念友",                       卖方姓名
        "buyer": "敬若冰"                          买方姓名
        }
    "dpInfo": {                               首期款信息
    "dp_strike_price": "5900000.00",             成交价格
    "dp_earnest_money": "80000.00",             定金金额
    "dp_money": null,                          首期款金额
    "dp_supervise_bank": "建设银行",           资金监管银行
    "dp_supervise_bank_branch": null,          资金监管支行
    "dp_supervise_date": "2018-04-24",         监管日期
    "dp_buy_way": "按揭购房",                  购房方式  1全款购房2按揭购房
    "dp_now_mortgage": "5.00"                  现按揭成数
    }
    "mortgage_info": [                               现按揭信息
    {
    "type": "NOW",
    "mortgage_type": 1,
    "money": "900000.00",                     现按揭金额
    "organization_type": "1",
    "organization": "建设银行-深圳振兴支行",     现按揭机构
    "mortgage_type_str": "公积金贷款",           现按揭类型
    "organization_type_str": "银行"         现按揭机构类型
    },
    {
    "type": "NOW",
    "mortgage_type": 2,
    "money": "2050000.00",
    "organization_type": "1",
    "organization": "建设银行-深圳振兴支行",
    "mortgage_type_str": "商业贷款",
    "organization_type_str": "银行"
    }
    ]
    "estate_info": [   房产信息
    {
    "estate_name": "国际新城一栋",                  房产名称
    "estate_region": "深圳市|罗湖区|桂园街道",      所属城区
    "estate_area": 70,                             房产面积
    "estate_owner": "杨丽娟",                       产权人
    "estate_certtype": 1,                          产证类型
    "estate_certnum": 11111,                       产证编码
    "house_type": 1                                房产类型 1分户 2分栋
    },
    {
    "estate_name": "国际新城一栋",
    "estate_district": "440303",
    "estate_area": 70,
    "estate_certtype": 1,
    "estate_certnum": 11111,
    "house_type": 1
    }
    ],
    "advance_info": [              担保费信息
    {
    "advance_money": "12000000.00",  垫资金额
    "advance_day": 5,                垫资天数
    "advance_rate": 0.06,            垫资费率
    "advance_fee": "36000.0",        垫资费
    "remark": "",                    备注说明
    "id": 3
    }
    ]
    "outbackinfo": {                       出账回款信息
             "out_account_total": "1500000.00",    垫资金额
             "account_com_total": 1500000,         垫资出账金额
             "account_cus_total": 600000,
             "out_time": "2018-08-23",           垫资出账时间
             "expectday": "2018-08-28",          合同回款日
             "readyin_money": 0,                 已回款金额
             "unreadyin_money": 1500000,         待回款金额(展期申请信息里面也取该值)
             "return_time": null                 最近回款时间
         }
    "cost_info": {                         费用信息(展期专用)
        "sumexhibition_day": "17",           已展期总天数
        "sumexhibition_fee": "1010.00",      已收展期费
        "sumperiods": 2,                     已展期期数
        "guarantee_fee": "3000.00",          预收担保费
        "info_fee": "0.00",                  预计信息费
        "ac_guarantee_fee": "4534.00",      实收担保费
        "ac_overdue_money": "453.00",        实收逾期费
        "exhibition_rate": 0.12,              展期费率(展期申请信息)
        "yuqimoney": "213.00",              应收逾期费
        "yuqiday": 2                         逾期天数
        }
    "linesInfo": {               额度(现金)赎楼信息
        "create_time": 1534935737,       银行放款时间(额度放尾款)  回款时间(现金放尾款)
        "loan_money": "2836000.00",      银行放款金额(额度放尾款)  实际回款金额(现金放尾款)
        "foreclosure": 0,                赎楼金额
        "ac_return_money": "0.00",       赎楼返还款
        "sum_return_money": null,        已退赎楼金额
        "back_floor": 2836000            可退赎楼金额
        },
    "canBackInfo": {           罚息信息
        "ac_default_interest": "0.00",    实收罚息金额
        "used_interest": 0,                   已用罚息金额
        "sum_default_interest": null,     已退罚息金额
        "can_back_money": 0               可退罚息金额
        },
    "shortLoan": {            短贷利息信息
        "ac_short_loan_interest": "0.00",      实收短贷利息
         "used_short_loan" :   "0"             已用短贷利息
        "sum_short_loan": null,                已退短贷利息金额
        "can_back_short_loan": 0               可退短贷利息金额
        }
     }
     */
     public function getOrderInfo(){
         $order_sn = $this->request->Post('order_sn', null, 'trim');
         $this->process_type = $this->request->Post('process_type', null, 'trim');
         if(empty($order_sn) || empty($this->process_type)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空');
         if(!in_array($this->process_type,[1,2,3,4,5,6,7,8])) return $this->buildFailed(ReturnCode::PARAM_INVALID, '无效的添加类型!');

         $returnInfo = [];
         //获取订单基本信息
         $returnInfo['orderinfo'] = $this->orderother->getOrderInfo($this->DeliveryStatus(),$order_sn);

         if($this->process_type == 2){
             //获取首期款信息
             $returnInfo['dpInfo'] = OrderComponents::orderDp($order_sn, 'dp_strike_price,dp_earnest_money,dp_money,dp_supervise_bank,dp_supervise_bank_branch,dp_buy_way,dp_now_mortgage,dp_supervise_date');

             //现按揭信息
             $mortgageInfo = OrderComponents::showMortgage($order_sn, 'type,mortgage_type,money,organization_type,organization','NOW');
             $newMortgageArr = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_TYPE'));
             $newAgencyArr = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_AGENCY_TYPE '));
             if (!empty($mortgageInfo)) {
                 foreach ($mortgageInfo as $k => $v){
                     $mortgageInfo[$k]['mortgage_type_str'] = $newMortgageArr[$v['mortgage_type']] ? $newMortgageArr[$v['mortgage_type']] : '';
                     $mortgageInfo[$k]['organization_type_str'] = $newAgencyArr[$v['organization_type']] ? $newAgencyArr[$v['organization_type']] : '';
                 }
             }
             $returnInfo['mortgage_info'] = $mortgageInfo;
         }

         if($this->process_type == 3){
             //获取房产信息
             $estateInfo = OrderComponents::showEstateList($order_sn,'estate_name,estate_region,estate_area,estate_owner,estate_certtype,estate_certnum,house_type','DB');
             $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
             if($estateInfo){
                 foreach($estateInfo as $k => $val){
                     $estateInfo[$k]['estate_certtype_str'] = $newStageArr[$val['estate_certtype']] ? $newStageArr[$val['estate_certtype']]:'';
                 }
             }
             $returnInfo['estate_info'] = $estateInfo;
         }

         if($this->process_type == 4){
             //担保费信息
             $advanceInfo = OrderComponents::advanceMoney($order_sn);
             $returnInfo['advance_info'] = $advanceInfo;
         }

         if($this->process_type == 8){
             //出账回款信息
             $outbackinfo = FinancialBack::outBackinfo($order_sn);
             if($outbackinfo === false) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '该订单存在问题!');
             $returnInfo['outbackinfo'] = $outbackinfo;

             //费用信息
             $costInfo = $this->orderother->costInformation($order_sn,$outbackinfo['expectday']);
             $returnInfo['cost_info'] = $costInfo;
         }

         if($this->process_type == 6){
             //获取新房产信息
             /*$estateInfo = OrderComponents::showEstateList($order_sn,'estate_name,estate_region,estate_area,estate_owner,estate_certtype,estate_certnum,house_type','DB');
             $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
             if($estateInfo){
                 foreach($estateInfo as $k => $val){
                     $estateInfo[$k]['estate_certtype_str'] = $newStageArr[$val['estate_certtype']] ? $newStageArr[$val['estate_certtype']]:'';
                 }
             }
             $returnInfo['estate_info'] = $estateInfo;*/

             //额度类订单放尾款信息
             $resultInfo = $this->orderother->showTailSection($order_sn);
             //额度赎楼信息
             $returnInfo['linesInfo'] = $resultInfo['linesInfo'];
             //罚息信息
             $returnInfo['canBackInfo'] = $resultInfo['canBackInfo'];
             //短贷利息信息
             $returnInfo['shortLoan'] = $resultInfo['shortLoan'];

         }

         if($this->process_type == 7){
             //获取新房产信息
             /*$estateInfo = OrderComponents::showEstateList($order_sn,'estate_name,estate_region,estate_area,estate_owner,estate_certtype,estate_certnum,house_type','DB');
             $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
             if($estateInfo){
                 foreach($estateInfo as $k => $val){
                     $estateInfo[$k]['estate_certtype_str'] = $newStageArr[$val['estate_certtype']] ? $newStageArr[$val['estate_certtype']]:'';
                 }
             }
             $returnInfo['estate_info'] = $estateInfo;*/

             //现金类订单放尾款信息
             $resultInfo = $this->orderother->showRemainingCash($order_sn);
             //现金赎楼信息
             $returnInfo['linesInfo'] = $resultInfo['linesInfo'];
             //罚息信息
             $returnInfo['canBackInfo'] = $resultInfo['canBackInfo'];


         }

         return $this->buildSuccess($returnInfo);
     }




    /**
     * @api {post} admin/CostApply/costApplyDetail 信息费支付(首期款转账,退保证金,按天,额度退担保)详情[admin/CostApply/costApplyDetail]
     * @apiVersion 1.0.0
     * @apiName costApplyDetail
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/costApplyDetail
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
                "order_source": 2,
                "source_info": "中原地产",           来源机构
                "name": "梁小健",                    理财经理
                "sname": "担保业务02部",             所属部门
                "estateinfo": "绿海湾花园A座1单元1601,绿海湾花园A座1单元1601a",    房产名称
                "order_source_str": "银行介绍",                 业务来源
                "costomerinfo": "杨丽娟,梁玉生,孙士钧,刘佩铃"       担保申请人
                 "paymatters": "首期款转账",                        付款事项
                 "estateOwner": "杨丽娟,杨丽娟",                    业主姓名
                 "associated": "TMXJ2018070003,JYXJ2018070005,JYXJ2018070006"  关联订单
                 "ac_deposit": "1.00",                      实收保证金
                 "ac_guarantee_fee": "62400.00",            担保费(实收担保费)
                "ac_fee": "0.00",                           手续费
                "ac_exhibition_fee": "0.00",                展期费
                "ac_overdue_money": "0.00",                 逾期金额
                 "guarantee_rate": 0.8,                     担保费率
                 "is_show_communication"             是否展示沟通的按钮  1需要显示  2不需要显示
                 "is_show_approval"                   是否显示处理审批  1需要显示  2不需要要显示
            }
             "applyforinfo": {                     申请信息
                "id": 10,                               其他业务表主键id
                "process_sn": "201808150008",           流程编码
                "order_type": null,                     订单类型 1内单 2外单
                "loan_way": 1,                          放款方式 1转账
                "transfer_type": 1,                     到账类型 1实时 2普通
                "info_fee_rate": 0.55,                  信息费费率
                "info_fee": "10523.00",                 信息费金额
                "collector": "张三",                    信息费收取人
                "mobile": "18529113254",                联系电话
                "reason": "测试元原因",                 支付原因(申请原因)
                "return_money": "1125.00",              待回款金额(展期申请)  退赎楼金额(额度放尾款)  退回款金额(现金放尾款)
                "total_money": "5000.00",               应交金额
                "money": "4000.00",                     现金按天退担保费、额度退担保费、额度放尾款（退款总计）、现金放尾款（退款总计）、展期实交金额
                "exhibition_rate": 0.14,                展期费率/日
                "exhibition_starttime": "2018-08-08",   展期开始时间
                "exhibition_endtime": "2018-08-09",     展期结束时间
                "exhibition_day": 2,                    展期天数
                "exhibition_fee": "20000.00",           展期费用
                "exhibition_guarantee_fee": "2000.00",  担保费抵扣金额
                "exhibition_info_fee": "3000.00",       信息费抵扣金额
                "default_interest": "200.00",           退罚息金额
                "short_loan": null,                     退短贷金额
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
                "account_type": 1,            账户类型 1业主 2客户 3收款确定书
                "account_source": 1,          账户来源 1合同 2财务确认书 3其他
                "bank_card": "4521368",       银行卡号
                "bank": "中国银行",            开户银行
                "bank_branch": "车公庙支行",   开户支行
                "money": "12458.00",           支付金额(信息费) 转账金额(首期款转账) 应退金额(退保证金) 应退担保金额(现金按天退) 退款金额(额度退担保费)
                "exhibition_fee": "12345.00",  应退展期费(现金按天退专有)
                "actual_payment": null,      实付金额(信息费) 实转金额(首期款转账) 实退金额(退保证金，现金按天退，额度退担保费)
                "expense_taxation": null     扣税费用(信息费)  手续费(首期款转账,退保证金，现金按天退，额度退担保费)
                },
                {
                "bank_account": "李四",
                "account_type": 2,
                "account_source": 3,
                "bank_card": "123445",
                "bank": "中国农业银行",
                "bank_branch": "车公庙支行",
                "money": "1456.00",
                "exhibition_fee": "23456.00",
                "actual_payment": null,
                "expense_taxation": null
                }
            ]
         "dpInfo": {                               首期款信息
            "dp_strike_price": "5900000.00",             成交价格
            "dp_earnest_money": "80000.00",             定金金额
            "dp_money": null,                          首期款金额
            "dp_supervise_bank": "建设银行",           资金监管银行
            "dp_supervise_bank_branch": null,          资金监管支行
            "dp_supervise_date": "2018-04-24",         监管日期
            "dp_buy_way": "按揭购房",                  购房方式  1全款购房2按揭购房
            "dp_now_mortgage": "5.00"                  现按揭成数
            }
         "mortgage_info": [                               现按揭信息
                {
                "type": "NOW",
                "mortgage_type": 1,
                "money": "900000.00",                     现按揭金额
                "organization_type": "1",
                "organization": "建设银行-深圳振兴支行",     现按揭机构
                "mortgage_type_str": "公积金贷款",           现按揭类型
                "organization_type_str": "银行"         现按揭机构类型
                },
                {
                "type": "NOW",
                "mortgage_type": 2,
                "money": "2050000.00",
                "organization_type": "1",
                "organization": "建设银行-深圳振兴支行",
                "mortgage_type_str": "商业贷款",
                "organization_type_str": "银行"
                }
            ]
         "estate_info": [   房产信息
                {
                "estate_name": "国际新城一栋",                  房产名称
                "estate_region": "深圳市|罗湖区|桂园街道",      所属城区
                "estate_area": 70,                             房产面积
                "estate_owner": "杨丽娟",                       产权人
                "estate_certtype": 1,
                "estate_certtype_str:"房产证"                  产证类型
                "estate_certnum": 11111,                       产证编码
                "house_type": 1                                房产类型 1分户 2分栋
                },
                {
                "estate_name": "国际新城一栋",
                "estate_district": "440303",
                "estate_area": 70,
                "estate_certtype": 1,
                "estate_certnum": 11111,
                "house_type": 1
                }
            ],
         "advance_info": [              担保费信息
                {
                "advance_money": "12000000.00",  垫资金额
                "advance_day": 5,                垫资天数
                "advance_rate": 0.06,            垫资费率
                "advance_fee": "36000.0",        垫资费
                "remark": "",                    备注说明
                "id": 3
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
        "outbackinfo": {                       出账回款信息
            "out_account_total": "1500000.00",    垫资金额
            "account_com_total": 1500000,         垫资出账金额
            "account_cus_total": 600000,
            "out_time": "2018-08-23",           垫资出账时间
            "expectday": "2018-08-28",          合同回款日
            "readyin_money": 0,                 已回款金额
            "unreadyin_money": 1500000,         待回款金额
            "return_time": null                 最近回款时间
            }
        "cost_info": {                         费用信息
            "sumexhibition_day": "17",           已展期总天数
            "sumexhibition_fee": "1010.00",      已收展期费
            "sumperiods": 2,                     已展期期数
            "guarantee_fee": "3000.00",          预收担保费
            "info_fee": "0.00",                  预计信息费
            "ac_guarantee_fee": "4534.00",      实收担保费
            "ac_overdue_money": "453.00",        实收逾期费
            "exhibition_rate": 0.12,              展期费率(展期申请信息)
            "yuqimoney": "213.00",              应收逾期费
            "yuqiday": 2                         逾期天数
            }
    "cate_info": [                           沟通记录
        {
        "id": 3,
        "initiate_time": "2018-08-25 15:45",      时间
        "node": "待部门经理审批",                 沟通发起节点
        "initiator": "刘传英",                   操作人员
        "content": "测试测试测试",               内容
        "communicationtype": "沟通：张三,李四"    沟通类型
        },
        {
        "initiate_time": "2018-08-25 15:45",     时间
        "node": "沟通回复",                      沟通发起节点
        "communicationtype": "回复:刘传英",       操作人员
        "content": [                             内容
            {
            "user_name": "张三",
            "content": "从随时随地",
            "reply_time": "2018-08-25 15:33"
            },
            {
            "user_name": "李四",
            "content": "水电费水电费是",
            "reply_time": "2018-08-25 15:33"
            }
        ],
        "initiator": "张三,李四"            操作人员
        },
    ]
    "linesInfo": {               额度(现金)赎楼信息
    "create_time": 1534935737,       银行放款时间(额度放尾款)  回款时间(现金放尾款)
    "loan_money": "2836000.00",      银行放款金额(额度放尾款)  实际回款金额(现金放尾款)
    "foreclosure": 0,                赎楼金额
    "ac_return_money": "0.00",       赎楼返还款
    "sum_return_money": null,        已退赎楼金额
    "back_floor": 2836000            可退赎楼金额
    },
    "canBackInfo": {           罚息信息
    "ac_default_interest": "0.00",    实收罚息金额
    "used_interest": 0,                   已用罚息金额
    "sum_default_interest": null,     已退罚息金额
    "can_back_money": 0               可退罚息金额
    },
    "shortLoan": {            短贷利息信息
    "ac_short_loan_interest": "0.00",      实收短贷利息
    "used_short_loan" :   "0"             已用短贷利息
    "sum_short_loan": null,                已退短贷利息金额
    "can_back_short_loan": 0               可退短贷利息金额
    }
        }
     *
     */

    public function costApplyDetail(){
        $id = $this->request->Post('id', null, 'int');
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空');

        $otherInfo = $this->orderother->where(['id' => $id])->field('order_sn,process_type,process_sn,stage')->find();
        if(empty($otherInfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '不存在此条费用申请信息!');

        try{
            $returnInfo = [];
            //获取订单基本信息
            $returnInfo['orderinfo'] = $this->orderother->getOrderInfo($otherInfo['process_type'],$otherInfo['order_sn']);
            if($otherInfo['process_type'] == 'EXHIBITION'){
                //查询是否需要展示沟通的按钮  1需要显示  2不需要显示
                //查询出所有复核要求的沟通id
                $communicateArr = $this->ordercommunicate->where(['sn' => $otherInfo['process_sn'], 'status' => 1])->column('id');
                if(empty($communicateArr)){
                    $returnInfo['orderinfo']['is_show_communication'] = 2;
                }

                $where['order_communicate_id'] = ['in',$communicateArr];
                $where['user_id'] = $this->userInfo['id'];
                $where['status'] = 1;
                $where['content'] = ['NULL',null];
                $erplyid = $this->ordercommunicatereply->where($where)->value('id');
                if(isset($erplyid)){
                    $returnInfo['orderinfo']['is_show_communication'] = 1;
                }else{
                    $returnInfo['orderinfo']['is_show_communication'] = 2;
                }

            }

            //是否显示处理审批
            $returnInfo['orderinfo']['is_show_approval'] = $this->orderother->isShowApproval($this->userInfo['id'],$otherInfo['stage'],$otherInfo['process_type']);

            //获取申请信息
            $returnInfo['applyforinfo'] = $this->orderother->getApplyInfo($id);

            //获取支付账户信息(退费账户信息)
            $returnInfo['accountinfo'] = $this->orderother->getAccountsInfo($id);

            if($otherInfo['process_type'] == 'SQ_TRANSFER'){
                //获取首期款信息
                $returnInfo['dpInfo'] = OrderComponents::orderDp($otherInfo['order_sn'], 'dp_strike_price,dp_earnest_money,dp_money,dp_supervise_bank,dp_supervise_bank_branch,dp_buy_way,dp_now_mortgage,dp_supervise_date');

                //现按揭信息
                $mortgageInfo = OrderComponents::showMortgage($otherInfo['order_sn'], 'type,mortgage_type,money,organization_type,organization','NOW');
                $newMortgageArr = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_TYPE'));
                $newAgencyArr = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_AGENCY_TYPE '));
                if (!empty($mortgageInfo)) {
                    foreach ($mortgageInfo as $k => $v){
                        $mortgageInfo[$k]['mortgage_type_str'] = $newMortgageArr[$v['mortgage_type']] ? $newMortgageArr[$v['mortgage_type']] : '';
                        $mortgageInfo[$k]['organization_type_str'] = $newAgencyArr[$v['organization_type']] ? $newAgencyArr[$v['organization_type']] : '';
                    }
                }
                $returnInfo['mortgage_info'] = $mortgageInfo;
            }

        if($otherInfo['process_type'] == 'DEPOSIT'){
            //获取房产信息
            $estateInfo = OrderComponents::showEstateList($otherInfo['order_sn'],'estate_name,estate_region,estate_area,estate_owner,estate_certtype,estate_certnum,house_type','DB');
            $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
            if($estateInfo){
                foreach($estateInfo as $k => $val){
                    $estateInfo[$k]['estate_certtype_str'] = $newStageArr[$val['estate_certtype']] ? $newStageArr[$val['estate_certtype']]:'';
                }
            }
            $returnInfo['estate_info'] = $estateInfo;
        }

        if($otherInfo['process_type'] == 'XJ_GUARANTEE_FEE'){
            //担保费信息
            $advanceInfo = OrderComponents::advanceMoney($otherInfo['order_sn']);
            $returnInfo['advance_info'] = $advanceInfo;
        }

        if($otherInfo['process_type'] == 'EXHIBITION'){
            //出账回款信息
            $outbackinfo = FinancialBack::outBackinfo($otherInfo['order_sn']);
            if($outbackinfo === false) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '该订单存在问题!');
            $returnInfo['outbackinfo'] = $outbackinfo;

            //费用信息
            $costInfo = $this->orderother->costInformation($otherInfo['order_sn'],$outbackinfo['expectday']);
            $returnInfo['cost_info'] = $costInfo;

            //查询出沟通记录
            $costInfo = $this->orderother->getCommunicate($otherInfo['process_sn']);
            $returnInfo['cate_info'] = $costInfo;

        }

            if($otherInfo['process_type'] == 'ED_TAIL'){
                //获取新房产信息
                /*$estateInfo = OrderComponents::showEstateList($order_sn,'estate_name,estate_region,estate_area,estate_owner,estate_certtype,estate_certnum,house_type','DB');
                $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
                if($estateInfo){
                    foreach($estateInfo as $k => $val){
                        $estateInfo[$k]['estate_certtype_str'] = $newStageArr[$val['estate_certtype']] ? $newStageArr[$val['estate_certtype']]:'';
                    }
                }
                $returnInfo['estate_info'] = $estateInfo;*/

                //额度类订单放尾款信息
                $resultInfo = $this->orderother->showTailSection($otherInfo['order_sn'], $id);
                //额度赎楼信息
                $returnInfo['linesInfo'] = $resultInfo['linesInfo'];
                //罚息信息
                $returnInfo['canBackInfo'] = $resultInfo['canBackInfo'];
                //短贷利息信息
                $returnInfo['shortLoan'] = $resultInfo['shortLoan'];

            }

            if($otherInfo['process_type'] == 'XJ_TAIL'){
                //获取新房产信息
                /*$estateInfo = OrderComponents::showEstateList($order_sn,'estate_name,estate_region,estate_area,estate_owner,estate_certtype,estate_certnum,house_type','DB');
                $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
                if($estateInfo){
                    foreach($estateInfo as $k => $val){
                        $estateInfo[$k]['estate_certtype_str'] = $newStageArr[$val['estate_certtype']] ? $newStageArr[$val['estate_certtype']]:'';
                    }
                }
                $returnInfo['estate_info'] = $estateInfo;*/

                //现金类订单放尾款信息
                $resultInfo = $this->orderother->showRemainingCash($otherInfo['order_sn'], $id);
                //现金赎楼信息
                $returnInfo['linesInfo'] = $resultInfo['linesInfo'];
                //罚息信息
                $returnInfo['canBackInfo'] = $resultInfo['canBackInfo'];

            }

        //查询出审批记录
        if($otherInfo['process_type'] == 'XJ_TAIL'){
            $otherInfo['process_type'] = 'ED_TAIL';
        }
        $approvalInfo = $this->orderother->getApprovalRecords($otherInfo['order_sn'],$otherInfo['process_type'],$id);
        $returnInfo['approval_info'] = $approvalInfo;



            return $this->buildSuccess($returnInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '查询失败'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/costApprovalDetail 费用申请审批详情[admin/CostApply/costApprovalDetail]
     * @apiVersion 1.0.0
     * @apiName costApprovalDetail
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/costApprovalDetail
     *
     * @apiParam {int}  id   其他业务表主键id
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * "data": {
    "orderinfo": {                    订单基本信息
    "order_sn": "JYDB2018070002",       业务单号
    "finance_sn": "100000001",          财务编号
    "money": "7800000.00",              担保金额
    "order_source": 2,
    "source_info": "中原地产",           来源机构
    "name": "梁小健",                    理财经理
    "sname": "担保业务02部",             所属部门
    "estateinfo": "绿海湾花园A座1单元1601,绿海湾花园A座1单元1601a",    房产名称
    "order_source_str": "银行介绍",                 业务来源
    "costomerinfo": "杨丽娟,梁玉生,孙士钧,刘佩铃"       担保申请人
    "paymatters": "首期款转账",                        付款事项
    "estateOwner": "杨丽娟,杨丽娟",                    业主姓名
    "associated": "TMXJ2018070003,JYXJ2018070005,JYXJ2018070006"  关联订单
    "ac_deposit": "1.00",                      实收保证金
    "ac_guarantee_fee": "62400.00",            担保费(实收担保费)
    "ac_fee": "0.00",                           手续费
    "ac_exhibition_fee": "0.00",                展期费
    "ac_overdue_money": "0.00",                 逾期金额
    "guarantee_rate": 0.8,                     担保费率
    }
    "applyforinfo": {                   申请信息
    "id": 10,                               其他业务表主键id
    "process_sn": "201808150008",           流程编码
    "order_type": null,                     订单类型 1内单 2外单
    "loan_way": 1,                          放款方式 1转账
    "transfer_type": 1,                     到账类型 1实时 2普通
    "info_fee_rate": 0.55,                  信息费费率
    "info_fee": "10523.00",                 信息费金额
    "collector": "张三",                    信息费收取人
    "mobile": "18529113254",                联系电话
    "reason": "测试元原因",                 支付原因(申请原因)
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
    "accountinfo": [               支付账户(退费账户)信息
    {
    "id": 11,                     账户信息id
    "bank_account": "张三",       银行户名
    "account_type": 1,            账户类型 1业主 2客户 3收款确定书
    "account_source": 1,          账户来源 1合同 2财务确认书 3其他
    "bank_card": "4521368",       银行卡号
    "bank": "中国银行",            开户银行
    "bank_branch": "车公庙支行",   开户支行
    "money": "12458.00",           支付金额(信息费) 转账金额(首期款转账) 应退金额(退保证金) 应退担保金额(现金按天退) 退款金额(额度退担保费)
    "exhibition_fee": "12345.00",  应退展期费(现金按天退专有)
    "actual_payment": null,      实付金额(信息费) 实转金额(首期款转账) 实退金额(退保证金，现金按天退，额度退担保费)
    "expense_taxation": null     扣税费用(信息费)  手续费(首期款转账,退保证金，现金按天退，额度退担保费)
    },
    {
    "bank_account": "李四",
    "account_type": 2,
    "account_source": 3,
    "bank_card": "123445",
    "bank": "中国农业银行",
    "bank_branch": "车公庙支行",
    "money": "1456.00",
    "exhibition_fee": "23456.00",
    "actual_payment": null,
    "expense_taxation": null
    }
    ]

    "approval_info": [   审批记录
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

    public function costApprovalDetail(){
        $id = $this->request->Post('id', null, 'int');
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空');

        $otherInfo = $this->orderother->where(['id' => $id])->field('order_sn,process_type')->find();
        if(empty($otherInfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '不存在此条费用申请信息!');

        try{
            $returnInfo = [];
            //获取订单基本信息
            $returnInfo['orderinfo'] = $this->orderother->getOrderInfo($otherInfo['process_type'],$otherInfo['order_sn']);

            //获取申请信息
            $returnInfo['applyforinfo'] = $this->orderother->getApplyInfo($id);

            //获取支付账户信息(退费账户信息)
            $returnInfo['accountinfo'] = $this->orderother->getAccountsInfo($id);

            //查询出审批记录
            $approvalInfo = $this->orderother->getApprovalRecords($otherInfo['order_sn'], $otherInfo['process_type'],$id);
            $returnInfo['approval_info'] = $approvalInfo;

            return $this->buildSuccess($returnInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '银行账户更新失败'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/editCostApply 编辑费用申请[admin/CostApply/editCostApply]
     * @apiVersion 1.0.0
     * @apiName editCostApply
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/editCostApply
     *
     *
     * @apiParam {int}  id    其他业务表主键id
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  loan_way   放款方式 1转账
     * @apiParam {int}  transfer_type   到账类型 1实时 2普通
     * @apiParam {float}   info_fee_rate  信息费费率
     * @apiParam {int}  info_fee   信息费金额
     * @apiParam {string}  collector  信息费收取人
     * @apiParam {int}  mobile  联系电话
     * @apiParam {string}  reason  支付原因(申请原因)
     * @apiParam {arr}  attachment  附件材料[1,2,3]
     * @apiParam {int}  order_type  订单类型 1内单 2外单
     *
     * @apiParam {array} accountinfo  支付账户信息(具体参数在下面)
     * @apiParam {string}   bank_account  银行户名
     * @apiParam {string}  bank_card   银行卡号
     * @apiParam {string}  bank  开户银行
     * @apiParam {string}  bank_branch  开户支行
     * @apiParam {float}  money  信息费(支付金额) 首期款(转账金额) 保证金(应退金额) 按天退担保费(应退担保金额) 额度退担保费(退款金额)
     * @apiParam {int}  account_type  账户类型 1业主 2客户 3收款确定书
     * @apiParam {int}  account_source  账户来源 1合同 2财务确认书 3其他
     * @apiParam {float}  exhibition_fee  应退展期费(按天退担保费申请专有)
     */

    public function editCostApply(){
        $this->id = $this->request->post('id');
        $otherInfo = $this->orderother->where(['id' => $this->id, 'status' => 1])->field('process_type,order_sn')->find();
        if(empty($otherInfo)) return $this->buildFailed(ReturnCode::PARAM_INVALID, "不存在该费用申请信息!");

        $this->process_type = $this->Deliverynum($otherInfo['process_type']);
        $this->time = time();
        //校验申请信息
        $resAppinfo = $this->checkApplicationinfo();
        if ($resAppinfo !== true)
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $resAppinfo);

        //校验账户信息
        $resAccinfo = $this->checkAccountinfo();
        if ($resAccinfo !== true)
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $resAccinfo);

        $costInfo = $this ->checkData['costInfo'];
        Db::startTrans();
        try{
            unset($costInfo['order_sn']);
            unset($costInfo['process_type']);
            $costInfo['update_time'] = $this->time;
            $costInfo['money'] = $this->summoney;
            //更改申请信息
            if (($this->orderother->where(['id' => $this->id, 'stage' => 301])->update($costInfo)) <= 0) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '申请信息更新失败');
            }

            //更改支付账户信息
            $accountInfo = $this->updateAccount();
            if ($accountInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $accountInfo);
            }

            //更改附件
            $attachmentInfo = $this->updateAttachment();
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }

            //编辑订单流程初始化
            $resInitInfo = $this->editProcess($otherInfo['order_sn'], $otherInfo['process_type']);
            if ($resInitInfo['code'] == -1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
            }

            Db::commit();
            return $this->buildSuccess();
        }catch (\Exception $e){
            Db::rollback();
            trace('编辑费用申请错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '费用申请添加失败'.$e->getMessage());
        }
    }

    //编辑信息费申请初始化
    /* @Param {int}  id   订单表id
     * @Param {string}  $order_sn  订单号
     * */
    private function editProcess($order_sn, $process_type) {
        $workflow = new Workflow();
        if($process_type == 'XJ_TAIL'){
            $process_type = 'ED_TAIL';
        }
        $flow_id = $workflow->getFlowId($process_type);
        $entry_id = WorkflowEntry::where(['mid' => $this->id, 'order_sn' => $order_sn, 'status' => -1, 'flow_id' => $flow_id])->value('id');
        if (isset($entry_id) && !empty($entry_id)) {
            $workflow->resend($entry_id, $this->userInfo['id']);
        } else {
            return ['code' => -1, 'msg' => "编辑费用申请信息流程初始化获取flow_id失败"];
        }
    }

    //更改附件
    private function updateAttachment() {
        //将以前的附件全部删除
        Db::name('order_other_attachment')->where(['order_other_id' => $this->id])->delete();
        if($msg = $this->addAttachment() !== 1) return $msg;
        return 1;
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



    /**
     * 转换类型
     * @param $type
     * @return string
     */
    private function Deliverynum($processtype) {
        switch ($processtype) {
            case 'INFO_FEE' :
                return 1;
                break;
            case 'SQ_TRANSFER' :
                return 2;
                break;
            case 'DEPOSIT' :
                return 3;
                break;
            case 'XJ_GUARANTEE_FEE' :
                return 4;
                break;
            case 'ED_GUARANTEE_FEE' :
                return 5;
                break;
            case 'ED_TAIL' :
                return 6;
                break;
            case 'XJ_TAIL' :
                return 7;
                break;
            default:
                return '';
        }
    }


    /**
     * @api {post} admin/CostApply/costAuditList 信息费审核列表[admin/CostApply/costAuditList]
     * @apiVersion 1.0.0
     * @apiName costAuditList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/costAuditList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
    "process_sn": "201808150008",   流程单号
    "order_sn": "DQJK2018070004",   业务单号
    "finance_sn": "100000023",      财务编号
    "type": "DQJK",                 业务类型
    "estate_name": null,           房产名称
    "estate_owner": null,          业主姓名
    "money": "0.00",               支付金额
    "stage": "10001",
    "create_time": "2018-08-15",   申请时间
    "name": "管理员"                        申请人
    "type_text": "短期借款",                业务类型
    "stage_text": "待核算专员审批"          审批状态
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

    public function costAuditList(){
        $createUid = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
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
        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['wf.type']= 'INFO_FEE';
        $map['d.is_back'] = 0;
        $map['d.is_deleted'] = 1;
        $map['d.user_id']= $this->userInfo['id'];
        $map['d.status'] = ['in','0,9'];
        $map['x.process_type'] = 'INFO_FEE';
        $field = 'max(d.id) proc_id,x.id,x.process_sn,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::costList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/appFlowInfo 处理审批相关信息[admin/CostApply/appFlowInfo]
     * @apiVersion 1.0.0
     * @apiName appFlowInfo
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/appFlowInfo
     *
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  proc_id   处理明细表主键id
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *  "data": {
            "proc_id": "5970",                              处理明细表主键id
            "process_id": 163,                              流程步骤表主键id
            "process_name": "待核算专员审批",               节点名称(当前步骤名称,审批节点)
            "next_process_name": "待资金专员放款",          下一个审批节点名称
            "preprocess": [    退回节点下拉信息['id(int)'=>'退回节点id','entry_id（int）'=>'流程实例id','flow_id（int）'=>'工作流定义表id','process_id（int）'=>'流程步骤id','process_name（string）'=>'返回节点名称']
                    {
                    "id": 5965,
                    "entry_id": 467,
                    "flow_id": 17,
                    "process_id": 162,
                    "process_name": "带业务报单",
                    "create_time": "2018-08-17 10:21:41"
                    }
                ]
            }
     *  }
     */

    public function appFlowInfo(){
        $orderSn = input('order_sn');
        $proc_id = input('proc_id');
        if(empty($orderSn) || empty($proc_id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        // 获取当前流程相关信息当前流程信息、可退回节点、下一步审批人员、下一步流程节点、审批记录
        $config = [
            'user_id' => $this->userInfo['id'], // 用户id
            'user_name' => $this->userInfo['name'], // 用户姓名
            'proc_id' => $proc_id  // 当前步骤id
        ];
        $workflow = new Workflow($config);
        $resInfo = $workflow->workflowInfo();
        try{
            //查询出节点名称和节点id
            $resWork = WorkflowProc::getOne(['id' => $proc_id],'process_id,process_name');
            //组装数据
            $resProcess['proc_id'] = $proc_id;
            $resProcess['process_id'] = $resWork['process_id'];
            $resProcess['process_name'] = $resWork['process_name'];
            $resProcess['next_process_name'] = $resInfo['nextprocess']['process_name'];
            $resProcess['preprocess'] = $resInfo['preprocess'];
            return $this->buildSuccess($resProcess);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }

    }


    // @author 赵光帅
    /**
     * @api {post} admin/CostApply/subDealWith 提交审批[admin/CostApply/subDealWith]
     * @apiVersion 1.0.0
     * @apiName subDealWith
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/subDealWith
     *
     * @apiParam {int}  id   其他业务表主键id
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  proc_id   处理明细表主键id
     * @apiParam {int}  is_approval   审批结果 1通过 2驳回
     * @apiParam {string}  content   审批意见
     * @apiParam {int}  backtoback   是否退回之后直接返回本节点 1 返回 不返回就不需要传值
     * @apiParam {int}  back_proc_id   退回节点id
     */

    public function subDealWith(){
        $id = input('id');
        $orderSn = input('order_sn');
        $is_approval = input('is_approval');
        $proc_id = input('proc_id');
        $content = input('content');
        $backtoback = input('backtoback')?:'';
        $back_proc_id = input('back_proc_id');

        if(empty($proc_id) || empty($orderSn) || empty($is_approval)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        if($backtoback == 1 && empty($back_proc_id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '退回节点id不能为空!');

        $config = [
            'user_id' => $this->userInfo['id'], // 用户id
            'user_name' => $this->userInfo['name'], // 用户姓名
            'proc_id' => $proc_id,  // 当前步骤id
            'content' => $content,  // 审批意见
            'backtoback' => $backtoback,  //是否退回之后直接返回本节点
            'back_proc_id' => $back_proc_id,  // 退回节点id
            'order_sn' => $orderSn
        ];
        $workflow = new Workflow($config);
        // 启动事务
        Db::startTrans();
        try{
            if($is_approval == 1){
                // 审批通过 走审批流
                $workflow->pass();
                //添加展期合同生效期数
                if(isset($id) && !empty($id)){
                    $otherInfo = $this->orderother->where(['id' => $id, 'status' => 1])->field('process_type,stage')->find();
                    if(empty($otherInfo)){
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '该展期信息不存在!');
                    }

                    if($otherInfo['process_type'] == 'EXHIBITION' && $otherInfo['stage'] == 308){
                        $num = $this->orderother->where(['order_sn' => $orderSn, 'status' => 1, 'stage' => 308, 'process_type' => 'EXHIBITION'])->count();
                        $resInfo = $this->orderotherexhibition->where(['order_other_id' => $id])->update(['exhibition_effective_period' => $num]);
                        if($resInfo <= 0){
                            Db::rollback();
                            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '展期生效期数添加失败!');
                        }
                        //查询出该申请的展期信息
                        $exhibitionInfo = $this->orderotherexhibition->where(['order_other_id' => $id])->field('exhibition_rate,exhibition_starttime,exhibition_endtime')->find();
                        if(empty($exhibitionInfo)){
                            Db::rollback();
                            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '该展期信息不存在!');
                        }

                        //延后推送展期合同
                        $exhibInfo = $this->orderotherexhibition->ChangefeeByexhibotion($orderSn, $exhibitionInfo['exhibition_starttime'], $exhibitionInfo['exhibition_endtime'], $exhibitionInfo['exhibition_rate']);
                        if(!$exhibInfo){
                            Db::rollback();
                            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '延后推送展期合同失败!');
                        }

                    }
                }

            }else{
                // 审批拒绝
                $workflow->unpass();
            }
            // 提交事务
            Db::commit();
            return $this->buildSuccess('审批成功');
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::ADD_FAILED, $e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/getAccountDetail 信息费支付(确定退费)[admin/CostApply/getAccountDetail]
     * @apiVersion 1.0.0
     * @apiName getAccountDetail
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/getAccountDetail
     *
     * @apiParam {int}  id   其他业务表主键id
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * "data": {
    "accountinfo": [               账户信息
    {
    "id": 11,                     账户信息id
    "bank_account": "张三",       银行户名
    "account_type": 1,            账户类型 1业主 2客户 3收款确定书
    "account_source": 1,          账户来源 1合同 2财务确认书 3其他
    "bank_card": "4521368",       银行卡号
    "bank": "中国银行",            开户银行
    "bank_branch": "车公庙支行",   开户支行
    "money": "12458.00",           支付金额(信息费) 转账金额(首期款转账) 应退金额(退保证金) 应退担保金额(现金按天退) 退款金额(额度退担保费 放尾款申请)
    "exhibition_fee": "12345.00",  应退展期费(现金按天退专有)
    "actual_payment": null,      实付金额(信息费) 实转金额(首期款转账) 实退金额(退保证金，现金按天退，额度退担保费) 实退金额(放尾款申请)
    "expense_taxation": null     扣税费用(信息费)  手续费(首期款转账,退保证金，现金按天退，额度退担保费)  过账手续费(放尾款申请)
    },
    {
    "bank_account": "李四",
    "account_type": 2,
    "account_source": 3,
    "bank_card": "123445",
    "bank": "中国农业银行",
    "bank_branch": "车公庙支行",
    "money": "1456.00",
    "exhibition_fee": "23456.00",
    "actual_payment": null,
    "expense_taxation": null
    }
    ]

    }
     *
     */

    public function getAccountDetail(){
        $id = $this->request->Post('id', null, 'int');
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空');

        $process_type = $this->orderother->where(['id' => $id, 'status' => 1])->value('process_type');
        if(empty($process_type)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '不存在该条费用申请信息');

        try{
            //查询出到账类型
            $transferType = $this->orderother->where(['id' => $id])->value('transfer_type');
            //获取支付账户信息(退费账户信息)
            $res = Db::name('order_other_account')->where(['order_other_id' => $id,'status' => 1])->field('id,bank_account,account_type,account_source,bank_card,bank,bank_branch,money,exhibition_fee,actual_payment,expense_taxation')->select();
            if($process_type == 'ED_TAIL' || $process_type == 'XJ_TAIL' ){
                foreach ($res as $k => $v){
                    if(empty($v['actual_payment'])){
                        if($transferType == 1){  //实时到账
                            $resultInfo = $this->orderother->realBackMoney($v['money']);
                            $res[$k]['actual_payment'] = $resultInfo['actual_payment'];
                            $res[$k]['expense_taxation'] = $resultInfo['expense_taxation'];
                        }else{  //普通
                            $res[$k]['actual_payment'] = $v['money'];
                            $res[$k]['expense_taxation'] = 0;
                        }
                    }
                }
            }else{
                foreach ($res as $k => $v){
                    $res[$k]['money'] = $v['money'] + $v['exhibition_fee'];
                }
            }

            return $this->buildSuccess($res);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '银行账户更新失败'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/addSubmission 提交信息费支付(确定退费)[admin/CostApply/addSubmission]
     * @apiVersion 1.0.0
     * @apiName addSubmission
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/addSubmission
     *
     * @apiParam {array} accountinfo  账户信息(具体参数在下面)
     * @apiParam {int}  id   账户信息id
     * @apiParam {float}  actual_payment  实付金额(其他退费)  实退金额(放尾款申请)
     * @apiParam {float}  expense_taxation  扣税费用(其他退费)  过账手续费(放尾款申请)
     */

    public function addSubmission(){
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
        $summoneys = '';
        foreach ($accountData as $k => $v){
            $moneyinfo = $this->orderotheraccount->where(['id' => $v['id']])->field('money,exhibition_fee')->find();
            $moneyinfos = $moneyinfo['money'] + $moneyinfo['exhibition_fee'];
            $sumMoney = $v['actual_payment'] + $v['expense_taxation'];
            $summoneys += $sumMoney;
            if(in_array($this->Deliverynum($otherInfo['process_type']), [6,7])){
                if($moneyinfos != $sumMoney) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '存在账户实退金额加过账手续费不等于退款金额!');
            }else{
                if($moneyinfos != $sumMoney) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '存在账户实付金额加扣税金额不等于支付金额!');
            }

        }
        if($otherInfo['process_type'] == 'XJ_TAIL'){
            $otherInfo['process_type'] = 'ED_TAIL';
        }

        $procInfo = ProcService::getDispatchProcId($otherInfo['process_type'],$otherInfo['order_sn'],$otherInfo['id'],1,$this->userInfo['id']);
        $config = [
            'user_id' => $this->userInfo['id'], // 用户id
            'user_name' => $this->userInfo['name'], // 用户姓名
            'proc_id' => $procInfo['id'],  // 当前步骤id
            'order_sn' => $otherInfo['order_sn']
        ];

        // 启动事务
        Db::startTrans();
        try{
            //查询出财务序号
            $finance_sn = Db::name('order')->where(['order_sn' => $otherInfo['order_sn']])->value('finance_sn');
            if(empty($finance_sn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '不存在该条订单信息!');

            //组装数据
            $recond = [];
            $recond['order_sn'] = $otherInfo['order_sn'];
            $recond['finance_sn'] = $finance_sn;
            $recond['cost_time'] = date('Y-m-d', time());
            $recond['create_time'] = time();
            $recond['create_uid'] = $this->userInfo['id'];
            //退保证金
            if ($this->Deliverynum($otherInfo['process_type']) == 3){
                $recond['total_money'] = $recond['deposit'] = -1*$summoneys;
                $recond['remark'] = "退保证金,流程编号:".$otherInfo['process_sn'];
                //更新赎楼信息表实收保证金金额
                $teeInfo = OrderGuarantee::get(['order_sn' => $otherInfo['order_sn']]);
                $teeInfo->ac_deposit = $teeInfo['ac_deposit'] - $summoneys;
                //更新数据
                $res = $teeInfo->save();
                if(empty($res)){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '费用支付失败!');
                }
            }

            //退担保费
            if (in_array($this->Deliverynum($otherInfo['process_type']),[4,5])){
                $recond['total_money'] = $recond['guarantee_fee'] = -1*$summoneys;
                $recond['remark'] = "退担保费,流程编号:".$otherInfo['process_sn'];
                //更新赎楼信息表实收担保费金额
                $teeInfo = OrderGuarantee::get(['order_sn' => $otherInfo['order_sn']]);
                $teeInfo->ac_guarantee_fee = $teeInfo['ac_guarantee_fee'] - $summoneys;
                //更新数据
                $res = $teeInfo->save();
                if(empty($res)){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '费用支付失败!');
                }
            }

            //更新账户表
            $accInfo = $this->orderotheraccount->saveAll($accountData);
            if($accInfo <= 0){
                // 回滚事务
                Db::rollback();
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '费用支付失败!');
            }

            //添加费用入账记录
            if (in_array($this->Deliverynum($otherInfo['process_type']),[3,4,5])){
                $recInfo = Db::name('order_cost_record')->insert($recond);
                if($recInfo !== 1){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '流水记录添加失败!');
                }
            }

            //首期款退费添加操作记录
            if ($this->Deliverynum($otherInfo['process_type']) == 2){
                $result = $this->addSqkRecord($otherInfo['order_sn'],$summoneys,$finance_sn,$otherInfo['id'],1);
                if($result !== 1){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '添加出账记录失败!');
                }

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
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '银行账户更新失败'.$e->getMessage());
        }
    }

    /*
     * 首期款退费添加记录
     * @param string $order_sn  订单编号
     * @param int $summoneys  累计退费金额
     * @param int $finance_sn  财务编号
     * @param int $id  其他业务表id
     * @param int $type  1 首期款退费  2首期款退费驳回
     * */
    protected function addSqkRecord($order_sn,$summoneys,$finance_sn,$id,$type){
        //查询出公司资金出账总金额
        $sumoutMoneyTotal = Db::name('order_cost_detail')->where(['order_sn' => $order_sn, 'status' => 1, 'type' => 1])->sum('money');
        //查询出已收回款金额总计
        $sumReturn = Db::name('order_ransom_return')->where(['order_sn' => $order_sn, 'status' => 1, 'return_money_into_status' => ['in','2,3']])->sum('money');
        //组装数据
        $detailInfo = [];
        $detailInfo['finance_sn'] = $finance_sn;
        $detailInfo['order_sn'] = $order_sn;
        $detailInfo['type'] = 1;
        $detailInfo['item'] = '首期款转账';
        $detailInfo['money'] = $summoneys;
        $detailInfo['cost_date'] = date('Y-m-d',time());
        $detailInfo['return_money_already'] = $sumReturn;  //已收回款总额
        $detailInfo['tablename'] = 'bs_order_other';
        $detailInfo['tableid'] = $id;
        if($type == 1){
            $detailInfo['out_money_total'] = $sumoutMoneyTotal + $summoneys; //累计出账总额
            $detailInfo['return_money_wait'] = $detailInfo['out_money_total'] - $detailInfo['return_money_already']; //代收回款总额
            $detailInfo['status'] = 1;
            $detailInfo['statustext'] = '财务已出账';
        }else{
            $detailInfo['out_money_total'] = $sumoutMoneyTotal - $summoneys;  //累计出账总额
            $detailInfo['return_money_wait'] = $detailInfo['out_money_total'] + $detailInfo['return_money_already']; //代收回款总额
            $detailInfo['status'] = 2;
            $detailInfo['statustext'] = '出账已退回';

            //将前一条回款入账记录状态修改为驳回
            if(Db::name('order_cost_detail')->where(['order_sn' => $order_sn, 'tablename' => 'bs_order_other', 'tableid' => $id])->update(['status' => 2]) <= 0){
                // 回滚事务
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '修改状态失败!');
            }
        }

        //添加记录
        if(Db::name('order_cost_detail')->insert($detailInfo) <= 0) return '添加退费记录失败!';

        //修改累计出账金额
        if(Db::name('order_guarantee')->where(['order_sn' => $order_sn, 'status' => 1])->update(['out_account_com_total' => $detailInfo['out_money_total'], 'update_time' => time()]) <= 0) return '修改累计出账金额失败!';

        return 1;
    }


    /**
     * @api {post} admin/CostApply/otherRefundList 其他退费申请列表[admin/CostApply/otherRefundList]
     * @apiVersion 1.0.0
     * @apiName otherRefundList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/otherRefundList
     *
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
    "type": "DQJK",
    "estate_name": null,           房产名称
    "estate_owner": null,          业主姓名
    "money": "0.00",               付款金额
    "stage": "10001",
    "create_time": "2018-08-15",   申请时间
    "name": "管理员"                        理财经理
    "process_type_text": "额度单退担保费"    付款事项
    "stage_text": "待部门经理审批"           审批状态
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

    public function otherRefundList(){
        $managerId = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
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
        $searchText && $map['x.order_sn|x.process_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['x.process_type'] = ['in','SQ_TRANSFER,DEPOSIT,XJ_GUARANTEE_FEE,ED_GUARANTEE_FEE'];
        //var_dump($map);exit;
        $field = 'x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::otherRefundList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/otherApprovalList 其他退费审核列表[admin/CostApply/otherApprovalList]
     * @apiVersion 1.0.0
     * @apiName otherApprovalList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/otherApprovalList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  stage_code   审批状态
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} other_type    区分从哪个入口调用该接口 1 其它退费审批列表 2其他退费管理列表
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
    "process_sn": "201808150008",   流程单号
    "order_sn": "DQJK2018070004",   业务单号
    "finance_sn": "100000023",      财务编号
    "type": "DQJK",
    "estate_name": null,           房产名称
    "estate_owner": null,          业主姓名
    "money": "0.00",               支付金额
    "stage": "10001",
    "create_time": "2018-08-15",   申请时间
    "name": "管理员"                  理财经理
    "process_type_text": "现金单退担保费",          付款事项
    "stage_text": "待核算专员审批"                  审批状态
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

    public function otherApprovalList(){
        $createUid = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
        $startTime = strtotime($this->request->post('start_time'));
        $endTime = strtotime($this->request->post('end_time'));
        $stage = $this->request->post('stage_code','','int');
        $searchText = $this->request->post('search_text','','trim');
        $otherType = $this->request->post('other_type','','int');
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
        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];
        if(in_array('hk_staff',get_user_sing($this->userInfo['id']))){  //是回款专员
            if($otherType == 1){  //审批列表
                $map['x.process_type'] = ['in','DEPOSIT,XJ_GUARANTEE_FEE,ED_GUARANTEE_FEE'];
                $map['wf.type']= ['in','DEPOSIT,XJ_GUARANTEE_FEE,ED_GUARANTEE_FEE'];
            }else{  //支付列表
                $map['x.process_type'] = ['in','SQ_TRANSFER,DEPOSIT,ED_GUARANTEE_FEE'];
                $map['wf.type']= ['in','SQ_TRANSFER,DEPOSIT,ED_GUARANTEE_FEE'];
            }
        }else{  //不是回款专员
            $map['x.process_type'] = ['in','SQ_TRANSFER,DEPOSIT,XJ_GUARANTEE_FEE,ED_GUARANTEE_FEE'];
            $map['wf.type']= ['in','SQ_TRANSFER,DEPOSIT,XJ_GUARANTEE_FEE,ED_GUARANTEE_FEE'];
        }

        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['d.is_back'] = 0;
        $map['d.is_deleted'] = 1;
        $map['d.user_id']= $this->userInfo['id'];
        $map['d.status'] = ['in','0,9'];
        $field = 'max(d.id) proc_id,x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::otherApprovalList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/subRejected  费用支付完驳回[admin/CostApply/subRejected]
     * @apiVersion 1.0.0
     * @apiName subRejected
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/subRejected
     *
     * @apiParam {int}  id   其他业务表主键id
     * @apiParam {string}  content   驳回原因
     */

    public function subRejected(){
        $id = input('id');
        $content = input('content');

        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');

        $otherInfo = $this->orderother->where(['id' => $id, 'status' => 1])->field('process_type,order_sn,process_sn')->find();
        if($otherInfo['process_type'] == 'XJ_TAIL'){
            $otherInfo['process_type'] = 'ED_TAIL';
        }

        $flowid = Db::name('workflow_flow')->where(['type' => $otherInfo['process_type'], 'status' => 1, 'is_publish' => 1])->value('id');
        $map['order_sn'] = $otherInfo['order_sn'];
        $map['flow_id'] = $flowid;
        $map['mid'] = $id;
        $entryid = Db::name('workflow_entry')->where($map)->value('id');
        $proc_id = ProcService::getFinishBackProcId($otherInfo['order_sn'],$flowid,$entryid,308);

        if($otherInfo['process_type'] == 'SQ_TRANSFER'){
            $back_proc_id = ProcService::getFinishBackProcId($otherInfo['order_sn'],$flowid,$entryid,303);
        }elseif($otherInfo['process_type'] == 'XJ_GUARANTEE_FEE'){
            $back_proc_id = ProcService::getFinishBackProcId($otherInfo['order_sn'],$flowid,$entryid,307);
        }else{
            $back_proc_id = ProcService::getFinishBackProcId($otherInfo['order_sn'],$flowid,$entryid,304);
        }

        $config = [
            'user_id' => $this->userInfo['id'], // 用户id
            'user_name' => $this->userInfo['name'], // 用户姓名
            'proc_id' => $proc_id,  // 当前步骤id
            'content' => $content,  // 审批意见
            'back_proc_id' => $back_proc_id,  // 退回节点id
            'order_sn' => $otherInfo['order_sn']
        ];
        $workflow = new Workflow($config);

        //查询出财务序号
        $finance_sn = Db::name('order')->where(['order_sn' => $otherInfo['order_sn']])->value('finance_sn');
        if(empty($finance_sn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '不存在该条订单信息!');

        //组装数据
        $recond = [];
        $recond['order_sn'] = $otherInfo['order_sn'];
        $recond['finance_sn'] = $finance_sn;
        $recond['cost_time'] = date('Y-m-d', time());
        $recond['create_time'] = time();
        $recond['create_uid'] = $this->userInfo['id'];

        if (in_array($this->Deliverynum($otherInfo['process_type']),[2,3,4,5])){
            $summoneys = $this->orderother->where(['id' => $id, 'status' => 1, 'delete_time' => null])->value('money');
        }

        //退保证金
        if ($this->Deliverynum($otherInfo['process_type']) == 3){
            $recond['total_money'] = $recond['deposit'] = $summoneys;
            $recond['remark'] = "退保证金,流程编号:".$otherInfo['process_sn'];
        }

        //退担保费
        if (in_array($this->Deliverynum($otherInfo['process_type']),[4,5])){
            $recond['total_money'] = $recond['guarantee_fee'] = $summoneys;
            $recond['remark'] = "退担保费,流程编号:".$otherInfo['process_sn'];
        }

        // 启动事务
        Db::startTrans();
        try{
            //修改该条数据的审批状态
            $res = ProcService::resetRackProcess($entryid);
            if($res <= 0){
                // 回滚事务
                Db::rollback();
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '修改审批状态失败!');
            }

            // 审批拒绝
            $workflow->unpass();

            //清空实退金额和手续费
            $resInfo = $this->orderotheraccount->where(['order_other_id' => $id, 'status' => 1, 'delete_time' => null])->update(['actual_payment' => null, 'expense_taxation' => null]);
            if($resInfo <= 0){
                // 回滚事务
                Db::rollback();
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '实退金额清空失败!');
            }

            //添加费用入账记录
            if (in_array($this->Deliverynum($otherInfo['process_type']),[3,4,5])){
                $recInfo = Db::name('order_cost_record')->insert($recond);
                if($recInfo !== 1){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '流水记录添加失败!');
                }
            }

            if ($this->Deliverynum($otherInfo['process_type']) == 3){
                //更新赎楼信息表实收保证金金额
                $teeInfo = OrderGuarantee::get(['order_sn' => $otherInfo['order_sn']]);
                $teeInfo->ac_deposit = $teeInfo['ac_deposit'] + $summoneys;
                //更新数据
                $res = $teeInfo->save();
                if(empty($res)){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '费用支付失败!');
                }
            }

            if (in_array($this->Deliverynum($otherInfo['process_type']),[4,5])){
                //更新赎楼信息表实收担保费金额
                $teeInfo = OrderGuarantee::get(['order_sn' => $otherInfo['order_sn']]);
                $teeInfo->ac_guarantee_fee = $teeInfo['ac_guarantee_fee'] + $summoneys;
                //更新数据
                $res = $teeInfo->save();
                if(empty($res)){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '费用支付失败!');
                }
            }


            //首期款退费添加操作记录
            if ($this->Deliverynum($otherInfo['process_type']) == 2){
                $result = $this->addSqkRecord($otherInfo['order_sn'],$summoneys,$finance_sn,$id,2);
                if($result !== 1){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '添加出账记录失败!');
                }

            }

            //添加操作日志
            $logInfo = $this->orderother->addSubLog($this->Deliverynum($otherInfo['process_type']), $this->userInfo,$otherInfo['order_sn'],2);
            if (empty($logInfo)) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '添加日志失败!');
            }

            // 提交事务
            Db::commit();
            return $this->buildSuccess('驳回成功');
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::ADD_FAILED, $e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/payDetailRejected  支付(退费)详情驳回[admin/CostApply/payDetailRejected]
     * @apiVersion 1.0.0
     * @apiName payDetailRejected
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/payDetailRejected
     *
     * @apiParam {int}  id   其他业务表主键id
     * @apiParam {string}  content   驳回原因
     */

    public function payDetailRejected(){
        $id = input('id');
        $content = input('content');

        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');

        $otherInfo = $this->orderother->where(['id' => $id, 'status' => 1])->field('process_type,order_sn')->find();
        $flowid = Db::name('workflow_flow')->where(['type' => $otherInfo['process_type'], 'status' => 1, 'is_publish' => 1])->value('id');
        $map['order_sn'] = $otherInfo['order_sn'];
        $map['flow_id'] = $flowid;
        $map['mid'] = $id;
        $entryid = Db::name('workflow_entry')->where($map)->value('id');
        if($otherInfo['process_type'] == 'XJ_TAIL'){
            $otherInfo['process_type'] = 'ED_TAIL';
        }

        $procInfo = ProcService::getApprovalPendInfo($otherInfo['process_type'],$otherInfo['order_sn'],$id,$this->userInfo['id']);

        if($otherInfo['process_type'] == 'SQ_TRANSFER'){
            $back_proc_id = ProcService::getFinishBackProcId($otherInfo['order_sn'],$flowid,$entryid,303);
        }elseif($otherInfo['process_type'] == 'XJ_GUARANTEE_FEE'){
            $back_proc_id = ProcService::getFinishBackProcId($otherInfo['order_sn'],$flowid,$entryid,307);
        }else{
            $back_proc_id = ProcService::getFinishBackProcId($otherInfo['order_sn'],$flowid,$entryid,304);
        }

        $config = [
            'user_id' => $this->userInfo['id'], // 用户id
            'user_name' => $this->userInfo['name'], // 用户姓名
            'proc_id' => $procInfo['id'],  // 当前步骤id
            'content' => $content,  // 审批意见
            'back_proc_id' => $back_proc_id,  // 退回节点id
            'order_sn' => $otherInfo['order_sn']
        ];
        $workflow = new Workflow($config);

        // 启动事务
        Db::startTrans();
        try{
            // 审批拒绝
            $workflow->unpass();
            // 提交事务
            Db::commit();
            return $this->buildSuccess('驳回成功');
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::ADD_FAILED, $e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/rollOverList 展期申请列表[admin/CostApply/rollOverList]
     * @apiVersion 1.0.0
     * @apiName rollOverList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/rollOverList
     *
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
    "return_money": "1125.00",     待回款金额
    "money": "4000.00",            实交展期费
    "exhibition_day": 2,           展期天数
    "exhibition_fee": "20000.00",  展期费用
    "stage": "302",
    "create_time": "2018-08-24",      申请时间
    "name": "刘志刚",                理财经理
    "stage_text": "待部门经理审批"   订单状态
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

    public function rollOverList(){
        $managerId = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
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
        $searchText && $map['x.order_sn|x.process_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['x.process_type'] = 'EXHIBITION';

        $field = 'x.id,x.process_sn,o.order_sn,o.finance_sn,o.type,y.estate_name,x.return_money,x.money,oe.exhibition_day,oe.exhibition_fee,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::rollList($map,$field,['list_rows'=>$pageSize,'page'=>$page]));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/addRenewal 添加展期申请[admin/CostApply/addRenewal]
     * @apiVersion 1.0.0
     * @apiName addRenewal
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/addRenewal
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  return_money   待回款金额
     * @apiParam {float}  exhibition_rate   展期费率
     * @apiParam {date}   exhibition_starttime  展期开始时间
     * @apiParam {date}  exhibition_endtime   展期结束时间
     * @apiParam {int}  exhibition_day  展期天数
     * @apiParam {float}  exhibition_fee  展期费用
     * @apiParam {float}  exhibition_guarantee_fee  担保费抵扣金额
     * @apiParam {float}  exhibition_info_fee  信息费抵扣金额
     * @apiParam {float}  total_money  应交金额
     * @apiParam {float}  money  实交金额
     * * @apiParam {string}  reason  申请原因
     * @apiParam {arr}  attachment  附件材料,附件id组成的数组[1,2,3]
     *
     */

    public function addRenewal(){
        //校验展期费申请信息
        $rolloverInfo = $this->checkRenew();
        if(!is_array($rolloverInfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $rolloverInfo);

        Db::startTrans();
        try{
            $costInfo['process_sn'] = $this->getProcesssn();
            if($costInfo['process_sn'] === false){
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '流程编号生成失败');
            }

            $costInfo['process_type'] = 'EXHIBITION';
            $costInfo['order_sn'] = $rolloverInfo['order_sn'];
            $costInfo['return_money'] = $rolloverInfo['return_money'];
            $costInfo['total_money'] = $rolloverInfo['total_money'];
            $costInfo['money'] = $rolloverInfo['money'];
            $costInfo['reason'] = isset($rolloverInfo['reason'])?$rolloverInfo['reason']:'';
            $costInfo['stage'] = 301; //初始状态
            $costInfo['create_uid'] = $this->userInfo['id'];
            $costInfo['create_time'] = $costInfo['update_time'] = time();
            $costInfo['category'] = Db::name('order')->where(['order_sn' => $rolloverInfo['order_sn']])->value('category');
            //添加申请信息
            if (($id = $this->orderother->insertGetId($costInfo)) === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '展期申请添加失败');
            }

            //添加展期信息
            $eshInfo['order_other_id'] = $id;
            $eshInfo['exhibition_rate'] = $rolloverInfo['exhibition_rate'];
            $eshInfo['exhibition_starttime'] = $rolloverInfo['exhibition_starttime'];
            $eshInfo['exhibition_endtime'] = $rolloverInfo['exhibition_endtime'];
            $eshInfo['exhibition_day'] = $rolloverInfo['exhibition_day'];
            $eshInfo['exhibition_fee'] = $rolloverInfo['exhibition_fee'];
            $eshInfo['exhibition_guarantee_fee'] = $rolloverInfo['exhibition_guarantee_fee'];
            $eshInfo['exhibition_info_fee'] = $rolloverInfo['exhibition_info_fee'];
            if (($this->orderotherexhibition->insert($eshInfo)) === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '展期申请添加失败');
            }

            //添加附件
            $attachmentInfo = $this->addRenevalAttachment($id);
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }

            //流程初始化
            $resInitInfo = $this->initRenewalProcess($costInfo['order_sn'],$id);
            if ($resInitInfo['code'] == -1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
            }

            //添加操作日志
            $logInfo = $this->orderother->addOperationLog(8,$this->userInfo,$costInfo['order_sn']);
            if (empty($logInfo)) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '添加日志失败!');
            }

            unset($rolloverInfo);
            Db::commit();
            return $this->buildSuccess();

        }catch (\Exception $e){
            Db::rollback();
            trace('展期费申请错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '展期申请添加失败'.$e->getMessage());
        }
    }

    /*
    * 流程初始化
    * */

    private function initRenewalProcess($order_sn,$id) {
        $workflow = new Workflow();
        $flow_id = $workflow->getFlowId('EXHIBITION');
        if (empty($flow_id))
            return (['code' => -1, 'msg' => "添加订单流程初始化获取flow_id失败"]);
        $params['flow_id'] = $flow_id;
        $params['user_id'] = $this->userInfo['id'];
        $params['order_sn'] = $order_sn;
        $params['mid'] = $id;
        $workflow->init($params);
    }

    //添加附件
    private function addRenevalAttachment($id) {
        $attach = $this->request->post('attachment/a');
        if(count($attach) > 6) return "附件上传仅限6张!";
        if (isset($attach) && !empty($attach)) {
            $attachArr = [];
            foreach ($attach as $key => $att) {
                $attachArr[$key]['order_other_id'] = $id;
                $attachArr[$key]['attachment_id'] = $att;
                $attachArr[$key]['create_time'] = time();
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
     * 校验展期费申请信息
     * */
    public function checkRenew(){
        $renewalInfo = $this->request->post('', null, 'trim');
        //验证器验证参数
        $valiDate = validate('RenewalValid');
        if(!$valiDate->check($renewalInfo)){
            return $valiDate->getError();
        }
        //根据订单编号查询出展期费率
        $renewalInfo['exhibition_rate'] = Db::name('order_advance_money')->where(['order_sn' => $renewalInfo['order_sn'], 'status' => 1])->value('advance_rate');
        return $renewalInfo;
    }

    /**
     * @api {post} admin/CostApply/renewalAppList 展期申请审批列表[admin/CostApply/renewalAppList]
     * @apiVersion 1.0.0
     * @apiName renewalAppList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/renewalAppList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
    "process_sn": "201808150008",   流程单号
    "order_sn": "DQJK2018070004",   业务单号
    "finance_sn": "100000023",      财务编号
    "estate_name": null,           房产名称
    "return_money": "1125.00",     待回款金额
    "money": "4000.00",            实交展期费
    "exhibition_day": 2,           展期天数
    "exhibition_fee": "20000.00",  展期费用
    "stage": "302",
    "create_time": "2018-08-24",      申请时间
    "name": "刘志刚",                理财经理
    "stage_text": "待部门经理审批"   审批状态
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

    public function renewalAppList(){
        $createUid = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
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
        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['wf.type']= 'EXHIBITION';
        $map['d.is_back'] = 0;
        $map['d.is_deleted'] = 1;
        $map['d.user_id']= $this->userInfo['id'];
        $map['d.status'] = ['in','0,9'];
        $map['x.process_type'] = 'EXHIBITION';
        $field = 'max(d.id) proc_id,x.id,x.process_sn,o.order_sn,o.finance_sn,o.type,y.estate_name,x.return_money,x.money,oe.exhibition_day,oe.exhibition_fee,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::renewalAppList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/editRenewal 编辑展期申请[admin/CostApply/editRenewal]
     * @apiVersion 1.0.0
     * @apiName editRenewal
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/editRenewal
     *
     * @apiParam {int}  id   其他业务表主键id
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  return_money   待回款金额
     * @apiParam {float}  exhibition_rate   展期费率
     * @apiParam {date}   exhibition_starttime  展期开始时间
     * @apiParam {date}  exhibition_endtime   展期结束时间
     * @apiParam {int}  exhibition_day  展期天数
     * @apiParam {float}  exhibition_fee  展期费用
     * @apiParam {float}  exhibition_guarantee_fee  担保费抵扣金额
     * @apiParam {float}  exhibition_info_fee  信息费抵扣金额
     * @apiParam {float}  total_money  应交金额
     * @apiParam {float}  money  实交金额
     * @apiParam {string}  reason  申请原因
     * @apiParam {arr}  attachment  附件材料,附件id组成的数组[1,2,3]
     *
     */

    public function editRenewal(){
        $this->id = $id = $this->request->post('id','','trim');
        $orderSn = $this->request->post('order_sn','','trim');
        $this->time = time();
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '其他业务id不能为空!');
        //校验展期费申请信息
        $rolloverInfo = $this->checkRenew();
        if(!is_array($rolloverInfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $rolloverInfo);

        Db::startTrans();
        try{
            $costInfo['return_money'] = $rolloverInfo['return_money'];
            $costInfo['total_money'] = $rolloverInfo['total_money'];
            $costInfo['money'] = $rolloverInfo['money'];
            $costInfo['reason'] = isset($rolloverInfo['reason'])?$rolloverInfo['reason']:'';
            $costInfo['update_time'] = time();
            //修改申请信息
            if (($this->orderother->save($costInfo,['id' => $id])) === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '修改失败');
            }

            //修改展期信息
            $eshInfo['exhibition_rate'] = $rolloverInfo['exhibition_rate'];
            $eshInfo['exhibition_starttime'] = $rolloverInfo['exhibition_starttime'];
            $eshInfo['exhibition_endtime'] = $rolloverInfo['exhibition_endtime'];
            $eshInfo['exhibition_day'] = $rolloverInfo['exhibition_day'];
            $eshInfo['exhibition_fee'] = $rolloverInfo['exhibition_fee'];
            $eshInfo['exhibition_guarantee_fee'] = $rolloverInfo['exhibition_guarantee_fee'];
            $eshInfo['exhibition_info_fee'] = $rolloverInfo['exhibition_info_fee'];
            if (($this->orderotherexhibition->save($eshInfo,['order_other_id' => $id])) === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '展期修改失败');
            }

            //修改附件
            $attachmentInfo = $this->updateAttachment();
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }

            //编辑订单流程初始化
             $resInitInfo = $this->editProcess($orderSn,'EXHIBITION');
             if ($resInitInfo['code'] == -1) {
                 Db::rollback();
                 return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
             }

            unset($rolloverInfo);
            Db::commit();
            return $this->buildSuccess();

        }catch (\Exception $e){
            Db::rollback();
            trace('展期费编辑错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '展期费编辑失败'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/initiateCommunica 发起沟通[admin/CostApply/initiateCommunica]
     * @apiVersion 1.0.0
     * @apiName initiateCommunica
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/initiateCommunica
     *
     * @apiParam {string}  type   沟通类别  ZQSQ(展期申请)
     * @apiParam {int}  id   其他业务表主键id
     * @apiParam {int}  proc_id   处理明细表主键id
     * @apiParam {arr}  user_id_arr   沟通对象,二维数组 [0 =>['user_id(int)'=>'用户id','user_name（string）'=>'用户名称']]
     * @apiParam {string}  content   沟通内容
     *
     */

    public function initiateCommunica(){
        $type = $this->request->post('type','','trim');
        $sid = $this->request->post('id','','int');
        $procId = $this->request->post('proc_id','','int');
        $userIdArr = $this->request->post('user_id_arr/a');
        $content = $this->request->post('content','','trim');
        if(empty($sid) || empty($type) || empty($procId) || empty($userIdArr) || empty($content)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        if(!is_array($userIdArr)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '沟通对象必须为数组!');
        if(!in_array($type,['ZQSQ'])) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '沟通类别有误!');

        Db::startTrans();
        try{
            $node = Db::name('workflow_proc')->where(['id' => $procId])->field('order_sn,process_name')->find();
            $process_sn = $this->orderother->where(['id' => $sid, 'status' => 1])->value('process_sn');

            if(empty($node) || empty($process_sn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!');

            $cateInfo['type'] = $type;
            $cateInfo['order_sn'] = $node['order_sn'];
            $cateInfo['sn'] = $process_sn;
            $cateInfo['node'] = $node['process_name'];
            $cateInfo['initiator'] = $this->userInfo['name'];
            $cateInfo['create_time'] = $cateInfo['update_time'] = $cateInfo['initiate_time'] = time();
            $cateInfo['content'] = $content;
            $cateInfo['create_uid'] = $this->userInfo['id'];
            $cateid = $this->ordercommunicate->insertGetId($cateInfo);
            if($cateid <= 0){
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '沟通信息添加失败!');
            }
            $this->id = $cateid;
            $replyInfo = array_map(function($v) {
                $v['order_communicate_id'] = $this->id;
                $v['create_uid'] = $this->userInfo['id'];
                $v['create_time'] = $v['update_time'] = time();
                return $v;
            }, $userIdArr);

            if ($this->ordercommunicatereply->saveAll($replyInfo) <= 0) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '添加沟通对象表失败!');
            }

            Db::commit();
            return $this->buildSuccess();

        }catch (\Exception $e){
            Db::rollback();
            trace('发起沟通失败', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '发起沟通失败'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/communicaReply 沟通回复[admin/CostApply/communicaReply]
     * @apiVersion 1.0.0
     * @apiName communicaReply
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/communicaReply
     *
     * @apiParam {int}  process_sn   流程编号
     * @apiParam {string}  content   回复内容
     *
     */

    public function communicaReply(){
        $processSn = $this->request->post('process_sn');
        $content = $this->request->post('content','','trim');
        if(empty($processSn) || empty($content)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');

        //查询出所有复核要求的沟通id
        $communicateArr = $this->ordercommunicate->where(['sn' => $processSn, 'status' => 1])->column('id');
        if(empty($communicateArr)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        try{
            $where['order_communicate_id'] = ['in',$communicateArr];
            $where['user_id'] = $this->userInfo['id'];
            $where['status'] = 1;
            $where['content'] = ['NULL',null];
            if($this->ordercommunicatereply->where($where)->update(['content' => $content, 'reply_time' => time(), 'update_time' => time()]) <= 0){
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '回复内容更新失败!');
            }

            return $this->buildSuccess();
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '沟通回复失败'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/communicationReplyList 沟通回复列表[admin/CostApply/communicationReplyList]
     * @apiVersion 1.0.0
     * @apiName communicationReplyList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/communicationReplyList
     *
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
    "process_sn": "201808150008",   流程编号
    "order_sn": "DQJK2018070004",   业务单号
    "finance_sn": "100000023",      财务编号
    "estate_name": null,           房产名称
    "stage": "302",
    "create_time": "2018-08-24",      申请时间
    "name": "刘志刚",                理财经理
    "reply_state": "待回复",         回复状态
    "application_type": "展期申请",  订单类型
    "stage_text": "待部门经理审批"   审批状态
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
     */

    public function communicationReplyList(){
        $managerId = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
        $startTime = strtotime($this->request->post('start_time'));
        $endTime = strtotime($this->request->post('end_time'));
        $stage = $this->request->post('stage_code','','int');
        $searchText = $this->request->post('search_text','','trim');
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
        //$userId = $this->userInfo['id'];

        $map = [];
        /*$userStr = SystemUser::getOrderPowerStr($userId, $this->userInfo['ranking'], $this->userInfo['deptid']);
        if ($userStr != 'super') {
            $map['o.financing_manager_id|x.create_uid'] = ['in', $userStr]; //理财经理或者提交人
        }*/
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
        $searchText && $map['x.order_sn|x.process_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['x.process_type'] = 'EXHIBITION';
        $map['ocr.user_id'] = $this->userInfo['id'];

        $field = 'x.id,x.process_sn,o.order_sn,o.finance_sn,x.process_type,y.estate_name,x.stage,x.create_time,z.name,ocr.content';
        try{
            return $this->buildSuccess(OrderOther::replyList($map,$field,['list_rows'=>$pageSize,'page'=>$page]));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/isCheckOrder 申请验证[admin/CostApply/isCheckOrder]
     * @apiVersion 1.0.0
     * @apiName isCheckOrder
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/isCheckOrder
     *
     *
     * @apiParam {string} order_sn   订单编号
     * @apiParam {int} process_type 添加类型 1信息费支付 2首期转账 3退保证金 4现金按天退担保费 5额度退担保费
     * 6额度类订单放尾款申请 7现金类订单放尾款申请 8展期申请 10(撤单退担保费) 11(撤单不退担保费) 12(撤单保费调整)
     *
     */

    public function isCheckOrder(){
        $order_sn = $this->request->post('order_sn','','trim');
        $process_type = $this->request->post('process_type','','trim');
        $map['order_sn'] = $order_sn;
        $map['status'] = 1;
        $orderinfo = Db::name('order')->where($map)->field('id,type')->find();
        if(in_array($process_type, [1,2,3,4,5])){
            if($orderinfo['type'] == 'JYDB' && $process_type == 4) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '额度单不能建现金单退担保费的申请!');
            if($orderinfo['type'] != 'JYDB' && $process_type == 5) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '现金单不能建额度单退担保费的申请!');
        }

        if(empty($orderinfo)){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '不存在该订单!');
        }else{
            return $this->buildSuccess('验证通过');
        }

    }

    /**
     * @api {post} admin/CostApply/tailSectionList 放尾款申请列表[admin/CostApply/tailSectionList]
     * @apiVersion 1.0.0
     * @apiName tailSectionList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/tailSectionList
     *
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
    "type": "DQJK",
    "estate_name": null,           房产名称
    "estate_owner": null,          业主姓名
    "money": "0.00",               退款金额
    "stage": "10001",
    "create_time": "2018-08-15",   申请时间
    "name": "管理员"                        理财经理
    "stage_text": "待部门经理审批"           审批状态
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

    public function tailSectionList(){
        $managerId = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
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
        $searchText && $map['x.order_sn|x.process_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.delete_time'] = null;
        $map['x.process_type'] = ['in','ED_TAIL,XJ_TAIL'];
        //var_dump($map);exit;
        $field = 'x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::tailList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CostApply/balanceApprovalList 放尾款审批列表[admin/CostApply/balanceApprovalList]
     * @apiVersion 1.0.0
     * @apiName balanceApprovalList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/balanceApprovalList
     *
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
    "type": "DQJK",
    "estate_name": null,           房产名称
    "estate_owner": null,          业主姓名
    "money": "0.00",               退款金额
    "stage": "10001",
    "create_time": "2018-08-15",   申请时间
    "name": "管理员"                        理财经理
    "stage_text": "待部门经理审批"           审批状态
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

    public function balanceApprovalList(){
        $createUid = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
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
        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.process_type'] = ['in','ED_TAIL,XJ_TAIL'];
        $map['x.delete_time'] = null;
        $map['wf.type']= ['in','ED_TAIL,XJ_TAIL'];
        $map['d.is_back'] = 0;
        $map['d.is_deleted'] = 1;
        $map['d.user_id']= $this->userInfo['id'];
        $map['d.status'] = ['in','0,9'];
        $field = 'max(d.id) proc_id,x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::balanceApprovalList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/balancemanagementList 放尾款管理列表[admin/CostApply/balancemanagementList]
     * @apiVersion 1.0.0
     * @apiName balancemanagementList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/balancemanagementList
     *
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
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
    "type": "DQJK",
    "estate_name": null,           房产名称
    "estate_owner": null,          业主姓名
    "money": "0.00",               退款金额
    "stage": "10001",
    "create_time": "2018-08-15",   申请时间
    "name": "管理员"                        理财经理
    "stage_text": "待部门经理审批"           审批状态
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

    public function balancemanagementList(){
        $createUid = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
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
        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];

        $map['x.status'] = 1;
        $map['x.process_type'] = ['in','ED_TAIL,XJ_TAIL'];
        $map['x.delete_time'] = null;
        $map['wf.type']= ['in','ED_TAIL,XJ_TAIL'];
        $map['d.is_back'] = 0;
        $map['d.is_deleted'] = 1;
        $map['d.user_id']= $this->userInfo['id'];
        $map['d.status'] = ['in','0,9'];
        $field = 'max(d.id) proc_id,x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name';
        try{
            return $this->buildSuccess(OrderOther::balanceApprovalList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }

    /**
     * @api {post} admin/CostApply/importantMatterList 要事审批列表[admin/CostApply/importantMatterList]
     * @apiVersion 1.0.0
     * @apiName importantMatterList
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/importantMatterList
     *
     * @apiParam {int} create_uid    理财经理id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {string}  start_time   开始时间
     * @apiParam {string}  end_time   结束时间
     * @apiParam {string} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     */
    public function importantMatterList(){
        $createUid = $this->request->post('create_uid',0,'int');
        $subordinates = $this->request->post('subordinates',0,'int');
        $startTime = strtotime($this->request->post('start_time'));
        $endTime = strtotime($this->request->post('end_time'));
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

        $searchText && $map['x.order_sn|y.estate_name'] = ['like', "%{$searchText}%"];
        $map['x.status'] = 1;
        $map['x.process_type'] = 'IMPORT_ORDER_ITEM';//IMPORTANT_MATTER
        $map['x.delete_time'] = null;
        //$map['wf.type']= '';
        $map['d.is_back'] = 0;
        $map['d.is_deleted'] = 1;
        $map['d.user_id']= $this->userInfo['id'];
        $map['d.status'] = ['in','0,9'];
        $field = 'max(d.id) proc_id,x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name,z.mobile,z.deptname,o.dept_manager_id,x.reason,d.process_name';
        //return json(OrderOther::importantMatlList($map,$field,$page,$pageSize));
        try{
            return $this->buildSuccess(OrderOther::importantMatlList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }
    }   

    /**
     * @api {post} admin/CostApply/addImportantMatter 添加要事审批申请[admin/CostApply/addImportantMatter]
     * @apiVersion 1.0.0
     * @apiName addImportantMatter
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/addImportantMatter
     * 
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {string}  reason  重要说明
     * @apiParam {arr}  attachment  附件材料[1,2,3]
     * @apiParam {int}  next_user_id  下一个审批人
     */
    public function addImportantMatter(){
        $orderSn = input('order_sn', null, 'trim');
        $reason = input('reason', null, 'trim');
        $next_user_id=input('next_user_id',0);
        $userId=$this->userInfo['id'];
        $costInfo = $this ->checkData['costInfo'];
        Db::startTrans();
        try{
            $costInfo['process_sn'] = $this->getProcesssn();
            if($costInfo['process_sn'] === false){
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '流程编号生成失败');
            }
            $costInfo['order_sn']=$orderSn;
            $costInfo['process_type']='IMPORT_ORDER_ITEM'; //流程类型 IMPORTANT_MATTER 要事审批申请
            $costInfo['money'] = $this->summoney;  //初始支付金额
            $costInfo['reason'] = $reason;//重要说明
            $costInfo['stage'] = 301; //初始状态
            //$dept=Db::name('system_user')->where(['id'=>$next_user_id])->find();
            //$costInfo['deptid']=$dept['deptid'];
            $costInfo['create_uid'] = $userId;
            $costInfo['create_time'] = $costInfo['update_time'] = time();
            //查询出订单的分类
            $costInfo['category'] = Db::name('order')->where(['order_sn' => $orderSn])->value('category');

            //添加申请信息
            if (($this->id = $this->orderother->insertGetId($costInfo)) === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '要事审批申请添加失败');
            }

            //添加附件
            $attachmentInfo = $this->addAttachment();
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }
            //流程初始化
            $resInitInfo = $this->importantMatProcess($costInfo['order_sn'],$this->id,$next_user_id);
            //print_r($resInitInfo);exit;
            if ($resInitInfo['code'] == -1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
            }

            //添加操作日志
            $logInfo = $this->orderother->addOperationLog(9,$this->userInfo,$orderSn);
            if (empty($logInfo)) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '添加日志失败!');
            }

            Db::commit();
            return $this->buildSuccess();

        }catch (\Exception $e){
            Db::rollback();
            trace('要事审批错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '要事审批申请添加失败'.$e->getMessage());
        }           
    }

    /*
    * 流程初始化
    *
    */

    private function importantMatProcess($order_sn,$id,$userId) {
        $workflow = new Workflow();
        $flow_id = $workflow->getFlowId('IMPORT_ORDER_ITEM');//IMPORTANT_MATTER
        if (empty($flow_id))
            return (['code' => -1, 'msg' => "添加订单流程初始化获取flow_id失败"]);
        $params['flow_id'] = $flow_id;
        $params['user_id'] = $this->userInfo['id'];
        $params['order_sn'] = $order_sn;
        $params['mid'] = $id;
        $params['next_user_id']=$userId;
        $workflow->initFreeWorkflow($params);
    }


    /**
     * @api {post} admin/CostApply/importantMatterRecords 要事审批页面信息[admin/CostApply/importantMatterRecords]
     * @apiVersion 1.0.0
     * @apiName importantMatterRecords
     * @apiGroup CostApply
     * @apiSampleRequest admin/Approval/importantMatterRecords
     *
     * @apiParam {string}  order_sn   订单编号
     *
     * @apiSuccess {string} order_sn   业务单号
     * @apiSuccess {string} finance_sn 业务单号
     * @apiSuccess {string} estate_name房产名称
     * @apiSuccess {string} name       理财经理
     * @apiSuccess {string} mobile    联系电话
     * @apiSuccess {string} deptname  所属部门
     * @apiSuccess {string} reason      重要说明
     * @apiSuccess {array} attachment   附件材料
     * @apiSuccess {int}  user_id       下一个审批人id
     * @apiSuccess {string} user_name   审批人名称
     * @apiSuccess {array} approval_info 审批记录
     */
    public function importantMatterRecords(){
        $orderSn = $this->request->post('order_sn');
        if(empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        $resInfo = OrderOther::importantMat(['x.order_sn'=>$orderSn]);
        try{
            //查询出审批记录
            $jlField = 'wp.order_sn,wp.create_time,wp.process_name,wp.auditor_name,wp.auditor_dept,wp.status_desc status,wp.content';
            //查询出审批记录
            $appMap['wp.order_sn'] = $orderSn;
            $appMap['wp.is_deleted'] = 1;
            $appMap['wp.status'] = ['in','-1,9'];
            $appMap['wf.type'] = get_approval_logo(substr($orderSn,0,4));
            $jlList = Db::name('workflow_proc')->alias('wp')
                ->join('workflow_flow wf', 'wp.flow_id = wf.id')
                ->where($appMap)
                ->field($jlField)
                ->select();
            foreach ($jlList as $k => $v){
                $jlList[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
            }
            $arrsInfo['approval_records'] = $jlList;

            //查询出其他信息
            $qtField = 'id,order_sn,process_name,item';
            $qtList =TrialProcess::getAll(['order_sn' => $orderSn],$qtField);
            //查询组合好的其他信息
            $zhInfo = TrialProcess::show_Other_Information($qtList);
            $arrsInfo['other_information'] = $zhInfo;
            return $this->buildSuccess(['orderInfo' => $resInfo,'approval_info'=>$arrsInfo]);

        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /**
     * @api {post} admin/CostApply/editImportantMatter 要事审批编辑页面[admin/CostApply/editImportantMatter]
     * @apiVersion 1.0.0
     * @apiName editImportantMatter
     * @apiGroup CostApply
     * @apiSampleRequest admin/Approval/editImportantMatter
     *
     * @apiParam {string}  order_sn   订单编号
     *
     * @apiSuccess {string} order_sn    业务单号
     * @apiSuccess {string} finance_sn  业务单号
     * @apiSuccess {string} estate_name 房产名称
     * @apiSuccess {string} process_sn  流程编号
     * @apiSuccess {string} name        理财经理
     * @apiSuccess {string} mobile      联系电话
     * @apiSuccess {string} deptname    所属部门
     * @apiSuccess {string} reason      重要说明
     * @apiSuccess {array} attachment   附件材料
     * @apiSuccess {int}  user_id       下一个审批人id
     * @apiSuccess {string} user_name   审批人名称
     */
    public function editImportantMatter(){
        $orderSn = $this->request->post('order_sn');
        if(empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        $resInfo = OrderOther::editImportantMat(['o.order_sn'=>$orderSn]);
        try{
            return $this->buildSuccess(['orderInfo' => $resInfo]);

        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /**
     * @api {post} admin/CostApply/exitImportantMatter 修改要事审批申请[admin/CostApply/exitImportantMatter]
     * @apiVersion 1.0.0
     * @apiName exitImportantMatter
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/exitImportantMatter
     * 
     * @apiParam {string}  id   要事审批id
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {string}  reason  重要说明
     * @apiParam {arr}  attachment  附件材料[1,2,3]
     * @apiParam {int}  next_user_id  下一个审批人
     */
    public function exitImportantMatter(){
        $orderSn = input('order_sn', null, 'trim');
        $reason = input('reason', null, 'trim');
        $next_user_id=input('next_user_id',0);
        $id=input('id',0);
        $userId=$this->userInfo['id'];
        $costInfo = $this ->checkData['costInfo'];

        Db::startTrans();
        try{
            $costInfo['process_sn'] = $this->getProcesssn();
            if($costInfo['process_sn'] === false){
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '流程编号生成失败');
            }
            $costInfo['reason'] = $reason;//重要说明
            unset($costInfo['order_sn']);
            unset($costInfo['process_type']);
            $costInfo['update_time'] = $this->time;
            $costInfo['money'] = $this->summoney;    
            //更改申请信息
            if (($this->orderother->where(['id' => $id, 'stage' => 301])->update($costInfo)) <= 0) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '申请信息更新失败');
            }
            //修改附件
            $attachmentInfo = $this->updateAttachment();
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }
            
            //编辑后提交初始化流程
            $resInitInfo = $this->importantMatProcess($costInfo['order_sn'],$this->id);
            $workflow = new Workflow();
            $flow_id = $workflow->getFlowId('IMPORT_ORDER_ITEM');
            $entry = WorkflowEntry::where(['mid' => $id, 'order_sn' => $order_sn, 'status' => -1, 'flow_id' => $flow_id])->findOrFail();
            $workflow->freeResend($entry->id);

            if ($resInitInfo['code'] == -1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
            }

            //添加操作日志
            $logInfo = $this->orderother->addOperationLog(9,$this->userInfo,$orderSn);
            if (empty($logInfo)) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '添加日志失败!');
            }

            Db::commit();
            return $this->buildSuccess();

        }catch (\Exception $e){
            Db::rollback();
            trace('要事审批错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '要事审批申请添加失败'.$e->getMessage());
        }           
    }



    /**
     * @api {post} admin/CostApply/importantMatSubDealWith 要事审批提交审批[admin/CostApply/importantMatSubDealWith]
     * @apiVersion 1.0.0
     * @apiName importantMatSubDealWith
     * @apiGroup CostApply
     * @apiSampleRequest admin/CostApply/importantMatSubDealWith
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  proc_id   处理明细表主键id
     * @apiParam {string}  content   审批意见
     * @apiParam {int}  stage   订单状态
     * @apiParam {int}  is_approval   审批结果 1通过 2驳回 3同意并结束流程
     * @apiParam {int}  next_user_id   下一步审批人员id
     * @apiParam {int}  backtoback   是否退回之后直接返回本节点 1 返回 不返回就不需要传值
     * @apiParam {int}  back_proc_id   退回节点id
     * @apiParam {int} process_id    流程步骤表主键id
     * @apiParam {string} process_name    节点名称(当前步骤名称,审批节点)
     * @apiParam {string}  next_process_name   流向的审批节点名称
     * @apiParam {int} is_next_user    是否需要选择审查人员 0不需要 1需要
     */

    public function importantMatSubDealWith(){
        $orderSn = input('order_sn');
        $proc_id = input('proc_id');
        $content = input('content');
        $stage = input('stage');
        $is_approval = input('is_approval');
        $next_user_id = input('next_user_id');
        $backtoback = input('backtoback')?:'';
        $back_proc_id = input('back_proc_id');    //日志表下一步操作节点code
        $process_id = input('process_id');
        $process_name = input('process_name');
        $next_process_name = input('next_process_name');
        $is_next_user = input('is_next_user');

        if(empty($proc_id) || empty($orderSn) || empty($is_approval)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        if($backtoback == 1 && empty($back_proc_id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '退回节点id不能为空!');

        $config = [
            'user_id' => $this->userInfo['id'], // 用户id
            'user_name' => $this->userInfo['name'], // 用户姓名
            'proc_id' => $proc_id,  // 当前步骤id
            'content' => $content,  // 审批意见
            'next_user_id' => $next_user_id,  // 下一步审批人员
            'backtoback' => $backtoback,  //是否退回之后直接返回本节点
            'back_proc_id' => $back_proc_id,  // 退回节点id
            'order_sn' => $orderSn
        ];
        $operate = show_status_name($stage,'ORDER_JYDB_STATUS');   //上一步的操作节点名称
        $workflow = new Workflow($config);
        // 启动事务
        Db::startTrans();
        try{
            if($is_approval == 1){
                // 审批通过 走审批流
                $workflow->freePass();
                $ordeInfo = Db::name('order')->where(['order_sn' => $orderSn])->field('type,stage')->find();
                //更改资料入架，添加权证
                // $flow_id = $workflow->getFlowId([$ordeInfo['type'],'RISK']);
                // self::addAuthority($orderSn,$flow_id,$orderInfo['type'],$guaranteeInfo['is_dispatch'],$orderInfo);
                $operate_reason = '';  //原因 如驳回原因
                $msg = "审批通过,下一节点为：".$next_process_name;
                //根据流向的下一步节点名称查询出对应的code
                $back_proc_id = $ordeInfo['stage'];
            }elseif($is_approval == 2){
                // 审批拒绝
                $workflow->freeReject();
                $ordeInfo = Db::name('order')->where(['order_sn' => $orderSn])->field('type,stage')->find();
                $back_proc_id = $ordeInfo['stage'];
                $next_process_name = show_status_name($back_proc_id,'ORDER_JYDB_STATUS');
                $msg = "审批驳回,下一节点为".$next_process_name;
                $operate_reason = $content;  //原因 如驳回原因
            }else{
                //同意并结束
                $workflow->freeFinishedWorkflow();
                $ordeInfo = Db::name('order')->where(['order_sn' => $orderSn])->field('type,stage')->find();
                $back_proc_id = $ordeInfo['stage'];
                $next_process_name = show_status_name($back_proc_id,'ORDER_JYDB_STATUS');
                $msg = "订单完成";
                $operate_reason = $content;  //原因 如驳回原因
            }
            /*添加订单操作记录*/
            $stagestr = $next_process_name; //流向的下一步操作节点名称
            $operate_node = "要事审批";  //当前操作描述
            $operate_det = $msg;   //操作详情
            $operate_table = 'order_other';  //操作表
            $operate_table_id = ''; //操作表id
            OrderComponents::addOrderLog($this->userInfo,$orderSn, $stagestr, $operate_node,$operate,$operate_det,$operate_reason,$back_proc_id,$operate_table,$operate_table_id);
            // 提交事务
            Db::commit();
            return $this->buildSuccess('审批成功');
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::ADD_FAILED, $e->getMessage());
        }
    }





































}
