<?php

/**
 * 赎楼
 */

namespace app\model;

use think\Db;
use app\model\OrderMortgage;

class OrderRansomDispatch extends Base {

    /**
     * 获取赎楼类型
     * @return 赎楼类型
     * @throws \think\db\exception\DataNotFoundException
     * @param $status  赎楼类型
     * @author zhongjiaqi 5.21
     */
    public function getRansomtype($status = '', $order_type = '') {
        if (!empty($order_type) && in_array($order_type, ['SQJK','PDXJ','DQJK'])) {
            $statusList = ['SQJK' => '首期款垫资', 'PDXJ' => '凭抵押回执', 'DQJK' => '短期借款'];
            return empty($order_type) ? '' : $statusList[$order_type];
        } else {
            $statusList = ['1' => '公积金贷款', '2' => '商业贷款', '3' => '装修贷/消费贷'];
            return empty($status) ? '' : $statusList[$status];
        }
    }

    /**
     * 判断是否此订单是否全部完成赎楼
     * @return 赎楼类型
     * @throws \think\db\exception\DataNotFoundException
     * @param $order_sn  订单号
     * @author zhongjiaqi 5.25
     */
    public function checkIsransom($order_sn) {
        $flag = true;
        $ransomstatus = $this->where('order_sn', $order_sn)->column('ransom_status');
        foreach ($ransomstatus as $value) {
            if ($value != 207) {
                $flag = false;
                break;
            }
        }
        $stage = 1014;
        if ($flag) {
            //完成赎楼之后要改主订单状态,已完成赎楼状态 并提交订单到权证版块
            $order = Db::name('order')->where(['order_sn' => $order_sn])->field('type,business_type')->find();
            if ($order['type'] == 'JYXJ' || $order['type'] == 'TMXJ' || $order['type'] == 'GMDZ' || $order['type'] == 'JYDB') {
                $stage = 1015;
                if ($order['type'] == 'TMXJ' && $order['business_type'] == 1) {//当订单类型是非交易现金 并且 非现金交易业务类型为个人资金过桥时  没有去红本过程 主订单状态直接变成待结单
                    $stage = 1026;
                } else {
                    $OrderWarrant = new OrderWarrant();
                    $list = [
                        ['order_sn' => $order_sn, 'warrant_stage' => 1, 'create_time' => time(), 'update_time' => time()],
                    ];
                    $OrderWarrant->saveAll($list);
                }
            } elseif ($order['type'] == 'PDXJ' || $order['type'] == 'DQJK' || $order['type'] == 'SQDZ') {
                $stage = 1026;
            }
            Db::name('order')->where('order_sn', $order_sn)->update(['stage' => $stage, 'is_foreclosure_finish' => 1, 'update_time' => time()]);
        }
        return $stage;
    }

}
