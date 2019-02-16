<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/23
 * Time: 16:23
 */

namespace app\model;

use think\Db;

class OrderGuarantee extends Base {

    /**
      /* @author 林桂均
     * 担保赎楼信息
     * @param $orderSn
     * @return array
     * @throws \think\exception\DbException
     */
    public static function orderGuarantee($orderSn, $type) {
        $guaranteeInfo = self::alias('x')
                ->join('order y', 'x.order_sn=y.order_sn')
                ->where(['y.order_sn' => $orderSn, 'x.status' => 1, 'y.type' => $type])
                ->field('x.notarization,x.money,x.self_financing,x.guarantee_per,x.guarantee_rate,x.out_account_total,x.account_per,x.guarantee_fee,fee,x.info_fee,total_fee,y.order_source,y.source_info,y.financing_manager_id,y.financing_dept_id,y.mortgage_name,y.mortgage_mobile,y.remark,x.project_money_date,x.turn_into_date,x.turn_back_date,y.business_type,y.dept_manager_id,y.stage')
                ->find();
        if (!$guaranteeInfo)
            return false;
        //获取理财经理姓名
        $financeInfo = Db::name('system_user')->alias('a')
                ->field('a.name,b.name as dept_name')
                ->join('system_dept b', 'a.deptid=b.id')
                ->where(['a.id' => $guaranteeInfo['financing_manager_id']])
                ->find();
        if ($financeInfo) {
            $guaranteeInfo['financing_manager_id_str'] = $financeInfo['name'];
            $guaranteeInfo['financing_dept_id_str'] = $financeInfo['dept_name'];
        }
        if ($guaranteeInfo['order_source']) {
            $guaranteeInfo['order_source_str'] = Db::name('dictionary')->where(['type' => 'ORDER_YWLY', 'code' => $guaranteeInfo['order_source']])->value('valname');
        }
        return $guaranteeInfo;
    }

}
