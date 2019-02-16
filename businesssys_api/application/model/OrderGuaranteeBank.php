<?php

namespace app\model;

use think\Db;

class OrderGuaranteeBank extends Base {

    /**
     * 判断当前订单是否全部完成核卡
     * @return 账目
     * @throws \think\db\exception\DataNotFoundException
     * @param $order_sn 订单号
     * @author zhongjiaqi 7.11
     */
    public function checkiscomplete($order_sn) {
        $where = ['x.order_sn' => $order_sn, 'x.status' => 1, 'x.verify_card_status' => ['neq', 3], 't.type' => ['in', '1,3,4,5']];
        $bankinfos = $this->alias('x')
                ->join('__ORDER_GUARANTEE_BANK_TYPE__ t', 't.order_guarantee_bank_id=x.id', 'left')
                ->where($where)
                ->field('x.id')
                ->select();
        //订单中所有需要核卡的卡 核卡完成才需要更改主订单 是否核卡完成字段
        if (count($bankinfos) == 0) {
            Db::name('order_guarantee')->where('order_sn', $order_sn)->update(['is_verify_card' => 1, 'update_time' => time()]);
        }
    }

    /**
     * 获取银行卡用途
     * @return 银行卡用途
     * @throws \think\db\exception\DataNotFoundException
     * @param $status  
     * @author zhongjiaqi 8.6
     */
    public function getBankuse($status = '') {
        $statusList = ['1' => '赎楼卡', '2' => '尾款卡', '3' => '过账卡', '4' => '回款卡', '5' => '监管卡', '6' => '出账卡'];
        return empty($status) ? '' : $statusList[$status];
    }

    /**
     * 获取核卡状态
     * @return 核卡状态
     * @throws \think\db\exception\DataNotFoundException
     * @param $status  
     * @author zhongjiaqi 8.6
     */
    public function getAccountstatus($status = '') {
        $statusList = ['0' => '待核卡', '1' => '待财务复核', '2' => '驳回待处理', '3' => '已完成'];
        return empty($status) ? '待核卡' : $statusList[$status];
    }

}
