<?php

/* 订单数据api
 */

namespace app\api\controller;

use think\Db;
use app\model\OrderGuarantee;

class Order extends Base {

    /**
     * 同步预计出账总额
     * 预计出账总额=商贷+公积金贷+消费贷
     * admin/DataSynchronization/totalAmountsame
     */
    public function totalAmountsame() {
        $orderGuarantee = new OrderGuarantee();
        $where = ['x.status' => 1, 'y.status' => 1];
        $data = $orderGuarantee->alias('x')->join('__ORDER__ y', 'y.order_sn=x.order_sn')->where($where)->field('y.type,x.money,x.id,x.bussiness_loan,x.accumulation_fund,x.consumer_loan')->select();
        if (!empty($data)) {
            foreach ($data as $value) {
                if ($value['type'] == 'JYDB') {
                    $total_amount = $value['bussiness_loan'] + $value['accumulation_fund'] + $value['consumer_loan'];
                } else {
                    $total_amount = $value['money'];
                }
                $update [] = ['out_account_total' => $total_amount, 'id' => $value['id']];
            }
            Db::startTrans();
            if (!$orderGuarantee->allowField(['out_account_total'])->isUpdate()->saveAll($update)) {
                Db::rollback();
                return '同步失败';
            }
            Db::commit();
            return '同步成功，本次共同步' . count($data) . '条数据';
        } else {
            return '无数据同步';
        }
    }

}
