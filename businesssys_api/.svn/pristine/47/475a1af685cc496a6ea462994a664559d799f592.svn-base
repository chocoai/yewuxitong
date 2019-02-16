<?php

/**
 * 按揭信息验证类
 */

namespace app\admin\validate;

use \think\Validate;

class Mortgage extends Validate {

    protected $rule = [
        'type' => 'require|in:ORIGINAL,NOW',
        'mortgage_type' => 'require|in:1,2,3',
        'money' => 'float|length:0,13',
        'organization_type' => 'require|in:1,2',
        'organization' => 'require',
        'out_account' => 'requireIf:type,ORIGINAL',
        'interest_balance' => 'require|float|length:0,13',
    ];
    protected $message = [
        'type.require' => '类型不能为空',
        'type.in' => '类型无效',
        'mortgage_type.require' => '按揭类型不能为空',
        'mortgage_type.in' => '按揭类型无效',
        'money.float' => '按揭金额格式有误',
        'money.length' => '按揭金额长度过长',
        'organization_type.require' => '按揭机构类型不能为空',
        'organization_type.in' => '按揭机构类型无效',
        'organization' => '按揭机构不能为空',
        'out_account' => '原按揭预出账金额不能为空',
        'interest_balance.require' => '本息不能为空',
        'interest_balance.float' => '本息格式有误',
        'interest_balance.length' => '本息超过有效长度',
    ];

}
