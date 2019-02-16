<?php

/**
 * 订单验证类
 */

namespace app\admin\validate;

use \think\Validate;

class AddnuclearCarddata extends Validate {

    protected $rule = [
        'order_guarantee_bank_id' => 'require', //核卡id
        'card_type' => 'require', //卡号类型
        'cyber_bank' => 'require|in:0,1,2', //网银
        'mobile_bank' =>  'require|in:0,1,2',//手机银行
        'telephone_bank' =>  'require|in:0,1,2',//电话银行
        'security_account' =>  'require|in:0,1,2',//证券账号
        'verify_card_time' => 'require',//核卡时间
    ];
    protected $message = [
        'order_guarantee_bank_id' => '此卡核卡信息有误', 
        'card_type' => '卡号类型不能为空',
        'cyber_bank' => '网银信息选择有误', 
        'mobile_bank' => '手机银行信息选择有误', 
        'telephone_bank' => '电话银行信息选择有误',
        'security_account' => '证券账号信息选择有误',
        'verify_card_time' => '核卡时间不能为空',
    ];

}
