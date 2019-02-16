<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/25
 * Time: 15:46
 */
namespace app\admin\validate;
use think\Validate;


class SubmitFinc extends Validate{

    protected $rule = [
        'order_sn|订单编号' =>'require|max:20',
        'is_approval|审批结果'  =>  'require|number|max:1',
        'dispatch_id|赎楼派单表主键id'  =>  'require|number',
        'ransom_status|子订单状态'  =>  'require|number'
    ];

}