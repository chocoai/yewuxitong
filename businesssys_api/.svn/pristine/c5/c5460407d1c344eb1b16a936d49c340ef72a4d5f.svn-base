<?php

/**
 * 订单验证类
 */

namespace app\admin\validate;

use \think\Validate;

class AddnuclearRecord extends Validate {

    protected $rule = [
        'order_guarantee_bank_id' => 'require', //核卡id
        'account_balance' => 'require', //账户余额
        'account_status' => 'require|in:1,2,3,4,5', //账户状态
        'check_time' =>  'require',//查账时间
    ];
    protected $message = [
        'order_guarantee_bank_id' => '此卡核卡信息有误', 
        'account_balance' => '账户余额不能为空',
        'account_status' => '账户状态选择有误', 
        'check_time' => '查账时间不能为空', 
    ];

}
