<?php

/* 银行出账控制器 */

namespace app\admin\controller;

use app\util\ReturnCode;
use app\model\OrderRansomOut;
use app\model\OrderRansomDispatch;
use app\model\Order;
use app\model\Message;
use app\model\SystemUser;
use app\util\OrderComponents;
use app\util\Tools;
use think\Db;

class BankAccount extends Base {

    private $orderransomout;
    private $orderransomdispatch;
    private $order;
    private $systemuser;
    private $message;

    public function _initialize() {
        parent::_initialize();
        $this->orderransomout = new OrderRansomOut();
        $this->orderransomdispatch = new OrderRansomDispatch();
        $this->order = new Order();
        $this->systemuser = new SystemUser();
        $this->message = new Message();
    }

    /**
     * @api {get} admin/BankAccount/checkList 支票出账列表[admin/BankAccount/checkList]
     * @apiVersion 1.0.0
     * @apiName checkList
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/checkList
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type     订单类型
     * @apiParam {int} account_status     出账状态（1待财务出账 2待财务复核 3待银行扣款 4出账已退回 5财务已出账）
     * @apiParam {int} keywords     关键词
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {string} finance_sn    财务序号
     * @apiSuccess {string} order_sn    业务单号
     * @apiSuccess {string} estate_name    房产名称
     * @apiSuccess {string} estate_owner    业主姓名
     * @apiSuccess {string} type_text    订单类型
     * @apiSuccess {string} account_status_text    出账状态
     * @apiSuccess {string} money    出账金额
     * @apiSuccess {string} guarantee_fee    担保金额
     * @apiSuccess {string} cheque_num    支票号码
     * @apiSuccess {string} create_time    申请时间
     * @apiSuccess {string} ransomer    赎楼员
     * @apiSuccess {string} financing_manager    理财经理
     * @apiSuccess {int} count    总条数
     */
    public function checkList() {
        $limit = $this->request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $page = $this->request->get('page', 1);
        $pageSize = $limit ? $limit : config('paginate')['list_rows'];

        $create_uid = $this->request->get('create_uid', 0);
        $subordinates = $this->request->get('subordinates', 0);
        $type = $this->request->get('type', '');
        $account_status = $this->request->get('account_status', '');
        $keywords = $this->request->get('keywords', '', 'trim');
        //查询条件组装
        $where = [];
        if ($create_uid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($create_uid);
            } else {
                $userStr = $create_uid;
            }
            $where['o.financing_manager_id'] = ['in', $userStr];
        }
        $type && $where['o.type'] = $type;
        $account_status && $where['x.account_status'] = $account_status;
        $keywords && $where['x.order_sn|o.finance_sn|e.estate_name|e.estate_owner'] = ['like', "%{$keywords}%"];

        $where['x.status'] = 1;
        $where['x.way'] = 2;
        $field = "x.id,x.order_sn,x.cheque_num,x.money,x.account_status,x.create_time,o.type,o.finance_sn,o.financing_manager_id,e.estate_name,e.estate_owner,g.money as guarantee_fee,x.create_uid";
        $creditList = $this->orderransomout->alias('x')
                        ->join('__ORDER__ o', 'o.order_sn=x.order_sn')
                        ->join('__ESTATE__ e', 'e.order_sn=x.order_sn', 'left')
                        ->join('__ORDER_GUARANTEE__ g', 'g.order_sn=x.order_sn')
                        ->join('__ORDER_RANSOM_DISPATCH__ d', 'd.id=x.ransom_dispatch_id')
                        ->where($where)->field($field)
                        ->order('x.create_time', 'DESC')
                        ->group('x.id')
                        ->paginate(array('list_rows' => $pageSize, 'page' => $page))->toArray();
        if (!empty($creditList['data'])) {
            foreach ($creditList['data'] as &$value) {
                $value['ransomer'] = Db::name('system_user')->where('id', $value['create_uid'])->value('name');
                $value['account_status_text'] = $this->orderransomout->getAccountstatus($value['account_status']);
                $value['type_text'] = $this->order->getType($value['type']);
                $value['create_time'] = date('Y-m-d', strtotime($value['create_time']));
                $value['financing_manager'] = $this->systemuser->where('id', $value['financing_manager_id'])->value('name');
            }
        }
        return $this->buildSuccess(['list' => $creditList['data'], 'count' => $creditList['total']]);
    }

    /**
     * @api {get} admin/BankAccount/checkDetail 出账详情页[admin/BankAccount/checkDetail]
     * @apiVersion 1.0.0
     * @apiName checkDetail
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/checkDetail
     *
     * @apiParam {int} id    出账id
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
      "orderinfo": {
      "estate_name": [
      "金怡华庭1栋30F"
      ],
      "estate_owner": "金荣富、金毅杰",
      "type_text": "交易担保",
      "type": "JYDB",
      "finance_sn": "100000075",
      "order_sn": "JYDB2018070023",
      "account_status_text": "财务已出账",
      "ransom_status": 3,
      "money": "1250000.00",
      "default_interest": "0.00",
      "self_financing": "0.00",
      "loan_money": "1250000.00",
      "short_loan_interest": "0.00",
      "can_money": 1250000,
      "out_money": 168922,
      "use_money": 1081078
      },
      "debitbank": {
      "out_bank_card": "1111111111",
      "out_bank": "民生银行",
      "out_bank_account": "中诚金服",
      "outok_time": "2018-07-25 08:35"
      },
      "debit": {
      "ransomer": "杜小彦",
      "money": "10000.00",
      "way_text": "现金",
      "ransom_bank": "工商银行-深圳市上步支行",
      "item_text": "银行罚息",
      "create_time": "2018-07-20 20:54",
      "is_prestore_text": "是",
      "account_type_text": "卖方账户",
      "receipt_bank": "(中国农业银行)",
      "prestore_day": null,
      "receipt_bank_account": "聂梦",
      "receipt_bank_card": "6666666666"
      },
      "id": 17
      }
     */
    public function checkDetail() {
        $id = $this->request->get('id', '');
        if ($id) {
            //订单信息
            $data = $this->orderransomout->where('id', $id)->field('account_status,ransom_dispatch_id,order_sn')->find();
            $orderinfo = OrderComponents::showDebitorderInfo($data['order_sn'], $data['account_status'], 2);
            if (!$orderinfo)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '订单信息记录未找到');
            //出账申请
            $debit = OrderComponents::showDebitInfo($id);
            //出账银行
            $debitbank = OrderComponents::showBankInfo($id);
            if (!$debit)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '出账申请记录未找到');
            return $this->buildSuccess(['orderinfo' => $orderinfo, 'debitbank' => $debitbank, 'debit' => $debit, 'id' => $data['ransom_dispatch_id']]);
        }else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/BankAccount/accountFlow 出账流水[admin/BankAccount/accountFlow]
     * @apiVersion 1.0.0
     * @apiName accountFlow
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/accountFlow
     *
     * @apiParam {string} id    派单id
     * 
     * @apiSuccess {array}info    统计信息
     * @apiSuccess {string} out_money    已出账金额
     * @apiSuccess {string} use_money    可用余额
     * 
     * @apiSuccess {array}totalarr    列表信息
     * @apiSuccess {int} id    出账id
     * @apiSuccess {string} item_text    出账项目
     * @apiSuccess {string} ransom_bank    赎楼银行
     * @apiSuccess {string} money    出账金额
     * @apiSuccess {string} ransomer    赎楼员
     * @apiSuccess {string} way_text    出账方式
     * @apiSuccess {string} bank    支票银行
     * @apiSuccess {string} cheque_num    支票号码
     * @apiSuccess {string} create_time    申请时间
     */
    public function accountFlow() {
        $id = $this->request->get('id', '');
        if ($id) {
            $where = ['id' => $id];
            $order_sn = $this->orderransomdispatch->where($where)->value('order_sn');
            //整个派单流水
            $debitinfolog = OrderComponents::showDebitInfolog($order_sn);
            return $this->buildSuccess($debitinfolog);
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {post} admin/BankAccount/determineAccount 确认出账[admin/BankAccount/determineAccount]
     * @apiVersion 1.0.0
     * @apiName determineAccount
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/determineAccount
     *
     * @apiParam {string} id    出账id
     * @apiParam {string} out_bank_card    出账卡号
     * @apiParam {string} out_bank    出账银行
     * @apiParam {string} out_bank_account    出账账户
     * @apiParam {string} out_bank_card_name    出账账户别名
     */
    public function determineAccount() {
        $data = $this->request->post('', null, 'trim');
        if ($data) {
            $userInfo['id'] = $this->userInfo['id'];
            if (empty($userInfo['id']))
                return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
            $updata = ['outok_time' => time(), 'outok_uid' => $userInfo['id'], 'out_bank_card_name' => $data['out_bank_card_name'], 'account_status' => 2, 'out_bank_card' => $data['out_bank_card'], 'out_bank' => $data['out_bank'], 'out_bank_account' => $data['out_bank_account']];
            $orderinfo = $this->orderransomout->where('id', $data['id'])->field('order_sn,ransom_dispatch_id,money,item,account_status')->find();
            if ($orderinfo['account_status'] != 1) {
                return $this->buildFailed(ReturnCode::UNKNOWN, '此订单已确认出账，请确认后重试');
            }
            $orderinfo['item'] = $this->orderransomout->getItem($orderinfo['item']);
            Db::startTrans();
            try {
                if ($this->orderransomout->where('id', $data['id'])->update($updata) > 0) {
                    //只要现金类业务申请一笔出账就要进入回款计费 2018.08.29
                    if ($this->order->where(['order_sn' => $orderinfo['order_sn'], 'type' => array('in', 'JYXJ,TMXJ,PDXJ,DQJK,SQDZ,GMDZ')])->whereNull('return_money_status')->count() > 0) {//如果主订单回款状态已经存在 则不需要再修改该字段
                        if (!$this->order->where(['order_sn' => $orderinfo['order_sn']])->setField('return_money_status', 1)) {
                            Db::rollback();
                            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '主订单回款状态修改失败！');
                        }
                    }
                    //区分公司扣款还是业主扣款 2018.8.21 
                    if (!$this->orderransomout->distinctMoney($orderinfo['order_sn'], $data['id'], $orderinfo['money'])) {
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::UPDATE_FAILED, '新增出账申请记录失败！');
                    }
                    //加订单操作记录 
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $operate_det = "确认出账：确认一笔" . $orderinfo['item'] . "出账,出账金额:" . $orderinfo['money'] . "元";
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $orderinfo['ransom_dispatch_id'];
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '确认出账', '待财务出账', $operate_det, $operate_reason = '', 1014, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '确认出账信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/BankAccount/reviewAccount 审核[admin/BankAccount/reviewAccount]
     * @apiVersion 1.0.0
     * @apiName reviewAccount
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/reviewAccount
     *
     * @apiParam {string} id    出账id
     */
    public function reviewAccount() {
        $id = $this->request->get('id', '');
        if ($id) {
            $userInfo['id'] = $this->userInfo['id'];
            if (empty($userInfo['id']))
                return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
            $updata = ['update_time' => time(), 'account_status' => 3];
            $orderinfo = $this->orderransomout->where('id', $id)->field('order_sn,ransom_dispatch_id,money,item,account_status,create_uid')->find();
            //
            if ($orderinfo['account_status'] != 2) {
                return $this->buildFailed(ReturnCode::UNKNOWN, '此订单已审核，请确认后重试');
            }
            $order_type = Db::name('order')->where(['order_sn' => $orderinfo['order_sn']])->value('type');
            if ($order_type == 'PDXJ' || $order_type == 'DQJK' || $order_type == 'SQDZ') {
                $updata = array_merge($updata, ['cut_status' => 1]);
            }
            $orderinfo['item'] = $this->orderransomout->getItem($orderinfo['item']);
            Db::startTrans();
            try {
                if ($this->orderransomout->where('id', $id)->update($updata) > 0) {
                    $order_type = Db::name('order')->where(['order_sn' => $orderinfo['order_sn']])->value('type');
                    //加APP消息推送记录  2018.8.27
                    if (!$this->message->AddmessageRecord($orderinfo['create_uid'], 2, 3, $id, $orderinfo['order_sn'], 3, '财务出账成功', '订单号为' . $orderinfo['order_sn'] . '财务出账成功，请及时处理', 1, 1, 0, 0, '', 'PC财务出账', 'order_ransom_out')) {
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::UPDATE_FAILED, '消息推送记录新增失败！');
                    }
                    //加订单操作记录 
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $operate_det = "审核通过：审核一笔" . $orderinfo['item'] . "出账,审核金额：" . $orderinfo['money'] . "元";
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $orderinfo['ransom_dispatch_id'];
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '审核通过', '待财务审核', $operate_det, $operate_reason = '', 1014, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '审核信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {post} admin/BankAccount/turndownAccount 驳回[admin/BankAccount/turndownAccount]
     * @apiVersion 1.0.0
     * @apiName turndownAccount
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/turndownAccount
     *
     * @apiParam {string} id    出账id
     * @apiParam {string} operate_reason    驳回理由
     */
    public function turndownAccount() {
        $data = $this->request->post('', null, 'trim');
        if (!empty($data['operate_reason'])) {
            $userInfo['id'] = $this->userInfo['id'];
            if (empty($userInfo['id']))
                return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
            $updata = ['update_time' => time(), 'account_status' => 1];
            $orderinfo = $this->orderransomout->where('id', $data['id'])->field('order_sn,ransom_dispatch_id,account_status,money,item')->find();
            if ($orderinfo['account_status'] != 2) {
                return $this->buildFailed(ReturnCode::UNKNOWN, '此订单已驳回，请确认后重试');
            }
            $orderinfo['item'] = $this->orderransomout->getItem($orderinfo['item']);
            Db::startTrans();
            try {
                if ($this->orderransomout->where('id', $data['id'])->update($updata) > 0) {
                    //加订单操作记录 
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $operate_det = "出账驳回：驳回一笔" . $orderinfo['item'] . "出账,驳回金额：" . $orderinfo['money'] . "元";
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $orderinfo['ransom_dispatch_id'];
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '出账驳回', '待财务审核', $operate_det, $operate_reason = $data['operate_reason'], 1014, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '出账驳回信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '请输入驳回原因');
        }
    }

    /**
     * @api {get} admin/BankAccount/backAccount 退单[admin/BankAccount/backAccount]
     * @apiVersion 1.0.0
     * @apiName backAccount
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/backAccount
     *
     * @apiParam {string} id    出账id
     */
    public function backAccount() {
        $id = $this->request->get('id', '');
        if ($id) {
            $userInfo['id'] = $this->userInfo['id'];
            if (empty($userInfo['id']))
                return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
            $orderinfo = $this->orderransomout->where('id', $id)->field('order_sn,create_uid,ransom_dispatch_id,money,item,cut_status')->find();
            if ($orderinfo['cut_status'] == 1) {
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '此订单赎楼员已确认扣款，无法进行退单操作！');
            }
            $updata = ['update_time' => time(), 'account_status' => 4];
            $orderinfo['item'] = $this->orderransomout->getItem($orderinfo['item']);
            $order_type = Db::name('order')->where(['order_sn' => $orderinfo['order_sn']])->value('type');
//            $dis_stage = 206;
//            if ($order_type == 'PDXJ' || $order_type == 'DQJK' || $order_type == 'SQDZ') {
//                $dis_stage = 208;
//            }
            Db::startTrans();
            try {
                if ($this->orderransomout->where('id', $id)->update($updata) > 0) {
                    //加APP消息推送记录  2018.8.29
                    if (!$this->message->AddmessageRecord($orderinfo['create_uid'], 2, 4, $id, $orderinfo['order_sn'], 4, '财务出账退回', '订单号为' . $orderinfo['order_sn'] . '财务出账已退回，请点击查看', 1, 1, 0, 0, '', 'PC财务出账退回', 'order_ransom_out')) {
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::UPDATE_FAILED, '消息推送记录新增失败！');
                    }
                    $this->orderransomdispatch->where('id', $orderinfo['ransom_dispatch_id'])->setDec('money_total', $orderinfo['money']);
                    //区分公司扣款还是业主扣款 2018.8.21 
                    if (!$this->orderransomout->backMoney($id)) {
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::UPDATE_FAILED, '退单失败！');
                    }
                    //加订单操作记录 
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $operate_det = "退回出账：退回一笔" . $orderinfo['item'] . "出账,退单金额:" . $orderinfo['money'] . "元";
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $orderinfo['ransom_dispatch_id'];
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '退回派单', '待银行放款', $operate_det, $operate_reason = '', 1014, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '退回订单信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/BankAccount/cashList 现金出账列表[admin/BankAccount/cashList]
     * @apiVersion 1.0.0
     * @apiName cashList
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/cashList
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type     订单类型
     * @apiParam {int} account_status     出账状态（1待财务出账 2待财务复核 3待银行扣款 4出账已退回 5财务已出账）
     * @apiParam {int} keywords     关键词
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {string} finance_sn    财务序号
     * @apiSuccess {string} order_sn    业务单号
     * @apiSuccess {string} estate_name    房产名称
     * @apiSuccess {string} estate_owner    业主姓名
     * @apiSuccess {string} type_text    订单类型
     * @apiSuccess {string} account_status_text    出账状态
     * @apiSuccess {string} money    出账金额
     * @apiSuccess {string} guarantee_fee    担保金额
     * @apiSuccess {string} receipt_bank_account    收款户名
     * @apiSuccess {string} create_time    申请时间
     * @apiSuccess {string} ransomer    赎楼员
     * @apiSuccess {string} financing_manager    理财经理
     * @apiSuccess {int} count    总条数
     */
    public function cashList() {
        $limit = $this->request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $page = $this->request->get('page', 1);
        $pageSize = $limit ? $limit : config('paginate')['list_rows'];
        $create_uid = $this->request->get('create_uid', 0);
        $subordinates = $this->request->get('subordinates', 0);
        $type = $this->request->get('type', '');
        $account_status = $this->request->get('account_status', '');
        $keywords = $this->request->get('keywords', '', 'trim');
        //查询条件组装
        $where = [];
        if ($create_uid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($create_uid);
            } else {
                $userStr = $create_uid;
            }
            $where['o.financing_manager_id'] = ['in', $userStr];
        }
        $type && $where['o.type'] = $type;
        $account_status && $where['x.account_status'] = $account_status;
        $keywords && $where['x.order_sn|o.finance_sn|e.estate_name|e.estate_owner'] = ['like', "%{$keywords}%"];
        $where['x.status'] = 1;
        $where['x.way'] = 1;
        $field = "x.id,x.order_sn,x.receipt_bank_account,x.money,x.account_status,x.create_time,o.type,o.finance_sn,o.financing_manager_id,e.estate_name,e.estate_owner,g.money as guarantee_fee,d.ransomer";
        $creditList = $this->orderransomout->alias('x')
                        ->join('__ORDER__ o', 'o.order_sn=x.order_sn')
                        ->join('__ESTATE__ e', 'e.order_sn=x.order_sn', 'left')
                        ->join('__ORDER_GUARANTEE__ g', 'g.order_sn=x.order_sn')
                        ->join('__ORDER_RANSOM_DISPATCH__ d', 'd.order_sn=x.order_sn')
                        ->where($where)->field($field)
                        ->order('x.create_time', 'DESC')
                        ->group('x.id')
                        ->paginate(array('list_rows' => $pageSize, 'page' => $page))->toArray();
        if (!empty($creditList['data'])) {
            foreach ($creditList['data'] as &$value) {
                $value['account_status_text'] = $this->orderransomout->getAccountstatus($value['account_status']);
                $value['type_text'] = $this->order->getType($value['type']);
                $value['create_time'] = date('Y-m-d', strtotime($value['create_time']));
                $value['financing_manager'] = $this->systemuser->where('id', $value['financing_manager_id'])->value('name');
            }
        }
        return $this->buildSuccess(['list' => $creditList['data'], 'count' => $creditList['total']]);
    }

    /**
     * @api {get} admin/BankAccount/trackacountList 出账跟踪[admin/BankAccount/trackacountList]
     * @apiVersion 1.0.0
     * @apiName trackacountList
     * @apiGroup BankAccount
     * @apiSampleRequest admin/BankAccount/trackacountList
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type     订单类型
     * @apiParam {int} is_prestore   是否预存 
     * @apiParam {int} keywords     关键词
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {string} finance_sn    财务序号
     * @apiSuccess {string} order_sn    业务单号
     * @apiSuccess {string} estate_name    房产名称
     * @apiSuccess {string} estate_owner    业主姓名
     * @apiSuccess {string} money    出账金额
     * @apiSuccess {string} way_text    出账方式
     * @apiSuccess {string} prestore_day    预存天数
     * @apiSuccess {string} outok_time    出账时间
     * @apiSuccess {string} ransomer    赎楼员
     * @apiSuccess {string} financing_manager    理财经理
     * @apiSuccess {int} count    总条数
     */
    public function trackacountList() {
        $limit = $this->request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $page = $this->request->get('page', 1);
        $pageSize = $limit ? $limit : config('paginate')['list_rows'];
        $create_uid = $this->request->get('create_uid', 0);
        $subordinates = $this->request->get('subordinates', 0);
        $type = $this->request->get('type', '');
        $is_prestore = $this->request->get('is_prestore/d', '');
        $keywords = $this->request->get('keywords', '', 'trim');
        //查询条件组装
        $where = [];
        if ($create_uid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($create_uid);
            } else {
                $userStr = $create_uid;
            }
            $where['o.financing_manager_id'] = ['in', $userStr];
        }
        $type && $where['o.type'] = $type;
        if ($is_prestore === 0 || !empty($is_prestore)) {
            $where['x.is_prestore'] = $is_prestore;
        }
        $keywords && $where['x.order_sn|o.finance_sn|e.estate_name|e.estate_owner'] = ['like', "%{$keywords}%"];
        $where['x.status'] = 1;
        $where['x.account_status'] = 3;
        $where['o.stage'] = 1014;
        $field = "x.id,x.order_sn,o.type,x.money,x.outok_time,x.prestore_day,x.way,o.finance_sn,o.financing_manager_id,e.estate_name,e.estate_owner,d.ransomer,x.create_uid";
        $creditList = $this->orderransomout->alias('x')
                        ->join('__ORDER__ o', 'o.order_sn=x.order_sn')
                        ->join('__ESTATE__ e', 'e.order_sn=x.order_sn', 'left')
                        ->join('__ORDER_RANSOM_DISPATCH__ d', 'd.order_sn=x.order_sn')
                        ->where($where)->field($field)
                        ->order('x.create_time', 'DESC')
                        ->group('x.id')
                        ->paginate(array('list_rows' => $pageSize, 'page' => $page))->toArray();
        if (!empty($creditList['data'])) {
            foreach ($creditList['data'] as &$value) {
                $value['way_text'] = $value['way'] == 1 ? '现金' : '支票';
                $value['outok_time'] = date('Y-m-d', $value['outok_time']);
                $value['financing_manager'] = $this->systemuser->where('id', $value['financing_manager_id'])->value('name');
                $value['ransomer'] = Db::name('system_user')->where('id', $value['create_uid'])->value('name');
            }
        }
        return $this->buildSuccess(['list' => $creditList['data'], 'count' => $creditList['total']]);
    }

}
