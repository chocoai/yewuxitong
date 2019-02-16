<?php

namespace app\model;

use think\Db;

/* * 展期
 * Class OrderOtherExhibition
 * @package app\model
 * @author: bordon
 */

class OrderOtherExhibition extends Base {
    /*     * other关联模型
     * @author: bordon
     */

    public function orderOther() {
        return $this->belongsTo('OrderOther', 'order_other_id');
    }

    /*
     * 延后推送展期合同 要改该订单的每日费用  逾期变展期  正常担保变展期
     * @param $order_sn 订单号
     * @param $exhibition_starttime 展期合同开始时间
     * @param $exhibition_endtime 展期合同结束时间
     * @param $rate 展期费率
     */

    public function ChangefeeByexhibotion($order_sn, $exhibition_starttime, $exhibition_endtime, $rate) {
        $this->today = date('Y-m-d');
        $dataArr = [];
        if ($exhibition_starttime <= $this->today && $exhibition_endtime > $this->today) {
            $dataArr = $this->prDates($exhibition_starttime, $this->today);
        } elseif ($exhibition_starttime <= $this->today && $exhibition_endtime <= $this->today) {
            $dataArr = $this->prDates($exhibition_starttime, $exhibition_endtime);
        }
        if (!empty($dataArr)) {
            foreach ($dataArr as $key => $value) {
                $feeData = DB::name('order_collect_fee')->where(['order_sn' => $order_sn, 'cal_date' => $value, 'status' => 1, 'create_uid' => -1])->field('id,type,cal_money')->find();
                if (!empty($feeData)) {
                    $remark = '';
                    if ($feeData['type'] != 2) {
                        $remark = $feeData['type'] == 1 ? '正常担保变展期' : '逾期变展期';
                    }
                    $total_fee = sprintf('%.2f', $feeData['cal_money'] * $rate / 100);
                    $updata = ['type' => 2, 'rate' => $rate, 'remark' => $remark, 'money' => $total_fee];
                    if (!Db::name('order_collect_fee')->where('id', $feeData['id'])->update($updata)) {
                        return false;
                    }
                }
            }
        }
        return TRUE;
    }

    /*
     * 获取两个date时间之间的日期
     */

    function prDates($start, $end) {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        $dataArr = [];
        while ($dt_start <= $dt_end) {
            $dataArr[] = date('Y-m-d', $dt_start);
            $dt_start = strtotime('+1 day', $dt_start);
        }
        return $dataArr;
    }

}
