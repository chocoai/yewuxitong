<?php

/* 财务回款 */

namespace app\admin\controller;

use app\model\OrderOtherExhibition;
use think\Db;
use think\Loader;
use think\Request;
use app\model\Order;
use app\model\OrderGuarantee;
use app\model\OrderGuaranteeBank;
use app\model\SystemUser;
use app\util\FinancialBack;
use app\util\ReturnCode;
use app\model\OrderRansomReturn;
use app\model\OrderAttachment;
use app\model\Dictionary;
use app\model\Attachment;
use app\model\OrderOther;
use app\model\OrderCollectFee;
use  app\model\OrderCostDetail;
use app\model\OrderRansomOut;
use app\util\OrderComponents;
use app\admin\service\PayBackService;

class FinancialReceipts extends Base
{

    private $order;
    private $orderguarantee;
    private $orderguaranteebank;
    private $systemuser;
    private $orderransomreturn;
    private $orderattac;
    private $dictionary;
    private $attachment;

    public function _initialize()
    {
        parent::_initialize();
        $this->order = new Order();
        $this->orderguarantee = new OrderGuarantee();
        $this->orderguaranteebank = new OrderGuaranteeBank();
        $this->systemuser = new SystemUser();
        $this->orderransomreturn = new OrderRansomReturn();
        $this->orderattac = new OrderAttachment();
        $this->dictionary = new Dictionary();
        $this->attachment = new Attachment();
    }

    /**
     * @api {get} admin/FinancialReceipts/index 回款管理列表[admin/FinancialReceipts/index]
     * @apiVersion 1.0.0
     * @apiName index
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/index
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {string} start_time    开始时间
     * @apiParam {string} end_time    结束时间
     * @apiParam {int} return_money_status   回款状态（1回款待完成 2回款完成待复核 3回款完成待核算 4回款已完成） 数据字典类型 PAYBACK_STATUS
     * @apiParam {int} city     城市
     * @apiParam {int} district     城区
     * @apiParam {int} keywords     关键词
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {Object}  list       回款管理列表.
     * @apiSuccess {string} list.finance_sn    财务序号
     * @apiSuccess {string} list.order_sn    业务单号
     * @apiSuccess {string} list.estate_name    房产名称
     * @apiSuccess {string} list.estate_owner    业主姓名
     * @apiSuccess {string} list.return_money_amount    应收回款金额
     * @apiSuccess {string} list.return_money    已收回款金额
     * @apiSuccess {string} list.return_time    回款到账时间
     * @apiSuccess {string} list.return_money_status_text    回款状态
     * @apiSuccess {string} list.financing_manager    理财经理
     * @apiSuccess {int} count    总条数
     */
    public function index(Request $request)
    {
        $create_uid = $request->get('create_uid', '');
        $subordinates = $request->get('subordinates', 0);
        $page = $request->get('page', 1);
        $pageSize = $request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $return_money_status = $request->get('return_money_status', '');
        $city = $request->get('city', '');
        $district = $request->get('district', '');
        $keywords = $request->get('keywords', '');

        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        if ($start_time && $end_time) {
            $start_time_stamp = strtotime($start_time);
            $end_time_stamp = strtotime($end_time);
            if ($start_time_stamp > $end_time_stamp) {
                $map['r.return_time'] = ['between', [$end_time, $start_time]];
            } else {
                $map['r.return_time'] = ['between', [$start_time, $end_time]];
            }
        } else {
            $start_time && $map['r.return_time'] = ['egt', $start_time];
            $end_time && $map['r.return_time'] = ['elt', $end_time];
        }
        $where = [];
        if ($create_uid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($create_uid);
            } else {
                $userStr = $create_uid;
            }
            $where['x.financing_manager_id'] = ['in', $userStr];
        }
        $city && $where['e.estate_ecity'] = $city;
        $district && $where['e.estate_district'] = $district;
        $keywords && $where['x.order_sn|e.estate_name|x.finance_sn|e.estate_owner'] = ['like', "%{$keywords}%"];
        $where['x.status'] = 1;
        $field = "x.id,x.finance_sn,x.order_sn,x.return_money_status,x.financing_manager_id,x.type,e.estate_name,e.estate_owner";
        $queryModel = Order::alias('x')
            ->join('__ESTATE__ e', 'e.order_sn=x.order_sn', 'left')
            ->join('order_ransom_return r', 'r.order_sn = x.order_sn', 'left')
            ->where('x.type', 'in', 'JYXJ,TMXJ,PDXJ,DQJK,SQDZ,GMDZ')//现金业务
            ->where($where)
            ->where("e.estate_usage = 'DB' or e.estate_usage is null")
            ->where('x.return_money_status', $return_money_status ? $return_money_status : 'not null');
        if (isset($map)) {
            $map['r.status'] = 1;
            $queryModel->where($map)->field('max(r.return_time) as return_time');
        }
        $orderList = $queryModel
            ->field($field)
            ->order('x.create_time', 'DESC')
            ->group('x.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        foreach ($orderList['data'] as &$item) {
            $item['financing_manager'] = SystemUser::where('id', $item['financing_manager_id'])->value('name');
            $item['return_money_status_text'] = Order::$returnMoneyStatusMap[$item['return_money_status']];
            $where = [
                'order_sn' => $item['order_sn'],
                'status' => 1
            ];
            // 已收回款金额
            $item['return_money'] = OrderRansomReturn::getReturnMoney(['order_sn' => $item['order_sn']]);
            //应收回款金额
            $item['return_money_amount'] = OrderRansomOut::getReturnMoneyAmount($item['order_sn']);
            if (!isset($map)) {
                $item['return_time'] = OrderRansomReturn::where($where)->order('return_time desc')->value('return_time');
            }
        }
        return $this->buildSuccess([
            'count' => $orderList['total'],
            'list' => $orderList['data']
        ]);
    }

    /**
     * @api {get} admin/FinancialReceipts/collectList 费用信息列表[admin/FinancialReceipts/collectList]
     * @apiVersion 1.0.0
     * @apiName collectList
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/collectList
     *
     * @apiParam {date} start_time    开始时间
     * @apiParam {date} end_time    结束时间
     * @apiParam {string} order_sn    订单编号
     * @apiParam {number} type    费用类型 1正常担保 2展期 3逾期 数据字典类型 PAYBACK_FEE_TYPE
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {Object}  list       费用信息列表.
     * @apiSuccess {string} list.cal_date    计算费用日期
     * @apiSuccess {string} list.wait_money    待收回款金额
     * @apiSuccess {string} list.calc_total_money    计算费用总额
     * @apiSuccess {string} list.type    费用类型
     * @apiSuccess {string} list.rate    计算费率%
     * @apiSuccess {string} list.money    当日费用
     * @apiSuccess {string} list.act_calc_total_amont    实际计算费用总和
     * @apiSuccess {string} list.receive_total_amount    已收计算费用总和
     * @apiSuccess {string} list.less_calc_total_amont    剩余计算费用总和
     * @apiSuccess {string} list.remark    备注
     * @apiSuccess {int} count    总条数
     */
    //@author: bordon
    public function collectList(Request $request)
    {
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $type = $request->get('type', '');
        $order_sn = $request->get('order_sn');
        if (!$order_sn) {
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '订单编号不能为空');
        }
        $page = $request->get('page', 1);
        $pageSize = $request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $where['order_sn'] = $order_sn;
        if ($start_time && $end_time) {
            $start_time_stamp = strtotime($start_time);
            $end_time_stamp = strtotime($end_time);
            if ($start_time_stamp > $end_time_stamp) {
                $where['cal_date'] = ['between', [$end_time, $start_time]];
            } else {
                $where['cal_date'] = ['between', [$start_time, $end_time]];
            }
        } else {
            $start_time && $where['cal_date'] = ['egt', $start_time];
            $end_time && $where['cal_date'] = ['elt', $end_time];
        }
        $type && $where['type'] = $type;
        $field = 'id,cal_date,wait_money,type,rate,money,remark,cal_money as calc_total_money';
        $list = OrderCollectFee::getList($where, $field, ['page' => $page, 'list_rows' => $pageSize]);
        foreach ($list['data'] as &$item) {
            // 今日已收回款金额
//            $return_today_money = OrderRansomReturn::getReturnMoney(['order_sn' => $order_sn, 'return_time' => $item['cal_date']]);
//            // 计算费用总额 = 待收回款金额+今日已收回款金额（回款入账复核通过后计入回款金额）
//            $item['calc_total_money'] = $return_today_money + $item['wait_money'];
            // 实际计算费用总和 = 正常担保费+实际展期费+实际逾期费
            $item['act_calc_total_amont'] = OrderCollectFee::where(['order_sn' => $order_sn])
                ->where('cal_date', '<= time', $item['cal_date'])
                ->sum('money');
            //已收计算费用总和 = 实收担保费-预计信息费+已收展期费+已收逾期费
            $orderTee = OrderGuarantee::where('order_sn', $order_sn)->field('ac_guarantee_fee,info_fee,ac_exhibition_fee,ac_overdue_money')->find();
            $item['receive_total_amount'] = $orderTee['ac_guarantee_fee'] - $orderTee['info_fee'] + $orderTee['ac_exhibition_fee'] + $orderTee['ac_overdue_money'];
            //剩余计算费用总和 = 已收费用总和-实际产生费用总和
            $item['less_calc_total_amont'] = $item['receive_total_amount'] - $item['act_calc_total_amont'];
        }
        return $this->buildSuccess([
            'count' => $list['total'],
            'list' => $list['data']
        ]);
    }

    /**
     * @api {get} admin/FinancialReceipts/extensionList 展期信息列表[admin/FinancialReceipts/extensionList]
     * @apiVersion 1.0.0
     * @apiName extensionList
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/extensionList
     *
     * @apiParam {string} order_sn    订单编号
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {Object}  list       展期信息列表.
     * @apiSuccess {string} list.return_money    待收回款金额
     * @apiSuccess {string} list.exhibition_starttime    展期开始时间
     * @apiSuccess {string} list.exhibition_endtime    展期合同结束时间
     * @apiSuccess {string} list.actual_exhibition_endtime    展期实际结束时间
     * @apiSuccess {string} list.actual_exhibition_day    实际展期天数
     * @apiSuccess {string} list.exhibition_rate    展期费率
     * @apiSuccess {string} list.total_money    实际展期费
     * @apiSuccess {string} list.money    已收展期费  // 展期应交金额
     * @apiSuccess {string} list.create_user    展期申请人
     * @apiSuccess {Object}  extension      展期信息列表.
     * @apiSuccess {Number} extension.exten_time    展期次数
     * @apiSuccess {Number} extension.exten_days    实际展期总天数
     * @apiSuccess {string} extension.exten_total_money    实际展期费总额
     * @apiSuccess {string} extension.exten_receive_money     已收展期费总额
     * @apiSuccess {int} count    总条数
     */
    //@author: bordon
    //实际展期费=待收回款金额*展期费率
    public function extensionList(Request $request)
    {
        $order_sn = $request->get('order_sn', '', 'trim');
        if (!$order_sn) {
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '订单编号不能为空');
        }
        $where['ot.order_sn'] = $order_sn;
        $where['ot.process_type'] = 'EXHIBITION';
        $where['ot.stage'] = '308';
        $where['ot.status'] = 1;
        $page = $request->get('page', 1);
        $pageSize = $request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $field = 'ot.money,ot.return_money,ot.create_uid,ot.order_sn,
        oe.exhibition_guarantee_fee,
        oe.exhibition_info_fee,
        oe.exhibition_starttime,
        oe.exhibition_endtime,
        oe.actual_exhibition_endtime,
        oe.actual_exhibition_day,
        oe.exhibition_rate,oe.exhibition_fee,oe.exhibition_day,su.name as create_user';
        $list = OrderOther::getExtenList($where, $field, ['page' => $page, 'list_rows' => $pageSize]);
        $extension = OrderOther::getExtenInfo($where);
        $extension[0]['exten_days'] = !is_null($extension[0]['exten_days']) ? $extension[0]['exten_days'] : 0;
        $map = [
            'order_sn' => $order_sn,
            'type' => 2,
            'status' => 1,
            'create_uid' => -1
        ];
        foreach ($extension as &$item) {
            $item->exten_days = !is_null($item->exten_days) ? $item->exten_days : 0;
            $item->exten_total_money = OrderCollectFee::where($map)->sum('money');
            $item->exten_receive_money = $item->exten_receive_money + array_sum(array_column($list['data'], 'exhibition_guarantee_fee')) + array_sum(array_column($list['data'], 'exhibition_info_fee'));
        }
        return $this->buildSuccess([
            'count' => $list['total'],
            'list' => $list['data'],
            'extensionInfo' => $extension[0]
        ]);
    }

    /**
     * @api {post} admin/FinancialReceipts/finishPayback 回款完成 [admin/FinancialReceipts/finishPayback]
     * @apiVersion 1.0.0
     * @apiName finishPayback
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/finishPayback
     *
     * @apiParam {string} order_sn    订单编号
     * @apiParam {string} type    pending|success  类型
     *
     * @apiSuccess {number} code    1可以回款完成 -1 不能完成回款
     * @apiSuccess {string} msg    提示信息
     *
     */
    public function finishPayback(Request $request, PayBackService $payBackService)
    {
        $order_sn = $request->post('order_sn');
        $type = $request->post('type', 'pending');
        if (!$order_sn || !$type) {
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数错误');
        }
        $order = Order::where('order_sn', $order_sn)->findOrFail();
        if (!$order->return_money_status || $order->return_money_status != 1) {
            return $this->buildFailed(ReturnCode::UNKNOWN, '请勿异常操作！');
        }
        $result = $payBackService->checkfinishPayback($order_sn);
        if ($type == 'pending') {
            return $this->buildSuccess($result);
        }
        $msg = '下一节点为：' . Order::$returnMoneyStatusMap[2];
        Db::startTrans();
        try {
            $order->return_money_status = 2;
            $order->save();
            $user = $this->getUser();
            OrderComponents::addOrderLog($user,
                $order_sn,
                show_status_name($order->stage, 'ORDER_JYDB_STATUS'),
                '回款完成审批：通过',
                Order::$returnMoneyStatusMap[1],
                $msg,
                $result['msg'],
                $order->stage,
                'order',
                $order->id,
                'RETURN_MONEY');
            Db::commit();
            return $this->buildSuccess();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '操作失败' . $e->getMessage());
        }
    }

    /**
     * @author: bordon
     */
    /**
     * @api {post} admin/FinancialReceipts/payBackRecheck 回款完成待复核|回款完成待核算 [admin/FinancialReceipts/payBackRecheck]
     * @apiVersion 1.0.0
     * @apiName payBackRecheck
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/payBackRecheck
     *
     * @apiParam {string} order_sn    订单编号
     * @apiParam {string} type     审批意见  1:通过 2:驳回
     * @apiParam {string} reason    原因
     *
     */
    public function payBackRecheck(Request $request)
    {
        $data = $request->post('', '', 'trim');
        $rule = [
            ['id', 'require', '参数错误'],
            ['order_sn', 'require', '参数错误'],
            ['type', 'require', '参数错误'],
            ['reason', 'requireIf:type,0', '原因不能为空']
        ];
        $result = $this->validate($data, $rule);
        if (true !== $result) {
            // 验证失败 输出错误信息
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $result);
        }
        Db::startTrans();
        try {
            $order = Order::where('order_sn', $data['order_sn'])->find();
            if ($data['type'] == 1) {
                if ($order->return_money_status == 2) {
                    $return_money_status = 3;
                }
                if ($order->return_money_status == 3) {
                    $return_money_status = 4;
                    $this->updateExtend($order);
                }
            } else {
                if ($order->return_money_status == 2) {
                    $return_money_status = 1;
                }
                if ($order->return_money_status == 3) {
                    $return_money_status = 2;
                }
            }
            $msg = '下一节点为：' . Order::$returnMoneyStatusMap[$return_money_status];
            $order->return_money_status = $return_money_status;
            $order->save();
            $user = $this->getUser();
            OrderComponents::addOrderLog($user,
                $data['order_sn'],
                show_status_name($order->stage, 'ORDER_JYDB_STATUS'),
                '回款完成审批：' . $this->getType($data['type']),
                Order::$returnMoneyStatusMap[$return_money_status],
                $msg,
                $data['reason'],
                $order->stage,
                'order',
                $order->id,
                'RETURN_MONEY');
            Db::commit();
            return $this->buildSuccess();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '操作失败' . $e->getMessage());
        }
    }

    /**结束展期
     * @param Order $order
     * @throws \think\exception\DbException
     * @author: bordon
     */
    private function updateExtend(Order $order)
    {
        $result = OrderOther::alias('o')
            ->join('order_other_exhibition e', 'e.order_other_id = o.id')
            ->where(['o.order_sn' => $order->order_sn, 'o.process_type' => 'EXHIBITION', 'o.status' => 1, 'o.stage' => 308])
            ->where('e.actual_exhibition_endtime is null')
            ->field('e.*')
            ->select();
        foreach ($result as $item) {
            $today = date('Y-m-d', time());
            //展期实际结束时间
            $data['actual_exhibition_endtime'] = $today;
            //实际展期天数
            $data['actual_exhibition_day'] = $this->diffInDays($item->exhibition_starttime, $today);
            $data['actual_exhibition_update_time'] = time();
            $res = OrderOtherExhibition::where('order_other_id', $item->order_other_id)->update($data);
            if (!$res) {
                throw new \Exception('操作失败');
            }
        }
    }

    private function diffInDays($begin_time, $end_time)
    {
        return (strtotime($end_time) - strtotime($begin_time)) / 86400;
    }

    /**
     * @api {get} admin/FinancialReceipts/payBackList 回款入账审核列表[admin/FinancialReceipts/payBackList]
     * @apiVersion 1.0.0
     * @apiName payBackList
     * @apiGroup FinancialReceipts
     * @apiSampleRequest  admin/FinancialReceipts/payBackList
     *
     * @apiParam {string} start_time    开始时间
     * @apiParam {string} end_time    结束时间
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} return_money_status   回款状态 1回款入账待复核 2回款入账待核算 3回款入账已完成 4驳回待处理 -1已作废
     * @apiParam {int} keywords     关键词
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {Object}  list       回款入账审核列表.
     * @apiSuccess {string} list.finance_sn    财务序号
     * @apiSuccess {string} list.order_sn    业务单号
     * @apiSuccess {string} list.estate_name    房产名称
     * @apiSuccess {string} list.bank_account    回款户名
     * @apiSuccess {string} list.bank_card    回款账号
     * @apiSuccess {string} list.bank    回款银行
     * @apiSuccess {string} list.return_money_amount    应收回款金额
     * @apiSuccess {string} list.return_money    已收回款金额
     * @apiSuccess {string} list.money    本次回款金额
     * @apiSuccess {string} list.return_time    本次回款到账时间
     * @apiSuccess {string} list.ac_return_time    实际回款时间
     * @apiSuccess {string} list.return_money_status_text    回款入账状态
     * @apiSuccess {string} list.financing_manager    理财经理
     * @apiSuccess {int} count    总条数
     */
    public function payBackList(Request $request)
    {
        $create_uid = $request->get('create_uid', '');
        $subordinates = $request->get('subordinates', 0);
        $page = $request->get('page', 1);
        $pageSize = $request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $return_money_status = $request->get('return_money_status', '');
        $keywords = $request->get('keywords', '');

        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $where = [];
        $start_time_stamp = strtotime($start_time);
        $end_time_stamp = strtotime($end_time);
        if ($start_time_stamp && $end_time_stamp) {
            if ($start_time_stamp > $end_time_stamp) {
                $where['ort.return_time'] = ['between', [$end_time, $start_time]];
            } else {
                $where['ort.return_time'] = ['between', [$start_time, $end_time]];
            }
        } else {
            $start_time && $where['ort.return_time'] = ['egt', $start_time];
            $end_time && $where['ort.return_time'] = ['elt', $end_time];
        }
        if ($create_uid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($create_uid);
            } else {
                $userStr = $create_uid;
            }
            $where['x.financing_manager_id'] = ['in', $userStr];
        }
        $keywords && $where['x.order_sn|e.estate_name|x.finance_sn'] = ['like', "%{$keywords}%"];
        $where['x.status'] = 1;
        $where['ort.status'] = 1;
        $field = "ort.id,x.finance_sn,x.order_sn,x.financing_manager_id,
        x.type,e.estate_name,g.return_money_amount,
        ort.bank_account,ort.bank_card,ort.bank,ort.money,ort.return_money_into_status,ort.return_time,ort.ac_return_time";

        $orderList = OrderRansomReturn::alias('ort')
            ->join('order x', 'x.order_sn=ort.order_sn')
            ->join('__ESTATE__ e', 'e.order_sn=ort.order_sn', 'left')
            ->join('__ORDER_GUARANTEE__ g', 'g.order_sn=ort.order_sn')
            ->where('x.type', 'in', 'JYXJ,TMXJ,PDXJ,DQJK,SQDZ,GMDZ')//现金业务
            ->where($where)
            ->where("e.estate_usage = 'DB' or e.estate_usage is null")
            ->where($return_money_status ? "ort.return_money_into_status in ($return_money_status)" : 'ort.return_money_into_status is not null')
            ->field($field)
            ->order('ort.create_time', 'DESC')
            ->group('ort.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        foreach ($orderList['data'] as &$item) {
            $item['financing_manager'] = SystemUser::where('id', $item['financing_manager_id'])->value('name');
            $item['return_money_status_text'] = OrderRansomReturn::$intoStatusMap[$item['return_money_into_status']];
            // 已收回款金额
            $item['return_money'] = OrderRansomReturn::getReturnMoney(['order_sn' => $item['order_sn']]);
            //应收回款金额
            $item['return_money_amount'] = OrderRansomOut::getReturnMoneyAmount($item['order_sn']);
        }
        return $this->buildSuccess([
            'count' => $orderList['total'],
            'list' => $orderList['data']
        ]);
    }

//@author: bordon

    /**
     * @api {post} admin/FinancialReceipts/payBackEnterRecheck 回款入账复核 | 回款入账核算| 作废 [admin/FinancialReceipts/payBackEnterRecheck]
     * @apiVersion 1.0.0
     * @apiName payBackEnterRecheck
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/payBackEnterRecheck
     *
     * @apiParam {id} id    入账id
     * @apiParam {string} order_sn    订单编号
     * @apiParam {string} type    审批意见  1:通过 2:驳回 3:作废
     * @apiParam {string} reason    原因
     *
     */
    public function payBackEnterRecheck(Request $request)
    {
        $data = $request->post();
        $rule = [
            ['id', 'require', '参数错误'],
            ['order_sn', 'require', '参数错误'],
            ['type', 'require', '参数错误'],
            ['reason', 'requireIf:type,2', '原因不能为空']
        ];
        $result = $this->validate($data, $rule);
        if (true !== $result) {
            // 验证失败 输出错误信息
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $result);
        }
        $OrderRansom = OrderRansomReturn::where('order_sn', $data['order_sn'])->findOrFail($data['id']);
        $orderTee = OrderGuarantee::where('order_sn', $data['order_sn'])->field('id,out_account_com_total')->find();
        $ext_desc = '';  // 驳回金额变动描述内容
        Db::startTrans();
        try {
//            通过
            if ($data['type'] == 1) {
                if ($OrderRansom->return_money_into_status == 1) {
                    $return_money_into_status = 2;
                    if ($OrderRansom->is_rebut == 1) {  // 回款入账待核算驳回后重新审核
                        $less_money = $OrderRansom->money - $OrderRansom->rebut_oldmoney;
                        if ($less_money > 0) {   // 金额变动大于0
                            $orderTee->setDec('out_account_com_total', $less_money);
                        } elseif ($less_money < 0) {   // 金额变动小于0
                            $orderTee->setInc('out_account_com_total', abs($less_money));
                        }
                        $rebut_oldmoney = format_money($OrderRansom->rebut_oldmoney);
                        $money = format_money($OrderRansom->money);
                        $ext_desc .= "原入账金额：{$rebut_oldmoney}元，现入账金额：{$money}元，";
                        $less_money = format_money($less_money);
                        $ext_desc .= "入账金额变动：{$less_money}元";
                    } else {
                        $orderTee->setDec('out_account_com_total', $OrderRansom->money);
                    }
                    $OrderRansom->is_rebut = 0;
                }
                // 回款入账待核算
                if ($OrderRansom->return_money_into_status == 2) {
                    $return_money_into_status = 3;
                }
                $orderCost = $this->addOrderCost($OrderRansom, OrderRansomReturn::$intoStatusMap[$return_money_into_status], 1);
                //驳回
            } else if ($data['type'] == 2) {
                // 回款入账待复核
                if ($OrderRansom->return_money_into_status == 1) {
                    $return_money_into_status = 4;
                    if ($OrderRansom->is_rebut == 1) {  // 回款入账待核算驳回后重新审核
                        $orderCost = $this->addOrderCost($OrderRansom, OrderRansomReturn::$intoStatusMap[$return_money_into_status], 1);
                    }
                }
                // 回款入账待核算
                if ($OrderRansom->return_money_into_status == 2) {
                    $OrderRansom->rebut_oldmoney = $OrderRansom->money;  // 存储之前金额
                    $OrderRansom->is_rebut = 1;
                    $return_money_into_status = 4;
                    $orderCost = $this->addOrderCost($OrderRansom, OrderRansomReturn::$intoStatusMap[$return_money_into_status], 1);
                }
                // 作废
            } else {
                if ($OrderRansom->return_money_into_status == 4) {
                    $orderTee->setDec('out_account_com_total', $OrderRansom->money);
                    $return_money_into_status = -1;
                    $orderCost = $this->addOrderCost($OrderRansom, OrderRansomReturn::$intoStatusMap[$return_money_into_status], 3);
                } else {
                    throw new \Exception('数据异常');
                }
            }

            $user = $this->getUser();
            $msg = '下一节点为：' . OrderRansomReturn::$intoStatusMap[$return_money_into_status];
            $order = Order::where('order_sn', $data['order_sn'])->find();
            OrderComponents::addOrderLog($user,
                $data['order_sn'],
                show_status_name($order->stage, 'ORDER_JYDB_STATUS'),
                '回款入账审批：' . $this->getType($data['type']),
                OrderRansomReturn::$intoStatusMap[$OrderRansom->return_money_into_status],
                $msg . '  ' . $ext_desc,
                $data['reason'],
                $order->stage,
                'OrderRansomReturn',
                $OrderRansom->id,
                'RETURN_MONEY');
            $OrderRansom->return_money_into_status = $return_money_into_status;
            $OrderRansom->save();
            if (isset($orderCost) && $orderCost) {
                // 修改金额明细表
                $orderCost->save();
            }
            Db::commit();
            return $this->buildSuccess();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '操作失败' . $e->getMessage());
        }
    }

    /**添加金额明细表数据
     * @param OrderRansomReturn $orderRansom
     * @param $statustext 状态描述  财务已出账/出账已退回
     * @param $status 状态 1正常 2驳回 3退回
     * @return false|int
     * @throws \think\exception\DbException
     * @author: bordon
     */
    private function addOrderCost(OrderRansomReturn $orderRansom, $statustext, $status)
    {
        $res = $orderCost = OrderCostDetail::get(function ($query) use ($orderRansom) {
            $query->where(['order_sn' => $orderRansom->order_sn, 'tableid' => $orderRansom->id, 'tablename' => 'order_ransom_return']);
        });
        //已收回款总额
        $return_money = OrderRansomReturn::getReturnMoney(['order_sn' => $orderRansom->order_sn]);
        // 应收回款金额
        $return_money_amount = OrderRansomOut::getReturnMoneyAmount($orderRansom->order_sn);
        // 待收回款金额
        $balance = $return_money_amount - $return_money;
        //
        if ($orderCost && $status != 3) {
            $orderCost->finance_sn = Order::where('order_sn', $orderRansom->order_sn)->value('finance_sn');
            $orderCost->order_sn = $orderRansom->order_sn;
            $orderCost->statustext = $statustext;
            $orderCost->money = $orderRansom->money;
//            $orderCost->cost_date = date('Y-m-d', time());  不更新时间
            $orderCost->out_money_total = OrderCostDetail::where(['order_sn' => $orderRansom->order_sn, 'type' => 1, 'status' => 1])->sum('money');
            $orderCost->return_money_already = $return_money;
            $orderCost->return_money_wait = $balance;
            $orderCost->status = $status;
            $res = $orderCost;
        }
        //作废 原来记录状态改为2
        if (($orderCost && $status == 3)) {
            $orderCost->status = 2;
            $orderCost->save();
        }
        // 作废新增一条记录
        if (($orderCost && $status == 3) || !$orderCost) {
            $orderCostDetail = new OrderCostDetail();
            $orderCostDetail->finance_sn = Order::where('order_sn', $orderRansom->order_sn)->value('finance_sn');
            $orderCostDetail->order_sn = $orderRansom->order_sn;
            $orderCostDetail->type = 3;
            $orderCostDetail->item = '回款入账';
            $orderCostDetail->statustext = $statustext;
            $orderCostDetail->money = $orderRansom->money;
            $orderCostDetail->cost_date = date('Y-m-d', time());
            $orderCostDetail->out_money_total = OrderCostDetail::where(['order_sn' => $orderRansom->order_sn, 'type' => 1, 'status' => 1])->sum('money');
            $orderCostDetail->return_money_already = $return_money;
            $orderCostDetail->return_money_wait = $balance;
            $orderCostDetail->tablename = 'order_ransom_return';
            $orderCostDetail->tableid = $orderRansom->id;
            $orderCostDetail->status = $status;
            $res = $orderCostDetail;
        }
        return $res;
    }

    /**
     * @api {get} admin/FinancialReceipts/costList 回款入账列表 [admin/FinancialReceipts/costList]
     * @apiVersion 1.0.0
     * @apiName costList
     * @apiGroup FinancialReceipts
     * @apiSampleRequest  admin/FinancialReceipts/costList
     *
     * @apiParam {int} order_sn     订单编号
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {Object}  list       回款入账列表.
     * @apiSuccess {string} list.type    操作类型
     * @apiSuccess {string} list.item    项目明细
     * @apiSuccess {string} list.statustext    状态
     * @apiSuccess {string} list.money    金额
     * @apiSuccess {string} list.cost_date    时间
     * @apiSuccess {string} list.out_money_total    累计出账总额
     * @apiSuccess {string} list.return_money_already    已收回款总额
     * @apiSuccess {string} list.return_money_wait    待收回款总额
     * @apiSuccess {int} count    总条数
     */
    public function costList(Request $request)
    {
        $page = $request->get('page', 1);
        $pageSize = $request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $order_sn = $request->get('order_sn');
        if (!$order_sn) {
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数异常');
        }
        $list = OrderCostDetail::where('order_sn', $order_sn)->order('cost_date desc create_time asc')->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->each(function ($item, $key) {
                $item->type = OrderCostDetail::$typeMap[$item->type];
            })
            ->toArray();
        return $this->buildSuccess([
            'count' => $list['total'],
            'list' => $list['data']
        ]);
    }

    /**获取当前用户信息
     * @return array
     * @author: bordon
     */
    private function getUser()
    {
        return [
            'deptid' => $this->userInfo['deptid'],
            'deptname' => $this->userInfo['deptname'],
            'id' => $this->userInfo['id'],
        ];
    }

    /**获取审批类型
     * @param $item
     * @return mixed
     * @author: bordon
     */
    private function getType($item)
    {
        $arr = [1 => '通过', 2 => '驳回', 3 => '作废'];
        return $arr[$item];
    }

    /****************************************************bordon*********************************/

    /**
     * @api {post} admin/FinancialReceipts/FinancialBackdetail 财务回款详情[admin/FinancialReceipts/FinancialBackdetail]
     * @apiVersion 1.0.0
     * @apiName FinancialBackdetail
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/FinancialBackdetail
     *
     * @apiParam {int} order_sn    订单号
     * @apiParam {int} is_sonorder    1列表页订单 2子单列表页
     * @apiParam {int} return_id   回款表ID(列表页ID)
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
     * "baseinfo": {
     * "estate_name": [
     * "吉祥龙花园A座2单元10"
     * ],
     * "estate_owner": "llx",
     * "type_text": "交易现金",
     * "type": "JYXJ",
     * "finance_sn": "100000406",
     * "return_money_status": 1,
     * "return_money_status_text": "回款待完成",
     * "order_sn": "JYXJ2018080050",
     * "money": "2000000.00",
     * "default_interest": "0.00",
     * "self_financing": "0.00",
     * "channel_money": "2000000.00",
     * "company_money": "0.00",
     * "can_money": 2000000,
     * "out_money": 2000000,
     * "use_money": 0,
     * "notarization": "2018-08-29",
     * "return_money_amount": "2000000.00",
     * "return_money_mode": "通过业主回款"
     * },
     * "backcardinfo": [
     * {
     * "id": 665,
     * "bankaccount": "回款测试",
     * "accounttype": "卖方",
     * "bankcard": "14545121545211",
     * "openbank": "工商银行",
     * "verify_card_status": "已完成",
     * "type_text": "过账卡、回款卡"
     * },
     * {
     * "id": 666,
     * "bankaccount": "回款测试1",
     * "accounttype": "买方",
     * "bankcard": "45155468445417",
     * "openbank": "工商银行",
     * "verify_card_status": "已完成",
     * "type_text": "回款卡"
     * }
     * ],
     * "outbackinfo": {
     * "out_account_total": 2000000,
     * "account_com_total": 2000000,
     * "account_cus_total": 0,
     * "out_time": "2018-08-29",
     * "expectday": "2018-09-07",
     * "readyin_money": 0,
     * "unreadyin_money": 2000000,
     * "sq_money": null,//首期款金额
     * "return_time": null,
     * "ac_return_time": null//实际回款时间
     * },
     * "fee": {
     * "gufee_total": 0,//正常担保费
     * "exhibition_fee_total": 0,//实际展期费
     * "on_gufee": "12000.00",
     * "ac_exhibition_fee": "0.00",
     * "info_fee": "0.00",
     * "ac_overdue_money": 0,
     * "ready_overdue_money": "0.00",
     * "ac_transfer_fee": "0.00",
     * "ac_other_money": "0.00",
     * "ac_fee": "300.00",
     * "totol_money": 0,
     * "ready_money": 12300,
     * "un_money": -12300
     * },
     * "backmoneyrecord": []
     * }
     */
    public function FinancialBackdetail()
    {
        $order_sn = $this->request->post('order_sn', '');
//        $order_sn='TMXJ2018080011';
        if ($order_sn) {
            $is_sonorder = $this->request->post('is_sonorder', '');
            if ($is_sonorder == 1) {
                $return_id = $this->request->post('return_id', '');
                $data['return_money_into_status'] = $this->orderransomreturn->where('id', $return_id)->value('return_money_into_status');
            }
            //订单信息
            $data['baseinfo'] = FinancialBack::orderBaseinfo($order_sn);
            if (!$data['baseinfo'])
                $this->buildFailed(ReturnCode::UNKNOWN, '订单类型有误！');
            //回款卡信息
            $data['backcardinfo'] = FinancialBack::orderBackcardinfo($order_sn);
            //出账回款信息
            $data['outbackinfo'] = FinancialBack::outBackinfo($order_sn);
            if (!$data['outbackinfo']) {
                $this->buildFailed(ReturnCode::UNKNOWN, '出账回款信息有误！');
            }
            //费用信息
            $data['fee'] = FinancialBack::getfee($order_sn);
            //回款入账流水
            $data['backmoneyrecord'] = FinancialBack::orderBackmoneyrecord($order_sn);
            return $this->buildSuccess($data);
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误！');
        }
    }

    /**
     * @api {post} admin/FinancialReceipts/addBackmoneyrecord 新增/编辑回款入账[admin/FinancialReceipts/addBackmoneyrecord]
     * @apiVersion 1.0.0
     * @apiName addBackmoneyrecord
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/addBackmoneyrecord
     *
     * @apiParam {string}  id 回款记录id （编辑的时候才需要传）
     * @apiParam {string}  order_sn 订单编号
     * @apiParam {string}  unmoney  待回款金额
     * @apiParam {string}  money  回款金额
     * @apiParam {string}  bank_card_id  银行卡ID
     * @apiParam {string}  bank_card  银行卡号
     * @apiParam {string}  bank_account  银行账户
     * @apiParam {string}  bank  银行
     * @apiParam {string}  bank_branch  支行
     * @apiParam {string}  bank_name  银行账号别名（下拉选中的值）
     * @apiParam {string}  customer_bank_card  业主汇款账号
     * @apiParam {string}  customer_bank  业主汇款账号
     * @apiParam {string}  customer_bank_account  业主汇款账号
     * @apiParam {string}  order_guarantee_bank_id  业主汇款账号ID
     * @apiParam {date}    ac_return_time  实际回款时间
     * @apiParam {date}    return_time  预计回款时间
     * @apiParam {string}  remark  备注
     * @apiParam {array}   attachment  授权材料（数组）eg:[1,2]
     *
     */
    public function addBackmoneyrecord()
    {
        $data = $this->request->Post('', null, 'trim');
        $validate = loader::validate('AddBackmoney');
        if (!$validate->check($data)) {
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $validate->getError());
        }
        // 已收回款金额
        $return_money = OrderRansomReturn::getReturnMoney(['order_sn' => $data['order_sn']]);
        // 应收回款金额
        $return_money_amount = OrderRansomOut::getReturnMoneyAmount($data['order_sn']);
        // 待收回款金额
        $balance = $return_money_amount - $return_money;
        if ($balance != $data['unmoney']) {
            return $this->buildFailed(ReturnCode::UNKNOWN, '待回款金额有误！');
        }
        Db::startTrans();
        try {
            if (count($data['attachment']) > 0) {
                if (!$this->orderattac->filterCreditpic($data['order_sn'], $data['attachment'])) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '授权材料保存失败！');
                }
            }
            $data['return_money_into_status'] = 1; //默认回款入账待复核（编辑之后也是默认这个状态）
            $data['create_uid'] = $this->userInfo['id'];
            $where = [];
            !empty($data['id']) && $where['id'] = $data['id'];
            unset($data['unmoney']);
            unset($data['attachment']);
            $user = $this->systemuser->where('id', $this->userInfo['id'])->field('deptid,deptname,name')->find(); //获取用户信息
            $orderstatus = $this->order->where('order_sn', $data['order_sn'])->value('stage'); //获取当前主订单状态
            if ($this->orderransomreturn->save($data, $where)) {
                //加订单操作记录
                $userInfo['id'] = $data['create_uid'];
                $userInfo['deptid'] = $user['deptid'];
                $userInfo['deptname'] = $user['deptname'];
                $operate_det = empty($data['id']) ? "新增回款:新增一笔金额为" . $data['money'] . '的回款' : "编辑回款:编辑回款信息";
                $operate = empty($data['id']) ? "新增回款信息" : "编辑回款信息";
                $operate_node = empty($data['id']) ? "回款入账待复核" : "驳回待处理";
                $operate_table = 'order_ransom_return';
                $operate_table_id = empty($data['id']) ? $this->orderransomreturn->id : $data['id'];
                if (OrderComponents::addOrderLog($userInfo, $order_sn = $data['order_sn'], $stage = show_status_name($orderstatus, 'ORDER_JYDB_STATUS'), $operate, $operate_node, $operate_det, $operate_reason = '', $orderstatus, $operate_table, $operate_table_id, 'RETURN_MONEY')) {
                    Db::commit();
                    return $this->buildSuccess();
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::ADD_FAILED, '新增操作记录失败！');
            }
            Db::rollback();
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '新增回款记录失败！');
        } catch (Exception $exc) {
            return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
        }
    }

    /**
     * @api {get} admin/FinancialReceipts/getAllreceiptcard 获取所有公司回款卡账户信息[admin/FinancialReceipts/getAllreceiptcard]
     * @apiVersion 1.0.0
     * @apiName getAllreceiptcard
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/getAllreceiptcard
     *
     * @apiParam {int} name    账号名称
     *
     * @apiSuccess {string} id    银行卡id
     * @apiSuccess {string} name    银行卡别名（下拉展示的值）
     * @apiSuccess {string} openbank    银行
     * @apiSuccess {string} bankaccount   开户人
     * @apiSuccess {string} bankcard    银行卡号
     * @apiSuccess {string} bank_branch    支行
     */
    public function getAllreceiptcard()
    {
        $name = $this->request->get('name', '');
        $where = [
            'status' => 1,
            'type' => 7,
            'name' => ['like', "%{$name}%"]
        ];
        $data = Db::name('bank_card')->where($where)->field('id,name,bank_account,bank_card,bank,bank_branch')->order('create_time', 'DESC')->select();
        return $this->buildSuccess($data);
    }

    /**
     * @api {get} admin/FinancialReceipts/getReceiptinfo 获取回款记录信息[admin/FinancialReceipts/getReceiptinfo]
     * @apiVersion 1.0.0
     * @apiName getReceiptinfo
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/getReceiptinfo
     *
     * @apiParam {int} id    回款记录ID
     *
     * @apiSuccess {string} order_sn   订单号
     * @apiSuccess {string} money   回款金额
     * @apiSuccess {string} bank_name   回款账号
     * @apiSuccess {string} return_time    回款到账时间
     * @apiSuccess {string} remark    备注
     * @apiSuccess {array} attachment    附件
     */
    public function getReceiptinfo()
    {
        $id = $this->request->get('id', '');
        if ($id) {
            $where = ['id' => $id];
            $data = $this->orderransomreturn->where($where)->field('id,order_sn,money,bank_name,return_time,remark,customer_bank_card,customer_bank,customer_bank_account,order_guarantee_bank_id,ac_return_time')->find()->toarray();
            $aids = $this->orderattac->where(['order_sn' => $data['order_sn'], 'type' => 2, 'status' => 1])->column('attachment_id');
            foreach ($aids as $value) {
                $data['attachment'][] = $this->attachment->getUrl($value);
            }
            return $this->buildSuccess($data);
        }
        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误！');
    }

    /**
     * @api {get} admin/FinancialReceipts/getAllreceiptincard 获取所有业主回款卡账户信息[admin/FinancialReceipts/getAllreceiptincard]
     * @apiVersion 1.0.0
     * @apiName getAllreceiptincard
     * @apiGroup FinancialReceipts
     * @apiSampleRequest admin/FinancialReceipts/getAllreceiptincard
     *
     * @apiParam {int} order_sn  订单号
     *
     * @apiSuccess {string} openbank    银行
     * @apiSuccess {string} bankaccount   开户人
     * @apiSuccess {string} bankcard    银行卡号
     * @apiSuccess {array}  Allbank   所有出账银行，仅展示
     */
    public function getAllreceiptincard()
    {
        $order_sn = $this->request->get('order_sn', '');
        if ($order_sn) {
            $where = ['x.order_sn' => $order_sn, 't.type' => 4];
            $bankinfos = Db::name('order_guarantee_bank')->alias('x')
                ->join('__ORDER_GUARANTEE_BANK_TYPE__ t', 't.order_guarantee_bank_id=x.id', 'left')
                ->where($where)
                ->field('x.id,x.bankaccount,x.bankcard,x.openbank')
                ->select();
            if (!empty($bankinfos)) {
                foreach ($bankinfos as &$value) {
                    $value['bankaccount'] = $value['bankaccount']; //开户人
                    $value['openbank'] = $value['openbank']; //银行
                    $value['bankcard'] = $value['bankcard']; //银行卡号
                }
            }
            $AlloutBank = Db::name('order_ransom_out')->where(['order_sn' => $order_sn, 'status' => 1, 'account_status' => ['in', '2,3,5']])->column('out_bank_card_name');
            return $this->buildSuccess(['bankinfos' => $bankinfos, 'Allbank' => array_unique($AlloutBank)]);
        }
        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误！');
    }

}
