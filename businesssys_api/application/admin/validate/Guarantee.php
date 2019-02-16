<?php

/**
 * 按揭信息验证类
 */

namespace app\admin\validate;

use \think\Validate;

class Guarantee extends Validate{
    protected  $rule = [

        'notarization' => 'dateFormat:Y-m-d',//公正日期
        'self_financing' => 'float|length:0,15',
        'guarantee_rate' => 'float',
        'guarantee_fee' => 'float|length:0,15',
        'fee' => 'require|float|length:0,15',
        'info_fee' => 'float|length:0,15',

//        'return_money_amount'=>'float|length:0,15',
        'project_money_date'=>'dateFormat:Y-m-d',//预计还款日
        'evaluation_price'=>'float|length:0,15|>:0',//评估价
        'now_mortgage'=>'between:0,10',//现按揭层数
        'turn_into_date' => 'dateFormat:Y-m-d',//转入日期
        'turn_back_date' => 'dateFormat:Y-m-d',//转回日期

    ];

    protected $message = [
        'notarization' => '公正日期格式有误',
        'self_financing' => '自筹金额有误',
        'guarantee_rate' => '担保费率有误',
        'guarantee_fee' => '担保费有误',
        'fee' => '手续费有误',
        'info_fee' => '预计信息费有误',
//        'return_money_amount' => '回款金额格式有误',
        'project_money_date' => '预计还款日期有误',
        'evaluation_price' => '评估价格式有误',
        'now_mortgage' => '按揭成数格式有误',
        'turn_into_date' => '转入日期有误',
        'turn_back_date' => '传回日期有误',
    ];
}
