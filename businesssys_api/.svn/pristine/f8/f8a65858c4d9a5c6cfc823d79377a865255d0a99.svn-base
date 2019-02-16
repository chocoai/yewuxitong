<?php
/**
 * Created by PhpStorm.
 * User: bordon
 * Date: 2018-09-04
 * Time: 16:38
 */

namespace app\model;


class OrderOtherDiscount extends Base
{
    public static $stageStatus = [
        ['code' => 301, 'name' => '待业务保单'],
        ['code' => 302, 'name' => '待部门经理审批'],
        ['code' => 312, 'name' => '待区域经理审批'],
        ['code' => 313, 'name' => '待事业部经理审批'],
        ['code' => 303, 'name' => '待风控经理审批'],
        ['code' => 314, 'name' => '待风控总监审批'],
        ['code' => 315, 'name' => '待总经理审批'],
        ['code' => 316, 'name' => '待财务经理审批'],
        ['code' => 317, 'name' => '待财务主管审批'],
        ['code' => 308, 'name' => '已完成']
    ];

    public function Other()
    {
        return $this->belongsTo("OrderOther", "order_other_id");
    }
}