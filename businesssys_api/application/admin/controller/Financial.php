<?php

/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/9
 * Time: 13:46
 */

namespace app\admin\controller;

use app\util\ReturnCode;
use app\util\OrderComponents;
use app\model\Order;
use app\model\SystemUser;
use app\model\OrderCostRecord;
use app\model\OrderAccountRecord;
use think\Db;
use app\model\OrderGuarantee;
use app\model\Dictionary;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Financial extends Base {

    /**
     * @api {post} admin/Financial/bookedList 财务费用待入账列表[admin/Financial/bookedList]
     * @apiVersion 1.0.0
     * @apiName bookedList
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/bookedList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  guarantee_fee_status   收费状态 1未收齐 2已收齐
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
     *       "total": 2,
     *       "per_page": 20,
     *       "current_page": 1,
     *       "last_page": 1,
     *       "data": [
     *           {
     *           "order_sn": "JYDB2018050096",
     *           "finance_sn": "100000047",
     *           "type": "JYDB",
     *           "name": "夏丽平",
     *           "estate_name": "国际新城",
     *           "estate_owner": null,
     *           "ac_guarantee_fee_time": "2018-05-08 14:50:07",
     *           "guarantee_fee": "2.00",
     *           "ac_guarantee_fee": "0.00",
     *           "guarantee_fee_status": 1
     *           },
     *           {
     *           "order_sn": "JYDB2018050095",
     *           "finance_sn": "100000047",
     *           "type": "JYDB",
     *           "name": "夏丽平",
     *           "estate_name": "国际新城",
     *           "estate_owner": null,
     *           "ac_guarantee_fee_time": "2018-05-08 14:46:58",
     *           "guarantee_fee": "2.00",
     *           "ac_guarantee_fee": "0.00",
     *           "guarantee_fee_status": 1
     *           }
     *       ]
     *   }
     * @apiSuccess {int} total    总条数
     * @apiSuccess {int} per_page    每页显示的条数
     * @apiSuccess {int} current_page    当前页
     * @apiSuccess {int} last_page    总页数
     * @apiSuccess {string} order_sn    业务单号
     * @apiSuccess {int} finance_sn    财务序号
     * @apiSuccess {int} type     订单类型
     * @apiSuccess {string} name    理财经理
     * @apiSuccess {string} estate_name    房产名称
     * @apiSuccess {string} estate_owner    业主姓名
     * @apiSuccess {string} ac_guarantee_fee_time    入账时间
     * @apiSuccess {float} guarantee_fee    应收担保费
     * @apiSuccess {float} ac_guarantee_fee    实收担保费
     * @apiSuccess {int} guarantee_fee_status    收费状态 1未收齐 2已收齐
     */
    public function bookedList() {
        $res = $this->bookedListWhere(1);
        try {
            $resInfo = Order::costList($res['map'], $res['page'], $res['pageSize']);
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
            if ($resInfo) {
                foreach ($resInfo['data'] as &$val) {
                    $val['type_text'] = $newStageArr[$val['type']] ? $newStageArr[$val['type']] : '';
                    if ($val['ac_guarantee_fee_time']) {
                        $val['ac_guarantee_fee_time'] = date('Y-m-d H:i:s', $val['ac_guarantee_fee_time']);
                    }
                }
            }
            return $this->buildSuccess($resInfo);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/bookedHasList 财务费用已入账列表[admin/Financial/bookedHasList]
     * @apiVersion 1.0.0
     * @apiName bookedHasList
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/bookedHasList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  guarantee_fee_status   收费状态 1未收齐 2已收齐
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     */
    public function bookedHasList() {
        $res = $this->bookedListWhere(2);
        try {
            $resInfo = Order::costList($res['map'], $res['page'], $res['pageSize']);
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
            if ($resInfo) {
                foreach ($resInfo['data'] as &$val) {
                    $val['type_text'] = $newStageArr[$val['type']] ? $newStageArr[$val['type']] : '';
                    if ($val['ac_guarantee_fee_time']) {
                        $val['ac_guarantee_fee_time'] = date('Y-m-d H:i:s', $val['ac_guarantee_fee_time']);
                    }
                }
            }
            return $this->buildSuccess($resInfo);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /*
     * @author 赵光帅
     * 组装费用入账列表的条件
     * @Param {int}  $typeList   1 费用待入账列表条件(担保费未收齐)  2 费用已入账列表条件(担保费已收齐)
     * */

    protected function bookedListWhere($typeList) {
        $createUid = input('create_uid') ?: 0;
        $subordinates = input('subordinates') ?: 0;
        $type = input('type');
        $startTime = strtotime(input('start_time'));
        $endTime = strtotime(input('end_time'));
        //$guarantee_fee_status = input('guarantee_fee_status')?:0;
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['x.financing_manager_id'] = ['in', $userStr];
        }
        if ($startTime && $endTime) {
            if ($startTime > $endTime) {
                $startTime = $startTime + 86400;
                $map['n.ac_guarantee_fee_time'] = array(array('egt', $endTime), array('elt', $startTime));
            } else {
                $endTime = $endTime + 86400;
                $map['n.ac_guarantee_fee_time'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        } elseif ($startTime) {
            $map['n.ac_guarantee_fee_time'] = ['egt', $startTime];
        } elseif ($endTime) {
            $endTime = $endTime + 86400;
            $map['n.ac_guarantee_fee_time'] = ['elt', $endTime];
        }
        $type && $map['x.type'] = $type;
        //$guarantee_fee_status && $map['n.guarantee_fee_status'] = $guarantee_fee_status;
        $searchText && $map['y.estate_name|x.order_sn|x.finance_sn|y.estate_owner'] = ['like', "%{$searchText}%"];
        $map['x.delete_time'] = NULL;
        $map['x.status'] = 1;
        if ($typeList == 1) {
            $map['n.guarantee_fee_status'] = 1;
        } else {
            $map['n.guarantee_fee_status'] = 2;
        }

        return ['map' => $map, 'page' => $page, 'pageSize' => $pageSize];
    }

    /**
     * @api {post} admin/Financial/isCollected 担保费是否已收齐[admin/Financial/isCollected]
     * @apiVersion 1.0.0
     * @apiName isCollected
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/isCollected
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  type   是否收齐 1已收齐 2未收齐
     */
    public function isCollected() {
        $ordersn = input('order_sn');
        $type = input('type');
        if (empty($type))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '是否收齐类型不能为空!');
        if (empty($ordersn))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        try {
            $guaranteeInfo = OrderGuarantee::get(['order_sn' => $ordersn]);
            //if($guaranteeInfo->guarantee_fee_status == 2) return $this->buildFailed(ReturnCode::UPDATE_FAILED, '担保费已经收齐，不能重复操作!');
            if ($type == 1) {
                if ($guaranteeInfo->guarantee_fee_status == 2)
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '担保费已收齐,不能再进行收齐!');
                $guaranteeInfo->guarantee_fee_status = 2;
                $content = "担保费已收齐";
                $msg = "已收齐成功!";
            }else {
                if ($guaranteeInfo->guarantee_fee_status == 1)
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '担保费未收齐,不能再进行未收齐!');
                $guaranteeInfo->guarantee_fee_status = 1;
                $content = "担保费未收齐";
                $msg = "取消收齐成功!";
            }
            $guaranteeInfo->save();

            /* 添加订单操作记录 */
            $orderInfo = Order::getOne(['order_sn' => $ordersn], 'stage');
            $stage = $operate = show_status_name($orderInfo['stage'], 'ORDER_JYDB_STATUS');
            $operate_node = "确认担保费是否收齐";
            $operate_det = $this->userInfo['name'] . ',更改' . $content;
            $operate_reason = '';
            $stage_code = $orderInfo['stage'];
            $operate_table = '';
            OrderComponents::addOrderLog($this->userInfo, $ordersn, $stage, $operate_node, $operate, $operate_det, $operate_reason, $stage_code, $operate_table);
            return $this->buildSuccess($msg);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '操作失败');
        }
    }

    /**
     * @api {post} admin/Financial/addBooksWater 增加财务入账流水[admin/Financial/addBooksWater]
     * @apiVersion 1.0.0
     * @apiName addBooksWater
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/addBooksWater
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  finance_sn   财务序号
     * @apiParam {float}  guarantee_fee  担保费
     * @apiParam {float}  fee   手续费
     * @apiParam {float}  self_financing 自筹金额
     * @apiParam {float}  short_loan_interest 短贷利息
     * @apiParam {float}  return_money 赎楼返还款
     * @apiParam {float}  default_interest 罚息
     * @apiParam {float}  overdue_money 逾期金额
     * @apiParam {float}  exhibition_fee 展期费
     * @apiParam {float}  transfer_fee 过账手续费
     * @apiParam {date}  cost_time 入账时间(2018-05-08)
     * @apiParam {float}  deposit 保证金
     * @apiParam {float}  other_money 其它
     * @apiParam {string}  remark   备注说明
     * @apiParam {int}  guarantee_fee_status   收费状态 1未收齐 2已收齐
     *
     */
    public function addBooksWater() {
        $waterInfo['order_sn'] = input('order_sn');
        $waterInfo['finance_sn'] = input('finance_sn');
        $waterInfo['guarantee_fee'] = sprintf("%.2f", input('guarantee_fee') ?: 0);
        $waterInfo['fee'] = sprintf("%.2f", input('fee') ?: 0);
        $waterInfo['self_financing'] = sprintf("%.2f", input('self_financing') ?: 0);
        $waterInfo['short_loan_interest'] = sprintf("%.2f", input('short_loan_interest') ?: 0);
        $waterInfo['return_money'] = sprintf("%.2f", input('return_money') ?: 0);
        $waterInfo['default_interest'] = sprintf("%.2f", input('default_interest') ?: 0);
        $waterInfo['overdue_money'] = sprintf("%.2f", input('overdue_money') ?: 0);
        $waterInfo['exhibition_fee'] = sprintf("%.2f", input('exhibition_fee') ?: 0);
        $waterInfo['transfer_fee'] = sprintf("%.2f", input('transfer_fee') ?: 0);
        $waterInfo['deposit'] = sprintf("%.2f", input('deposit') ?: 0);
        $waterInfo['cost_time'] = input('cost_time') ?: '1000-10-10';
        $waterInfo['other_money'] = sprintf("%.2f", input('other_money') ?: 0);
        $waterInfo['remark'] = input('remark');
        //$guarantee_fee_status = input('guarantee_fee_status');
        if ($waterInfo['transfer_fee'] == 0 && $waterInfo['exhibition_fee'] == 0 && $waterInfo['guarantee_fee'] == 0 && $waterInfo['fee'] == 0 && $waterInfo['self_financing'] == 0 && $waterInfo['short_loan_interest'] == 0 && $waterInfo['return_money'] == 0 && $waterInfo['default_interest'] == 0 && $waterInfo['overdue_money'] == 0 && $waterInfo['deposit'] == 0 && $waterInfo['other_money'] == 0)
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '流入入账不能为空!');
        //验证器验证参数
        $valiDate = validate('FinanVail');
        if (!$valiDate->check($waterInfo)) {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
        }
        // 启动事务
        Db::startTrans();
        try {
            //更改担保赎楼信息表信息
            $guaranteeInfo = OrderGuarantee::get(['order_sn' => $waterInfo['order_sn']]);
            //判断担保费是否已经收齐
            if ($guaranteeInfo->guarantee_fee_status == 2 && !empty($waterInfo['guarantee_fee']) && $waterInfo['guarantee_fee'] != 0.00)
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '担保费已经收齐，不能再收取担保费!');
            $total_money = $waterInfo['guarantee_fee'] + $waterInfo['fee'] + $waterInfo['self_financing'] + $waterInfo['short_loan_interest'] +
                    $waterInfo['return_money'] + $waterInfo['default_interest'] + $waterInfo['overdue_money'] + $waterInfo['exhibition_fee'] + $waterInfo['transfer_fee'] + $waterInfo['other_money'] + $waterInfo['deposit'];
            $waterInfo['total_money'] = $total_money;
            $waterInfo['create_time'] = time();
            $waterInfo['create_uid'] = $this->userInfo['id'];
            //添加入账流水
            $resCost = OrderCostRecord::create($waterInfo);
            if (empty($resCost)) {
                // 回滚事务
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '流水添加失败!');
            }

            $guaranteeInfo->ac_guarantee_fee = $guaranteeInfo['ac_guarantee_fee'] + $waterInfo['guarantee_fee'];
            $guaranteeInfo->ac_fee = $guaranteeInfo['ac_fee'] + $waterInfo['fee'];
            $guaranteeInfo->ac_self_financing = $guaranteeInfo['ac_self_financing'] + $waterInfo['self_financing'];
            $guaranteeInfo->ac_short_loan_interest = $guaranteeInfo['ac_short_loan_interest'] + $waterInfo['short_loan_interest'];
            $guaranteeInfo->ac_return_money = $guaranteeInfo['ac_return_money'] + $waterInfo['return_money'];
            $guaranteeInfo->ac_default_interest = $guaranteeInfo['ac_default_interest'] + $waterInfo['default_interest'];
            $guaranteeInfo->ac_overdue_money = $guaranteeInfo['ac_overdue_money'] + $waterInfo['overdue_money'];
            $guaranteeInfo->ac_exhibition_fee = $guaranteeInfo['ac_exhibition_fee'] + $waterInfo['exhibition_fee'];
            $guaranteeInfo->ac_transfer_fee = $guaranteeInfo['ac_transfer_fee'] + $waterInfo['transfer_fee'];
            $guaranteeInfo->ac_deposit = $guaranteeInfo['ac_deposit'] + $waterInfo['deposit'];
            $guaranteeInfo->ac_other_money = $guaranteeInfo['ac_other_money'] + $waterInfo['other_money'];
            $guaranteeInfo->ac_guarantee_fee_time = time();
            $guaranteeInfo->update_time = time();
            /* if($guarantee_fee_status == 2) //代表已经收齐
              $guaranteeInfo->guarantee_fee_status = 2; */
            $resTee = $guaranteeInfo->save();
            if ($resTee <= 0) {
                // 回滚事务
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '费用更新失败!');
            }

            /* 添加订单操作记录 */
            //根据订单号查询出订单状态
            $stageInfo = Order::getOne(['order_sn' => $waterInfo['order_sn']], 'stage');
            if (strlen($stageInfo['stage']) == 4) {
                $operate = $stage = show_status_name($stageInfo['stage'], 'ORDER_JYDB_STATUS');
            } else {
                $operate = $stage = show_status_name($stageInfo['stage'], 'ORDER_JYDB_FINC_STATUS');
            }

            $operate_node = "费用入账";
            $operate_det = $this->userInfo['name'] . "添加入账流水";
            $operate_reason = '';
            $stage_code = $stageInfo['stage'];
            $operate_table = 'order';
            OrderComponents::addOrderLog($this->userInfo, $waterInfo['order_sn'], $stage, $operate_node, $operate, $operate_det, $operate_reason, $stage_code, $operate_table);
            // 提交事务
            Db::commit();
            return $this->buildSuccess('入账流水添加成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '入账流水添加失败' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/showBooksDetail 财务入账流水明细[admin/Financial/showBooksDetail]
     * @apiVersion 1.0.0
     * @apiName showBooksDetail
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/showBooksDetail
     *
     *
     * @apiParam {string}  order_sn   订单编号
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
     *       "code": 1,
     *       "msg": "操作成功",
     *        data": {
     *           "orderinfo": {
     *               "order_sn": "JYDB2018050137123456",
     *               "type": "JYDB",
     *               "name": "夏丽平",
     *               "deptname": "财务中心",
     *               "finance_sn": "100000048",
     *               "self_financing": "2.00",
     *               "guarantee_fee": "2.00",
     *               "fee": "2.00",
     *               "guarantee_fee_status": 2,
     *               "receivable_amount": 4,
     *               "shiShouMoney": 3665.1,
     *               "danBaoMoney": 3500
     *             },
     *           "booksWaterInfo": [
     *               {
     *               "total_money": "1634.70",
     *               "remark": "测试测试测试测试测试",
     *               "create_time": "2018-05-10 10:34:30",
     *               "operation_name": "杜欣",
     *               "arrinfo": [
     *                   {
     *                   "names": "担保费",
     *                   "money": "1500.00"
     *                   },
     *                   {
     *                   "names": "手续费",
     *                   "money": "-13.50"
     *                   },
     *                   {
     *                   "names": "自筹金额",
     *                   "money": "100.50"
     *                   },
     *                   {
     *                   "names": "短贷利息",
     *                   "money": "200.30"
     *                   },
     *                   {
     *                   "names": "赎楼返还款",
     *                   "money": "-152.60"
     *                   }
     *                 ]
     *               },
     *               {
     *               "total_money": "1015.20",
     *               "remark": "测试测试测试测试测试从",
     *               "create_time": "2018-05-10 10:28:58",
     *               "operation_name": "杜欣",
     *               "arrinfo": [
     *                   {
     *                   "names": "担保费",
     *                   "money": "1000.00"
     *                   },
     *                   {
     *                   "names": "手续费",
     *                   "money": "-15.00"
     *                   },
     *                   {
     *                   "names": "自筹金额",
     *                   "money": "30.00"
     *                   },
     *                   {
     *                   "names": "短贷利息",
     *                   "money": "-12.30"
     *                   },
     *                   {
     *                   "names": "赎楼返还款",
     *                   "money": "12.50"
     *                   }
     *                 ]
     *               },
     *            ]
     *        }
     *   }
     *
     * @apiSuccess {string} order_sn    订单编号
     * @apiSuccess {string} type    订单类型
     * @apiSuccess {string} name    理财经理
     * @apiSuccess {string} deptname    所在部门
     * @apiSuccess {int}   finance_sn  财务序号
     * @apiSuccess {float} self_financing    自筹金额
     * @apiSuccess {float} guarantee_fee    应收担保费
     * @apiSuccess {float} fee    应收手续费
     * @apiSuccess {int}   guarantee_fee_status  担保费是否收齐 1未收齐 2已收齐
     * @apiSuccess {float} receivable_amount    应收金额
     * @apiSuccess {float} shiShouMoney    实收金额总计
     * @apiSuccess {float} danBaoMoney    担保费总计
     * @apiSuccess {float} total_money    入账金额
     * @apiSuccess {string} remark    备注说明
     * @apiSuccess {string} cost_time    入账时间
     * @apiSuccess {string} create_time    操作时间
     * @apiSuccess {string} operation_name    操作人
     * @apiSuccess {int}   names   费用项目
     * @apiSuccess {float} money    费用金额
     */
    public function showBooksDetail() {
        $orderSn = input('order_sn');
        if (empty($orderSn))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        try {
            $returnInfo = [];
            $orderInfo = Order::booksDetail($orderSn);
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
            if ($orderInfo) {
                $orderInfo['type'] = $newStageArr[$orderInfo['type']] ? $newStageArr[$orderInfo['type']] : '';
            }
            $orderInfo['receivable_amount'] = $orderInfo['guarantee_fee'] + $orderInfo['fee'];
            $field = 'total_money,guarantee_fee,fee,self_financing,short_loan_interest,return_money,default_interest,overdue_money,exhibition_fee,transfer_fee,cost_time,other_money,deposit,remark,create_time,create_uid';
            $booksWaterInfo = OrderCostRecord::getAll(['order_sn' => $orderSn], $field, 'create_time desc');
            $fanHuiInfo = self::addFansDanbao($booksWaterInfo);
            $orderInfo['shiShouMoney'] = $fanHuiInfo['a'];
            $orderInfo['danBaoMoney'] = $fanHuiInfo['b'];
            $returnInfo['orderinfo'] = $orderInfo; //订单信息
            $returnInfo['booksWaterInfo'] = $fanHuiInfo['c']; //流水明细
            return $this->buildSuccess($returnInfo);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /*
     * 重新组装流水明细
     * @Param {array}  $booksWaterInfo   数据
     * */

    protected function addFansDanbao($booksWaterInfo) {
        $shiShouMoney = '';
        $danBaoMoney = '';
        foreach ($booksWaterInfo as $k => $v) {
            $shiShouMoney += $v['total_money'];
            $danBaoMoney += $v['guarantee_fee'];
            $moneyArr = [];
            $booksWaterInfo[$k]['operation_name'] = SystemUser::where(['id' => $v['create_uid']])->value('name');
            unset($booksWaterInfo[$k]['create_uid']);
            if (isset($v['guarantee_fee']) && !empty($v['guarantee_fee']) && $v['guarantee_fee'] != 0.00) {
                $moneyArr[] = array('names' => '担保费', 'money' => $v['guarantee_fee']);
            }
            unset($booksWaterInfo[$k]['guarantee_fee']);
            if (isset($v['fee']) && !empty($v['fee']) && $v['fee'] != 0.00) {
                $moneyArr[] = array('names' => '手续费', 'money' => $v['fee']);
            }
            unset($booksWaterInfo[$k]['fee']);
            if (isset($v['self_financing']) && !empty($v['self_financing']) && $v['self_financing'] != 0.00) {
                $moneyArr[] = array('names' => '自筹金额', 'money' => $v['self_financing']);
            }
            unset($booksWaterInfo[$k]['self_financing']);
            if (isset($v['short_loan_interest']) && !empty($v['short_loan_interest']) && $v['short_loan_interest'] != 0.00) {
                $moneyArr[] = array('names' => '短贷利息', 'money' => $v['short_loan_interest']);
            }
            unset($booksWaterInfo[$k]['short_loan_interest']);
            if (isset($v['return_money']) && !empty($v['return_money']) && $v['return_money'] != 0.00) {
                $moneyArr[] = array('names' => '赎楼返还款', 'money' => $v['return_money']);
            }
            unset($booksWaterInfo[$k]['return_money']);
            if (isset($v['default_interest']) && !empty($v['default_interest']) && $v['default_interest'] != 0.00) {
                $moneyArr[] = array('names' => '罚息', 'money' => $v['default_interest']);
            }
            unset($booksWaterInfo[$k]['default_interest']);
            if (isset($v['overdue_money']) && !empty($v['overdue_money']) && $v['overdue_money'] != 0.00) {
                $moneyArr[] = array('names' => '逾期费', 'money' => $v['overdue_money']);
            }
            unset($booksWaterInfo[$k]['overdue_money']);
            if (isset($v['exhibition_fee']) && !empty($v['exhibition_fee']) && $v['exhibition_fee'] != 0.00) {
                $moneyArr[] = array('names' => '展期费', 'money' => $v['exhibition_fee']);
            }
            unset($booksWaterInfo[$k]['exhibition_fee']);
            if (isset($v['transfer_fee']) && !empty($v['transfer_fee']) && $v['transfer_fee'] != 0.00) {
                $moneyArr[] = array('names' => '过账手续费', 'money' => $v['transfer_fee']);
            }
            unset($booksWaterInfo[$k]['transfer_fee']);
            if (isset($v['deposit']) && !empty($v['deposit']) && $v['deposit'] != 0.00) {
                $moneyArr[] = array('names' => '保证金', 'money' => $v['deposit']);
            }
            unset($booksWaterInfo[$k]['deposit']);
            if (isset($v['other_money']) && !empty($v['other_money']) && $v['other_money'] != 0.00) {
                $moneyArr[] = array('names' => '其它', 'money' => $v['other_money']);
            }
            unset($booksWaterInfo[$k]['other_money']);
            $booksWaterInfo[$k]['arrinfo'] = $moneyArr;
            if ($v['cost_time'] == '1000-10-10') {
                $booksWaterInfo[$k]['cost_time'] = '';
            }
        }
        $returnInfo['a'] = $shiShouMoney;
        $returnInfo['b'] = $danBaoMoney;
        $returnInfo['c'] = $booksWaterInfo;
        return $returnInfo;
    }

    /**
     * @api {post} admin/Financial/bankLendList 银行放款待入账列表[admin/Financial/bankLendList]
     * @apiVersion 1.0.0
     * @apiName bankLendList
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/bankLendList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  loan_money_status   银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
     *       "total": 2,
     *       "per_page": 20,
     *       "current_page": 1,
     *       "last_page": 1,
     *       "data": [
     *           {
     *           "order_sn": "JYDB2018050096",
     *           "finance_sn": "100000047",
     *           "type": "JYDB",
     *           "name": "夏丽平",
     *           "estate_name": "国际新城",
     *           "estate_owner": null,
     *           " loan_money_time": "2018-05-08 14:50:07",
     *           "guarantee_money": "2.00",
     *           " loan_money": "0.00",
     *           "loan_money_status": 1
     *           },
     *           {
     *           "order_sn": "JYDB2018050095",
     *           "finance_sn": "100000047",
     *           "type": "JYDB",
     *           "name": "夏丽平",
     *           "estate_name": "国际新城",
     *           "estate_owner": null,
     *           " loan_money_time": "2018-05-08 14:46:58",
     *           "guarantee_money": "2.00",
     *           " loan_money": "0.00",
     *           "loan_money_status": 1
     *           }
     *       ]
     *   }
     * @apiSuccess {int} total    总条数
     * @apiSuccess {int} per_page    每页显示的条数
     * @apiSuccess {int} current_page    当前页
     * @apiSuccess {int} last_page    总页数
     * @apiSuccess {string} order_sn    业务单号
     * @apiSuccess {int} finance_sn    财务序号
     * @apiSuccess {int} type     订单类型
     * @apiSuccess {string} name    理财经理
     * @apiSuccess {string} estate_name    房产名称
     * @apiSuccess {string} estate_owner    业主姓名
     * @apiSuccess {string}  loan_money_time    复核时间
     * @apiSuccess {float} guarantee_money    担保金额
     * @apiSuccess {float} loan_money    银行放款金额
     * @apiSuccess {int} loan_money_status    入账状态 1待入账 2待复核 3已复核 4驳回待处理
     */
    public function bankLendList() {
        $res = $this->bankLendWhere(1);
        try {
            return $this->buildSuccess(Order::bankList($res['map'], $res['page'], $res['pageSize']));
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/bankHasList 银行放款已入账列表[admin/Financial/bankHasList]
     * @apiVersion 1.0.0
     * @apiName bankHasList
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/bankHasList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  loan_money_status   银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     */
    public function bankHasList() {
        $res = $this->bankLendWhere(2);
        try {
            return $this->buildSuccess(Order::bankList($res['map'], $res['page'], $res['pageSize']));
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /**
     * @api {get} admin/Financial/exportBankHas 银行放款已入账导出[admin/Financial/exportBankHas]
     * @apiVersion 1.0.0
     * @apiName exportBankHas
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/exportBankHas
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  loan_money_status   银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理
     * @apiParam {int} search_text    关键字搜索
     */
    public function exportBankHas() {
        $res = $this->bankLendWhere(2);
        try {
            $resInfo = Order::exportBankHasList($res['map']);
            $head = ['0' => '序号', '1' => '业务单号', '2' => '财务序号', '3' => '房产名称', '4' => '卖方',
                '5' => '买方', '6' => '担保金额/元', '7' => '预计出账金额/元', '8' => '放款银行',
                '9' => '放款入账金额/元', '10' => '入账完成日期', '11' => '赎楼银行',
                '12' => '理财经理', '13' => '所属部门', '14' => '部门经理'];
            $retuurl = $this->exportExcel($resInfo, $head, '');//BankInAccount
            return $this->buildSuccess(['url' => $retuurl]);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '导出失败!' . $e->getMessage());
        }
    }

    /*
     * @author 赵光帅
     * 组装银行放款列表的条件
     * @Param {int}  $typeList   1 银行放款待入账列表条件  2 银行放款已入账列表条件
     * */

    protected function bankLendWhere($typeList) {
        $createUid = input('create_uid') ?: 0;
        $subordinates = input('subordinates') ?: 0;
        $type = input('type');
        $startTime = input('start_time');
        $endTime = input('end_time');
        $loan_money_status = input('loan_money_status') ?: 0;
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
        $userId = $this->userInfo['id'];
        //$userId = 2;
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['x.financing_manager_id'] = ['in', $userStr];
        }
        if ($startTime && $endTime) {
            if ($startTime > $endTime) {
                $startTime = date('Y-m-d', strtotime($startTime) + 86399);
                $map['n.loan_money_time'] = array(array('egt', $endTime), array('elt', $startTime));
            } else {
                $endTime = date('Y-m-d', strtotime($endTime) + 86399);
                $map['n.loan_money_time'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        } elseif ($startTime) {
            $map['n.loan_money_time'] = ['egt', $startTime];
        } elseif ($endTime) {
            $endTime = date('Y-m-d', strtotime($endTime) + 86399);
            $map['n.loan_money_time'] = ['elt', $endTime];
        }
        $searchText && $map['y.estate_name|x.order_sn|x.finance_sn|y.estate_owner'] = ['like', "%{$searchText}%"];
        $map['x.delete_time'] = NULL;
        $map['x.status'] = 1;
        //$map['n.is_dispatch'] = ['<>',0];
        $map['n.instruct_status'] = ['in', '0,3'];
        $map['x.type'] = ['in', 'JYDB,FJYDB'];
        $type && $map['x.type'] = $type;
        $map['x.stage'] = ['NOT BETWEEN', [1001, 1010]];
        if ($typeList == 1) {
            $map['n.loan_money_status'] = ['in', '1,2,4'];
            $loan_money_status && $map['n.loan_money_status'] = $loan_money_status;
        } else {
            $map['n.loan_money_status'] = 3;
        }
        return ['map' => $map, 'page' => $page, 'pageSize' => $pageSize];
    }

    /**
     * @api {post} admin/Financial/isLoanFinish 银行放款入账是否已收齐[admin/Financial/isLoanFinish]
     * @apiVersion 1.0.0
     * @apiName isLoanFinish
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/isLoanFinish
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  type   是否收齐 1已收齐 2未收齐
     *
     */
    public function isLoanFinish() {
        $ordersn = input('order_sn');
        $type = input('type');
        if (empty($type))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '是否收齐类型不能为空!');
        if (empty($ordersn))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        try {
            $guaranteeInfo = OrderGuarantee::get(['order_sn' => $ordersn]);
            if (empty($guaranteeInfo))
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '不存在该订单信息!');
            //if($guaranteeInfo->is_loan_finish == 1) return $this->buildFailed(ReturnCode::UPDATE_FAILED, '银行放款入账已经完成,不能重复操作!');
            if ($type == 1) {
                if ($guaranteeInfo->is_loan_finish == 1)
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '银行放款已收齐,不能再进行收齐!');
                $guaranteeInfo->is_loan_finish = 1;
                $guaranteeInfo->loan_money_status = 2;
                $msg = "已收齐成功!";
                $content = "银行放款已完成";
            }else {
                if (empty($guaranteeInfo->is_loan_finish))
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '银行放款已经是未收齐,不能再操作未收齐!');
                if ($guaranteeInfo->loan_money_status == 3)
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '银行放款已复核成功,不能再操作未收齐!');
                $guaranteeInfo->is_loan_finish = 0;
                $guaranteeInfo->loan_money_status = 1;
                $msg = "取消收齐成功!";
                $content = "银行放款未完成";
            }
            $guaranteeInfo->save();

            /* 添加订单操作记录 */
            $orderInfo = Order::getOne(['order_sn' => $guaranteeInfo['order_sn']], 'stage');
            $stage = $operate = show_status_name($orderInfo['stage'], 'ORDER_JYDB_STATUS');
            $operate_node = "确认银行放款是否完成";
            $operate_det = $this->userInfo['name'] . ',更改' . $content;
            $operate_reason = '';
            $stage_code = $orderInfo['stage'];
            $operate_table = '';
            OrderComponents::addOrderLog($this->userInfo, $guaranteeInfo['order_sn'], $stage, $operate_node, $operate, $operate_det, $operate_reason, $stage_code, $operate_table);
            return $this->buildSuccess($msg);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '操作失败');
        }
    }

    /**
     * @api {post} admin/Financial/paymentAccount 公司收款账户[admin/Financial/paymentAccount]
     * @apiVersion 1.0.0
     * @apiName paymentAccount
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/paymentAccount
     * @apiParam {int}  type   1 获取银行放款入账收款账户  2 获取渠道放款收款账户
     *
     * @apiParam {string}  bank_card_id   收款账户id
     * @apiParam {string}   bank  收款账户
     */
    public function paymentAccount() {
        try {
            if (input('type') == 1) {
                $bankData = Db::name('bank_card')->where(['type' => 1, 'delete_time' => null, 'status' => 1])->field('id bank_card_id,name')->select();
            } else {
                $bankData = Db::name('bank_card')->where(['type' => 3, 'delete_time' => null, 'status' => 1])->field('id bank_card_id,name')->select();
            }
            return $this->buildSuccess($bankData);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/addBankWater 增加银行入账流水[admin/Financial/addBankWater]
     * @apiVersion 1.0.0
     * @apiName addBankWater
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/addBankWater
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  finance_sn   财务序号
     * @apiParam {float}   loan_money  银行放款金额
     * @apiParam {int}  bank_card_id   收款账户id
     * @apiParam {string}  lender_object   放款银行
     * @apiParam {string}  receivable_account 收款账户
     * @apiParam {string}  into_money_time   到账时间
     * @apiParam {string}  remark   备注
     * @apiParam {int}   is_loan_finish  银行放款是否完成 0未完成 1已完成
     *
     */
    public function addBankWater() {
        $waterInfo['order_sn'] = input('order_sn');
        $waterInfo['finance_sn'] = input('finance_sn');
        $waterInfo['loan_money'] = input('loan_money');
        $waterInfo['total_money'] = input('loan_money');
        $waterInfo['bank_card_id'] = input('bank_card_id');
        $waterInfo['lender_object'] = input('lender_object');
        $waterInfo['receivable_account'] = input('receivable_account');
        $waterInfo['into_money_time'] = input('into_money_time');
        $waterInfo['remark'] = input('remark');
        //$is_loan_finish = input('is_loan_finish')?:0;
        //验证器验证参数
        $valiDate = validate('BankLending');
        if (!$valiDate->check($waterInfo)) {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
        }
        // 启动事务
        Db::startTrans();
        try {
            //更改担保赎楼信息表信息
            $guaranteeInfo = OrderGuarantee::get(['order_sn' => $waterInfo['order_sn']]);
            if ($guaranteeInfo->loan_money_status == 3)
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '银行放款已复核成功,不能再进行入账!');
            //判断银行放款入账金额不得大于担保金额
            $danBaoMoney = $guaranteeInfo['loan_money'] + $waterInfo['loan_money'];
            if ($danBaoMoney > $guaranteeInfo['money'])
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '银行放款金额不得大于担保金额，请确定后重新输入!');

            $waterInfo['create_time'] = time();
            $waterInfo['create_uid'] = $this->userInfo['id'];
            //添加银行放款入账流水
            OrderAccountRecord::create($waterInfo);
            $guaranteeInfo->loan_money = $guaranteeInfo['loan_money'] + $waterInfo['loan_money'];
            $guaranteeInfo->update_time = time();
            //$guaranteeInfo->is_loan_finish = $is_loan_finish;
            //if($is_loan_finish == 1) $guaranteeInfo->loan_money_status = 2;
            $guaranteeInfo->save();
            /* 添加订单操作记录 */
            //根据订单号查询出订单状态
            $stageInfo = Order::getOne(['order_sn' => $waterInfo['order_sn']], 'stage,type');
            if (strlen($stageInfo['stage']) == 4) {
                $operate = $stage = show_status_name($stageInfo['stage'], 'ORDER_JYDB_STATUS');
            } else {
                $operate = $stage = show_status_name($stageInfo['stage'], 'ORDER_JYDB_FINC_STATUS');
            }
            $operate_node = "银行放款入账";
            $operate_det = $this->userInfo['name'] . "添加入账流水";
            $operate_reason = '';
            $stage_code = $stageInfo['stage'];
            $operate_table = 'order';
            OrderComponents::addOrderLog($this->userInfo, $waterInfo['order_sn'], $stage, $operate_node, $operate, $operate_det, $operate_reason, $stage_code, $operate_table);
            // 提交事务
            Db::commit();
            return $this->buildSuccess('银行放款入账流水添加成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '银行放款入账流水添加失败' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/showBankLendDetail 银行放款入账流水明细[admin/Financial/showBankLendDetail]
     * @apiVersion 1.0.0
     * @apiName showBankLendDetail
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/showBankLendDetail
     *
     *
     * @apiParam {string}  order_sn   订单编号
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
     *       "code": 1,
     *       "msg": "操作成功",
     *        data": {
     *           "orderinfo": {
     *               "order_sn": "JYDB2018050137123456",
     *               "type": "JYDB",
     *               "name": "夏丽平",
     *               "deptname": "财务中心",
     *               "finance_sn": "100000048",
     *               "guarantee_money": "2.00",
     *               "loan_money": "180265.00",
     *               "is_loan_finish": 0,
     *               "loan_money_status": 1,
     *               "chuzhang_money": "4.00",
     *               "dp_redeem_bank": "农业"
     *             },
     *         "BankLendInfo": [
     *               {
     *               "loan_money": "56786.00",
     *               "lender_object": "中国银行",
     *               "receivable_account": "中国银行账户",
     *               "into_money_time": "2019-11-03",
     *               "remark": "法国红酒狂欢节",
     *               "operation_name": "杜欣"
     *               },
     *               {
     *               "loan_money": "123456.00",
     *               "lender_object": "中国银行",
     *               "receivable_account": "中国银行账户",
     *               "into_money_time": "2019-11-02",
     *               "remark": "啊是的范德萨",
     *               "operation_name": "杜欣"
     *               }
     *            ]
     *        }
     *   }
     *
     * @apiSuccess {string} order_sn    订单编号
     * @apiSuccess {string} type    订单类型
     * @apiSuccess {string} name    理财经理
     * @apiSuccess {string} deptname    所在部门
     * @apiSuccess {int}   finance_sn  财务序号
     * @apiSuccess {float} guarantee_money    担保金额
     * @apiSuccess {float} loan_money    实收金额总计(银行放款金额总计)
     * @apiSuccess {float} chuzhang_money    出账金额
     * @apiSuccess {int}   loan_money_status  入账状态 1待入账 2待复核 3已复核 4驳回待处理
     * @apiSuccess {int}   is_loan_finish  银行放款是否完成 0未完成 1已完成
     * @apiSuccess {string} dp_redeem_bank    放款银行(新增入账流水表单里面的放款银行)
     * @apiSuccess {string} lender_object    放款银行(流水明细)
     * @apiSuccess {int}   loan_money  放款金额
     * @apiSuccess {string} receivable_account    收款账户
     * @apiSuccess {string} into_money_time    到账时间
     * @apiSuccess {string}   remark  备注说明
     * @apiSuccess {string} operation_name    入账人员
     *
     */
    public function showBankLendDetail() {
        $orderSn = input('order_sn');
        if (empty($orderSn))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        try {
            $returnInfo = [];
            $orderInfo = Order::banksDetail($orderSn);
            $field = 'loan_money,lender_object,receivable_account,into_money_time,remark,create_uid';
            $booksWaterInfo = OrderAccountRecord::getAll(['order_sn' => $orderSn], $field, 'create_time desc');
            foreach ($booksWaterInfo as $k => $v) {
                $booksWaterInfo[$k]['operation_name'] = SystemUser::where(['id' => $v['create_uid']])->value('name');
                unset($booksWaterInfo[$k]['create_uid']);
            }
            $returnInfo['orderinfo'] = $orderInfo; //订单信息
            $returnInfo['BankLendInfo'] = $booksWaterInfo; //银行放款流水明细
            return $this->buildSuccess($returnInfo);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/editReview 银行放款入账复核[admin/Financial/editReview]
     * @apiVersion 1.0.0
     * @apiName editReview
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/editReview
     *
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  type   按钮区分  1 确认复核 2驳回
     *
     *
     */
    public function editReview() {
        $orderSn = input('order_sn');
        $type = input('type');
        if (empty($orderSn) || empty($type))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        $guaranteeInfo = OrderGuarantee::getOne(['order_sn' => $orderSn], 'is_loan_finish,loan_money_status');
        if ($guaranteeInfo['is_loan_finish'] != 1)
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '只有银行放款已经完成才能进行该操作!');
        try {
            if ($type == 1) {
                if ($guaranteeInfo['loan_money_status'] != 2)
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '只有银行放款入账状态为待复核，才能确认复核!');
                OrderGuarantee::where('order_sn', $orderSn)->update(['loan_money_status' => 3, 'loan_money_time' => date('Y-m-d', time())]);
                $msg = "复核成功";
            }elseif ($type == 2) {
                //驳回之前还得判断该订单是否已经派完单，排完单就不能驳回了
                if (Db::name('order_ransom_dispatch')->where('order_sn', $orderSn)->value('id'))
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '该订单已经派过单，不能进行驳回!');
                if ($guaranteeInfo['loan_money_status'] != 2)
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '只有银行放款入账状态为待复核，才能驳回!');
                OrderGuarantee::where('order_sn', $orderSn)->update(['loan_money_status' => 4, 'is_loan_finish' => 0]);
                $msg = "驳回成功";
            }
            /* 添加订单操作记录 */
            //根据订单号查询出订单状态
            $stageInfo = Order::getOne(['order_sn' => $orderSn], 'stage');
            if (strlen($stageInfo['stage']) == 4) {
                $operate = $stage = show_status_name($stageInfo['stage'], 'ORDER_JYDB_STATUS');
            } else {
                $operate = $stage = show_status_name($stageInfo['stage'], 'ORDER_JYDB_FINC_STATUS');
            }
            $operate_node = "银行放款入账审核";
            $operate_det = $this->userInfo['name'] . $msg;
            $operate_reason = '';
            $stage_code = $stageInfo['stage'];
            $operate_table = 'order';
            OrderComponents::addOrderLog($this->userInfo, $orderSn, $stage, $operate_node, $operate, $operate_det, $operate_reason, $stage_code, $operate_table);
            return $this->buildSuccess($msg);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '复核失败!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/instructionList 待发送指令列表[admin/Financial/instructionList]
     * @apiVersion 1.0.0
     * @apiName instructionList
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/instructionList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  instruct_status   指令状态（1待申请 2待发送 3已发送）
     * @apiParam {int}  is_lend   是否放款（1是 2否）
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
     *       "total": 2,
     *       "per_page": 20,
     *       "current_page": 1,
     *       "last_page": 1,
     *       "data": [
     *           {
     *           "order_sn": "JYDB2018050096",
     *           "finance_sn": "100000047",
     *           "type": "JYDB",
     *           "name": "夏丽平",
     *           "estate_name": "国际新城",
     *           "estate_owner": null,
     *           "instruct_status": 1,
     *           "is_loan_finish": 1
      "dp_redeem_bank": "工商银行",        赎楼短贷银行
      "dp_redeem_bank_branch": "深圳宝安支行",    赎楼短贷银行支行
      "organization": "中国银行-深圳腾龙支行",      赎楼银行
      "mortgage_sum": [                             所有的赎楼银行
      "中国银行-深圳腾龙支行",
      "中国银行-深圳腾龙支行"
      ]
     *           },
     *           {
     *           "order_sn": "JYDB2018050095",
     *           "finance_sn": "100000047",
     *           "type": "JYDB",
     *           "name": "夏丽平",
     *           "estate_name": "国际新城",
     *           "estate_owner": null,
     *           "instruct_status": 3,
     *           "is_loan_finish": 1,
     *           }
     *       ]
     *   }
     * @apiSuccess {int} total    总条数
     * @apiSuccess {int} per_page    每页显示的条数
     * @apiSuccess {int} current_page    当前页
     * @apiSuccess {int} last_page    总页数
     * @apiSuccess {string} order_sn    业务单号
     * @apiSuccess {int} finance_sn    财务序号
     * @apiSuccess {string} type     订单类型
     * @apiSuccess {string} name    理财经理
     * @apiSuccess {string} estate_name    房产名称
     * @apiSuccess {string} estate_owner    业主姓名
     * @apiSuccess {int} instruct_status    指令状态（1待申请 2待发送 3已发送）
     * @apiSuccess {int} is_loan_finish    是否放款  0否  1是
     */
    public function instructionList() {
        $res = $this->instructionListWhere(1);
        try {
            return $this->buildSuccess(Order::instructionList($res['map'], $res['page'], $res['pageSize']));
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/instructionHasList 已发送指令列表[admin/Financial/instructionHasList]
     * @apiVersion 1.0.0
     * @apiName instructionHasList
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/instructionHasList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  instruct_status   指令状态（1待申请 2待发送 3已发送）
     * @apiParam {int}  is_lend   是否放款（1是 2否）
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     */
    public function instructionHasList() {
        $res = $this->instructionListWhere(2);
        try {
            return $this->buildSuccess(Order::instructionList($res['map'], $res['page'], $res['pageSize']));
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /*
     * @author 赵光帅
     * 额度发送指令列表的条件
     * @Param {int}  $typeList   1 未发送列表  2 已发送列表
     * */

    protected function instructionListWhere($typeList) {
        $createUid = input('create_uid') ?: 0;
        $subordinates = input('subordinates') ?: 0;
        $type = input('type');
        $instruct_status = input('instruct_status') ?: 0;
        $is_lend = input('is_lend') ?: 0;
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : 10;
        $userId = $this->userInfo['id'];
        //$userId = 2;
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['x.financing_manager_id'] = ['in', $userStr];
        }
        $type && $map['x.type'] = $type;
        //$instruct_status && $map['n.instruct_status'] = $instruct_status;
        if ($typeList === 1) {   //未发送
            if (empty($instruct_status)) {
                $map['n.instruct_status'] = ['in', '1,2'];
            } else {
                $map['n.instruct_status'] = $instruct_status;
            }
        } else {     //已发送
            $map['n.instruct_status'] = 3;
        }
        if (!empty($is_lend) && $is_lend == 1) {
            $map['n.is_loan_finish'] = 1;
        } elseif (!empty($is_lend) && $is_lend == 2) {
            $map['n.is_loan_finish'] = 0;
        }
        $searchText && $map['y.estate_name|x.order_sn|y.estate_owner'] = ['like', "%{$searchText}%"];
        $map['x.delete_time'] = NULL;
        $map['x.status'] = 1;
        $map['x.stage'] = ['>', 1012];
        $map['x.type'] = 'JYDB';
        $map['n.is_instruct'] = 1;
        //$map['n.is_dispatch'] = ['<>',0];
        $map['n.guarantee_fee_status'] = 2;

        return ['map' => $map, 'page' => $page, 'pageSize' => $pageSize];
    }

    /**
     * @api {post} admin/Financial/foreclosureInfo 赎楼出账表信息[admin/Financial/foreclosureInfo]
     * @apiVersion 1.0.0
     * @apiName foreclosureInfo
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/foreclosureInfo
     *
     *
     * @apiParam {string}  order_sn   订单编号
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *  "data": {
      "basic_information": {                      基本信息
      "order_sn": "JYDB2018050137123456",    业务单号
      "type": "JYDB",        业务类型
      "finance_sn": "100000048",      财务序号
      "order_source": 1,
      "source_info": "万科地产",          订单来源
      "order_source_str": "合作中介",     来源机构
      "financing_manager_name": "夏丽平",    理财经理
      "dept_manager_name": "杜欣",           部门经理
      "deptname": "总经办"                   所属部门
      },
      "estate_info": [   房产信息
      {
      "estate_name": "国际新城一栋",                  房产名称
      "estate_region": "深圳市|罗湖区|桂园街道",      所属城区
      "estate_area": 70,                             房产面积
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
      "seller_info": [    买方信息(is_seller = 1 && is_comborrower = 0) 买方共同借款人(is_seller = 1 && is_comborrower = 1)
      {               卖方信息(is_seller = 2 && is_comborrower = 0) 卖方共同借款人(is_seller = 2 && is_comborrower = 1)
      "is_seller": 2,
      "is_comborrower": 0,           共同借款人属性 0借款人 1共同借款人
      "cname": "张三",                 卖方姓名
      "ctype": 1,                      卖方类型 1个人 2企业
      "certtype": 1,                   证件类型
      "certcode": "11111122322",       证件号码
      "mobile": "18825454079",         电话号码
      "is_guarantee": 0                 担保申请人 1是 0否
      "is_seller_str": "买方",
      "is_comborrower_str": "买方共同借款人",     所属角色
      "ctype_str": "个人",                      卖方类型
      "certtype_str": "身份证"                    证件类型
      },
      {
      "cname": "张三",
      "ctype": 1,
      "certtype": 1,
      "certcode": "11111122322",
      "mobile": "18825454079",
      "is_guarantee": 0
      }
      ],
      "sqk_info": {                               首期款信息
      "dp_strike_price": "5900000.00",             成交价格
      "dp_earnest_money": "80000.00",             定金金额
      "dp_supervise_guarantee": null,            担保公司监管
      "dp_supervise_buyer": null,                 买方本人监管
      "dp_supervise_bank": "建设银行",           监管银行
      "dp_supervise_bank_branch": null,
      "dp_supervise_date": "2018-04-24",         监管日期
      "dp_buy_way": "按揭购房",                  购房方式
      "dp_now_mortgage": "5.00"                  现按揭成数
      },
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
      ],
      "preliminary_question": [    风控初审问题汇总
      {
      "describe": "呵呵456",     问题描述
      "status": 0               是否解决  0未解决 1已经解决
      },
      {
      "describe": "呵呵帅那个帅789",
      "status": 0
      }
      ],
      "needing_attention": [   风控提醒注意事项
      {
      "process_name": "收到公司的",    来源
      "item": "啥打法是否"             注意事项
      },
      {
      "process_name": "测试",
      "item": "测试注意事项"
      }
      ],
      "reimbursement_info": [   银行账户信息
      {
      "bankaccount": "张三",   银行户名
      "accounttype": 1,        账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它 6公司个人账户
      "bankcard": "111111",    银行卡号
      "openbank": "中国银行"    开户银行
      "type": 2,
      "verify_card_status": 0,
      "type_text": "尾款卡",                  账号用途
      "verify_card_status_text": "已完成",    核卡状态
      "accounttype_str": "卖方"               账号归属
      },
      {
      "bankaccount": "李四",
      "accounttype": 5,
      "bankcard": "111",
      "openbank": "工商银行"
      }
      ],

      "advancemoney_info": [    垫资费计算
      {
      "advance_money": "650000.00",     垫资金额
      "advance_day": 30,               垫资天数
      "advance_rate": 0.5,             垫资费率
      "advance_fee": "97500.0",        垫资费
      "remark": null,                  备注
      "id": 115
      }
      ],

      "cost_account":{     预收费用信息 与 实际费用入账
      "ac_guarantee_fee": "1000.00",   实收担保费
      "ac_fee": "-15.00",              手续费
      "ac_self_financing": "30.00",    自筹金额(实际费用入账)
      "short_loan_interest": "-12.30",   短贷利息
      "return_money": "12.50",           赎楼返还款
      "default_interest": "0.00",        罚息
      "overdue_money": "0.00",           逾期费
      "exhibition_fee": "1000.00",      展期费
      "transfer_fee": "10000.00",       过账手续费
      "other_money": "0.00"             其他
      "notarization": "2018-07-26",     公正日期
      "money": "46465.00",              担保金额(额度类)  垫资金额(现金类)
      "project_money_date": null,       预计用款日
      "guarantee_per": 0.35,            担保成数(垫资成数)
      "guarantee_rate": 4,              担保费率
      "guarantee_fee": "225826007.64",  预收担保费
      "account_per": 41986.52,          出账成数
      "fee": "456456.00",               预收手续费
      "self_financing": "30.00",        自筹金额(预收费用信息)
      "info_fee": "0.00",               预计信息费
      "total_fee": "226282463.64",      预收费用合计
      "return_money_mode": null,
      "return_money_amount": 1425,      回款金额
      "turn_into_date": null,           存入日期
      "turn_back_date": null,           转回日期
      "chuzhangsummoney": 5645650191    预计出账总额
      "return_money_mode_str": "直接回款"   回款方式
      },
      "lend_books": [    银行放款入账
      {
      " loan_money": "56786.00",             放款金额
      "lender_object": "中国银行",           放款银行
      "receivable_account": "中国银行账户",    收款账户
      "into_money_time": "2019-11-03",        到账时间
      "remark": "法国红酒狂欢节",             备注说明
      "operation_name": "杜欣"                入账人员
      },
      {
      " loan_money": "123456.00",
      "lender_object": "中国银行",
      "receivable_account": "中国银行账户",
      "into_money_time": "2019-11-02",
      "remark": "啊是的范德萨",
      "operation_name": "杜欣"
      }
      ],
      "arrears_info": [    欠款及预计出账金额
      {
      "ransom_status_text": "已完成",       赎楼状态
      "ransomer": "朱碧莲",         赎楼员
      "organization": "银行",      欠款机构名称
      "interest_balance": "111111.11",    欠款金额
      "mortgage_type_name": "商业贷款",   欠款类型
      "accumulation_fund": "2.00"         预计出账金额
      }
      {
      "organization": "银行",
      "interest_balance": "111111.11",
      "mortgage_type_name": "公积金贷款",
      "accumulation_fund": "2.00"
      }
      ],
      "fund_channel": [                 资金渠道信息
      {
      "fund_channel_name": "华安",      资金渠道
      "money": "2000000.00",             申请金额
      "actual_account_money": "2000000.00",    实际入账金额
      "is_loan_finish": 1,
      "trust_contract_num": "25421155",         信托合同号
      "loan_day": 5                             借款天数
      }
      ]
      "status_info": {        各种需要用到的其他字段
      "guarantee_fee_status": 2,     （担保费）收费状态 1未收齐 2已收齐
      "loan_money_status": 1,         银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理
      "instruct_status": 3,           主表指令状态（1待申请 2待发送 3已发送）
      "is_loan_finish": 1,             银行放款是否完成 0未完成 1已完成
      "loan_money": "4200000.00",      银行放款入账(实收金额总计)  资金渠道信息(渠道实际入账总计)
      "com_loan_money": null,         公司放款金额
      "guarantee_fee": 14526          垫资费计算(垫资费总计)
      "is_comborrower_sell": 1       是否卖方有共同借款人 0否 1是
      "chile_instruct_status"  3     子单指令状态（资金渠道表指令状态）
      "is_dispatch": 1,               是否派单 0未派单 1已派单 2退回
      "endowmentsum": 120000          资金渠道信息(垫资总计)
      }
      }
     */
    public function foreclosureInfo() {
        $orderSn = input('order_sn');
        if (empty($orderSn))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        try {
            $returnInfo = [];
            //基本信息信息
            $resInfo = OrderComponents::orderJbInfo($orderSn);
            $returnInfo['basic_information'] = $resInfo;
            //房产信息
            $resInfo = OrderComponents::showEstateList($orderSn, 'estate_name,estate_region,estate_area,estate_certtype,estate_certnum,house_type', 'DB');
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
            if ($resInfo) {
                foreach ($resInfo as &$val) {
                    $val['estate_certtype_str'] = $newStageArr[$val['estate_certtype']] ? $newStageArr[$val['estate_certtype']] : '';
                    $val['house_type_str'] = $val['house_type'] == 1 ? "分户" : "分栋";
                }
            }
            $returnInfo['estate_info'] = $resInfo;
            //卖方信息(买方信息)
            $resInfo = OrderComponents::showCustomerInfo($orderSn, 'is_seller,is_comborrower,cname,ctype,certtype,certcode,mobile,is_guarantee');
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('CERTTYPE'));
            if ($resInfo) {
                foreach ($resInfo as &$val) {
                    $val['certtype_str'] = $newStageArr[$val['certtype']] ? $newStageArr[$val['certtype']] : '';
                    $val['is_guarantee_str'] = $val['is_guarantee'] == 1 ? '是' : '否';
                }
            }
            $returnInfo['seller_info'] = $resInfo;
            //实际出账收款账户
            //$returnInfo['collection_info'] = OrderComponents::showCollectionInfo($orderSn);
            //首期款信息
            $resInfo = OrderComponents::orderDp($orderSn, 'dp_strike_price,dp_earnest_money,dp_supervise_guarantee,dp_supervise_buyer,dp_buy_way,dp_now_mortgage');
            if (isset($resInfo)) {
                $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('PURCHASE_WAY'));
                if (!empty($resInfo['dp_buy_way'])) {
                    $resInfo['dp_buy_way'] = $newStageArr[$resInfo['dp_buy_way']] ? $newStageArr[$resInfo['dp_buy_way']] : '';
                } else {
                    $resInfo['dp_buy_way'] = '';
                }
            }
            $returnInfo['sqk_info'] = $resInfo;
            //回款方式信息
            $returnInfo['returnMoney'] = OrderComponents::orderReturnMoney($orderSn);
            //监管信息
            $returnInfo['dbBankInfo'] = OrderComponents::orderBankDp($orderSn, 'id,dp_supervise_date,dp_money,dp_organization_type,dp_supervise_bank,dp_supervise_bank_branch,dp_organization');
            //现按揭信息
            $resInfo = OrderComponents::showMortgage($orderSn, 'type,mortgage_type,money,organization_type,organization', 'NOW');
            $newMortgageArr = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_TYPE'));
            $newAgencyArr = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_AGENCY_TYPE '));
            if (!empty($resInfo)) {
                foreach ($resInfo as $k => $v) {
                    $resInfo[$k]['mortgage_type_str'] = $newMortgageArr[$v['mortgage_type']] ? $newMortgageArr[$v['mortgage_type']] : '';
                    $resInfo[$k]['organization_type_str'] = $newAgencyArr[$v['organization_type']] ? $newAgencyArr[$v['organization_type']] : '';
                }
            }
            $returnInfo['mortgage_info'] = $resInfo;

            //风控初审问题汇总
            $returnInfo['preliminary_question'] = OrderComponents::showPreliminary($orderSn);
            //风控提醒注意事项
            $returnInfo['needing_attention'] = OrderComponents::showNeedAtten($orderSn);
            //银行账户信息
            $resInfo = OrderComponents::showGuaranteeBank($orderSn, 'id,bankaccount,accounttype,bankcard,openbank,verify_card_status');
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('JYDB_ACCOUNT_TYPE'));
            if ($resInfo) {
                foreach ($resInfo as &$val) {
                    if (!empty($val['accounttype'])) {
                        $val['accounttype_str'] = $newStageArr[$val['accounttype']] ? $newStageArr[$val['accounttype']] : '';
                    }
                }
            }
            $returnInfo['reimbursement_info'] = $resInfo;
            //垫资费计算
            $returnInfo['advancemoney_info'] = OrderComponents::advanceMoney($orderSn);

            //预收费用 实际费用入账
            $returnInfo['cost_account'] = OrderComponents::showChargeList($orderSn);
            //银行放款入账
            $returnInfo['lend_books'] = OrderComponents::showBankList($orderSn);
            //欠款及预计出账金额 => 原按揭信息
            $returnInfo['arrears_info'] = OrderComponents::showArrearsInfo($orderSn, 'out_account,mortgage_type,organization,interest_balance', 'ORIGINAL');
            //查询出各种状态
            $returnInfo['status_info'] = OrderComponents::showStstusInfo($orderSn);
            //确认放款按钮是否展示
            $returnInfo['status_info']['is_show_button'] = self::isShowButton($returnInfo['status_info']);
            return $this->buildSuccess($returnInfo);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /*
     * 判断是否需要显示放款完成按钮
     * */

    private function isShowButton($statusInfo) {
        $arrSign = [];
        foreach ($this->userInfo['group'] as $k => $v) {
            $sign = Db::name('system_auth_group')->where(['id' => $v])->value('sign');
            $arrSign[] = $sign;
        }
        if (in_array('investment_advisor', $arrSign)) {
            $bool = true;
        } else {
            $bool = false;
        }
        if ($statusInfo['is_dispatch'] != 1 && $statusInfo['instruct_status'] == 3 && $statusInfo['is_loan_finish'] == 0 && $bool) {  //显示放款按钮
            return 1;
        } else {
            return 2;
        }
    }

    /**
     * @api {post} admin/Financial/instructionsSend 指令发送[admin/Financial/instructionsSend]
     * @apiVersion 1.0.0
     * @apiName instructionsSend
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/instructionsSend
     *
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  type   1申请发送 2撤回发送 3确认放款 4确认发送
     */
    public function instructionsSend() {
        $orderSn = input('order_sn');
        $type = input('type');
        if (empty($orderSn) || empty($type))
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');

        //担保费是否收齐 指令状态 银行放款入账状态
        $guaranteeInfo = OrderGuarantee::where('order_sn', $orderSn)->field('guarantee_fee_status,instruct_status,is_loan_finish,is_dispatch')->find();
        if ($type == 1) {
            //判断担保费是否已经收齐，未收齐则不能申请发送
            if ($guaranteeInfo['instruct_status'] != 1)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '指令状态为待申请，才能申请发送!');
            if ($guaranteeInfo['is_dispatch'] == 0 && $guaranteeInfo['guarantee_fee_status'] == 1)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '担保费未收齐,不能申请发送!');
        }elseif ($type == 2) {
            if ($guaranteeInfo['instruct_status'] != 3)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '指令状态为已发送，才能申请撤回!');
            if ($guaranteeInfo['is_loan_finish'] == 1)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '银行已经完成放款入账,不能申请撤回!');
        }elseif ($type == 3) {
            if ($guaranteeInfo['instruct_status'] != 3)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '指令状态为已发送，才能申请确认放款!');
            if ($guaranteeInfo['is_loan_finish'] == 1)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '银行放款已经完成,不能重复申请!');
        }elseif ($type == 4) {
            if ($guaranteeInfo['instruct_status'] != 2)
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '指令状态为待发送，才能申请确认发送!');
        }

        $sendInstructions = new \app\api\controller\AppFlow();
        $result = $sendInstructions->linesSendInstruct($orderSn, $type, $this->userInfo);
        return $result;
    }

    /**
     * @api {get} admin/Financial/exportFinanceList 财务费用已入账列表导出[admin/Financial/exportFinanceList]
     * @apiVersion 1.0.0
     * @apiName exportFinanceList
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/exportFinanceList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int} search_text    关键字搜索
     */
    public function exportFinanceList() {
        $createUid = input('create_uid') ?: 0;
        $subordinates = input('subordinates') ?: 0;
        $type = input('type');
        $startTime = strtotime(input('start_time'));
        $endTime = strtotime(input('end_time'));
        $searchText = $this->request->Get('search_text', '', 'trim');
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['x.financing_manager_id'] = ['in', $userStr];
        }
        if ($startTime && $endTime) {
            if ($startTime > $endTime) {
                $startTime = $startTime + 86400;
                $map['n.ac_guarantee_fee_time'] = array(array('egt', $endTime), array('elt', $startTime));
            } else {
                $endTime = $endTime + 86400;
                $map['n.ac_guarantee_fee_time'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        } elseif ($startTime) {
            $map['n.ac_guarantee_fee_time'] = ['egt', $startTime];
        } elseif ($endTime) {
            $endTime = $endTime + 86400;
            $map['n.ac_guarantee_fee_time'] = ['elt', $endTime];
        }
        $type && $map['x.type'] = $type;
        $searchText && $map['y.estate_name|x.order_sn|x.finance_sn'] = ['like', "%{$searchText}%"];
        //if(empty($map)) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '请选择导出的条件!');

        $map['x.delete_time'] = NULL;
        $map['x.status'] = 1;
        $map['n.guarantee_fee_status'] = 2;
        try {
            $spreadsheet = new Spreadsheet();
            $resInfo = Order::exportFinanceList($map);
            $head = ['0' => '序号', '1' => '业务单号', '2' => '财务序号', '3' => '订单类型', '4' => '房产名称',
                '5' => '业主姓名', '6' => '买方姓名', '7' => '理财经理', '8' => '所在部门',
                '9' => '应收金额', '10' => '实收金额', '11' => '担保费',
                '12' => '手续费', '13' => '自筹金额', '14' => '短贷利息',
                '15' => '赎楼返还款', '16' => '罚息', '17' => '逾期费',
                '18' => '展期费', '19' => '过账手续费',
                '20' => '保证金', '21' => '其他', '22' => '业务来源',
                '23' => '来源机构', '24' => '入账时间'];
            array_unshift($resInfo, $head);
            //$fileName = iconv("UTF-8", "GB2312//IGNORE", '财务费用已入账' . date('Y-m-dHis'));
            $fileName = '' . date('Y-m-dHis');
            $spreadsheet->getActiveSheet()->fromArray($resInfo);
            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $Path = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'download' . DS . date('Ymd');
            if (!file_exists($Path)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($Path, 0700);
            }
            $pathName = $Path . DS . $fileName . '.Xlsx';
            $objWriter->save($pathName);
            $retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS . iconv("GB2312", "UTF-8", $fileName) . '.Xlsx';
            return $this->buildSuccess(['url' => $retuurl]);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '导出失败!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Financial/exportInstructionHas 导出已发送指令列表[admin/Financial/exportInstructionHas]
     * @apiVersion 1.0.0
     * @apiName exportInstructionHas
     * @apiGroup Financial
     * @apiSampleRequest admin/Financial/exportInstructionHas
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  is_lend   是否放款（1是 2否）
     * @apiParam {int} search_text    关键字搜索
     */
    public function exportInstructionHas() {
        $createUid = $this->request->post('create_uid',0);
        $subordinates = $this->request->post('subordinates',0);
        $type = $this->request->post('type');
        //$instruct_status = input('instruct_status')?:0;
        $is_lend = $this->request->post('is_lend',0);
        $searchText = $this->request->post('search_text','trim');
        $userId = $this->userInfo['id'];
        //$userId = 2;
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['x.financing_manager_id'] = ['in', $userStr];
        }
        $uid = $this->userInfo['id'];
        if (empty($uid))
            return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
        $type && $map['x.type'] = $type;
        //$instruct_status && $map['n.instruct_status'] = $instruct_status;
        $map['n.instruct_status'] = 3;
        if (!empty($is_lend) && $is_lend == 1) {
            $map['n.is_loan_finish'] = 1;
        } elseif (!empty($is_lend) && $is_lend == 2) {
            $map['n.is_loan_finish'] = 0;
        }
        $searchText && $map['y.estate_name|x.order_sn|x.finance_sn'] = ['like', "%{$searchText}%"];
        $map['x.delete_time'] = NULL;
        $map['x.status'] = 1;
        $map['x.stage'] = ['>', 1012];
        $map['x.type'] = 'JYDB';
        $map['n.is_instruct'] = 1;
        //$map['n.is_dispatch'] = ['<>',0];
        $map['n.guarantee_fee_status'] = 2;
        $list = Order::instructionHasList($map);
        //return json($list);
        try {
            $spreadsheet = new Spreadsheet();
            $resInfo = $list;
            $head = ['0' => '序号', '1' => '业务单号', '2' => '房产名称', '3' => '房产证号', '4' => '卖方',
                '5' => '卖方共同借款人', '6' => '买方', '7' => '买方共同借款人', '8' => '担保金额/元', '9' => '预计出账总计', '10' => '现按揭总计/元', '11' => '放款银行', '12' => '赎楼银行', '13' => '部门', '14' => '理财经理', '15' => '指令状态'];
            array_unshift($resInfo, $head);
            //$fileName = iconv("UTF-8", "GB2312//IGNORE", '已发送指令(额度)列表' . date('Y-m-dHis'));
            $fileName = '' . date('Y-m-dHis');//InstructionHas
            //$fileName = '已发送指令列表'.date('Y-m-d').mt_rand(1111,9999);

            $spreadsheet->getActiveSheet()->fromArray($resInfo);
            $spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true)->setName('Arial')->setSize(12);
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
            $worksheet = $spreadsheet->getActiveSheet();
            $styleArray = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $worksheet->getStyle('A1:P1')->applyFromArray($styleArray);
            // $spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $Path = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'download' . DS . date('Ymd');
            if (!file_exists($Path)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($Path, 0700);
            }
            $pathName = $Path . DS . $fileName . '.Xlsx';
            $objWriter->save($pathName);
            $retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS . iconv("GB2312", "UTF-8", $fileName) . '.Xlsx';
            return $this->buildSuccess(['url' => $retuurl]);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '导出失败!' . $e->getMessage());
        }
    }

}
