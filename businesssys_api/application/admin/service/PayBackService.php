<?php
/**
 * Created by PhpStorm.
 * User: bordon
 * Date: 2018-08-23
 * Time: 14:49
 */

namespace app\admin\service;

use app\model\Order;
use app\model\OrderRansomOut;
use app\model\OrderRansomReturn;
use app\model\OrderGuarantee;
use app\model\OrderCostRecord;
use think\Exception;

class PayBackService
{


    /**
     * 回款完成条件校验
     * 回款已收齐；判断条件：待收回款金额小于0
     * 逾期费已收齐；判断条件：实际逾期费=已收逾期费
     * 过账手续费已收齐；（数值大于0）
     * @author: bordon
     */
    public function checkfinishPayback($order_sn)
    {
//        待收回款金额小于0  应收回款金额-已收回款金额 =0
        $OrderGuaranteeInfo = OrderGuarantee::where('order_sn', $order_sn)->field('ac_overdue_money, ac_transfer_fee')->find();
        // 已收回款金额(这里取入账已完成的金额)
        $return_money = OrderRansomReturn::where(['order_sn' => $order_sn, 'status' => 1, 'return_money_into_status' => 3])->sum('money');
        // 应收回款金额
        $return_money_amount = OrderRansomOut::getReturnMoneyAmount($order_sn);
        $balance = $return_money_amount - $return_money;
        //实际逾期费=已收逾期费
        $payback_overdue_money = OrderCostRecord::where('order_sn', $order_sn)->sum('overdue_money');
        $overdue_money = $OrderGuaranteeInfo['ac_overdue_money'] - $payback_overdue_money;
        if ($balance > 0) {
            $msg = '回款未收齐，无法发起回款完成指令。';
            $code = -1;
        }
        if ($overdue_money != 0) {
            $msg = '逾期费未收齐，无法发起回款完成指令。';
            $code = -1;
        }
//        if ($balance > 0 && $overdue_money != 0) {
//            $msg = '回款未收齐，逾期费未收齐，无法发起回款完成指令。';
//            $code = -1;
//        }
        $ac_fee = number_format((float)$OrderGuaranteeInfo->ac_transfer_fee, 2);
        return [
            'code' => isset($code) ? $code : 1,
            'msg' => isset($msg) ? $msg : "回款已收齐，逾期费已收齐，过账手续费为{$ac_fee}元，确认回款完成？"
        ];
    }

}