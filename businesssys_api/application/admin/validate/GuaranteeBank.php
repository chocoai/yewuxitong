<?php

/**
 * 赎楼银行信息
 */

namespace app\admin\validate;

use \think\Validate;

class GuaranteeBank extends Validate {

    protected $rule = [
        'bankaccount' => 'require|length:1,100',
        'accounttype' => 'require|in:1,2,3,4,5,6,7,8',
        'bankcard' => 'require|length:1,30',
        'openbank' => 'require',
        'bankuse' => 'require|array'
    ];
    protected $message = [
        'bankuse' => '银行卡用途不能为空', //银行卡用途
        'bankuse.array' => '银行卡用途参数格式不对', //银行卡用途
        'bankaccount' => '银行户名有误',
        'accounttype' => '还款账户类型有误',
        'bankcard' => '银行卡有误',
        'openbank' => '银行名称有误',
    ];

}
