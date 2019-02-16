<?php

namespace app\model;

use think\Model;

class OrderCostDetail extends Base {
    /*     * 操作类型
     * @var array
     */

    public static $typeMap = [
        1 => '出账',
        2 => '入账',
        3 => '回款'
    ];
    /*     * 状态
     * @var array
     */
    public static $statusMap = [
        1 => '正常',
        2 => '驳回',
        3 => '退回'
    ];

    /*
     * 新增(入账、出账、回款)金额明细表记录
     * zjq 2018.8.24
     */

    public function AddcostRecord($finance_sn, $order_sn, $type, $item, $statustext, $money, $out_money_total, $return_money_already, $return_money_wait, $tablename, $tableid, $status) {
        $data ['finance_sn'] = $finance_sn;
        $data ['order_sn'] = $order_sn;
        $data ['type'] = $type;
        $data ['item'] = $item;
        $data ['statustext'] = $statustext;
        $data ['money'] = $money;
        $data ['cost_date'] = date('Y-m-d', time());
        $data ['out_money_total'] = $out_money_total;
        $data ['return_money_already'] = $return_money_already;
        $data ['return_money_wait'] = $return_money_wait;
        $data ['tablename'] = $tablename;
        $data ['tableid'] = $tableid;
        $data ['status'] = $status;
        if (!$this->save($data)) {
            return false;
        }
        return true;
    }

}
