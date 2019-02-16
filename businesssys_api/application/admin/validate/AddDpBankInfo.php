<?php

/**
 * 首期监管银行/回款方式 验证
 */

namespace app\admin\validate;

use \think\Validate;

class AddDpBankInfo extends Validate {

    protected $rule = [
        'dp_supervise_date' => 'require|date',
        'dp_money' => 'require|float|length:0,15',
        'dp_organization_type' => 'require|in:1,2',
        'dp_supervise_bank' => 'requireIf:dp_organization_type,1',
        'dp_supervise_bank_branch' => 'requireIf:dp_organization_type,1',
        'dp_organization' => 'requireIf:dp_organization_type,2',
        'return_money_mode' => 'require',
        'return_money_amount' => 'require|float|length:0,15',
    ];
    protected $message = [
        'dp_supervise_date' => '首期监管日期不能为空',
        'dp_supervise_date.date' => '首期监管日期格式有误',
        'dp_money' => '监管金额不能为空',
        'dp_money.float|length' => '监管金额格式有误',
        'dp_organization_type' => '机构类型不能为空',
        'dp_organization_type.in' => '机构类型参数有误',
        'dp_supervise_bank' => '机构类型为银行时,监管机构银行不能为空',
        'dp_supervise_bank_branch' => '机构类型为银行时,监管机构银行支行不能为空',
        'dp_organization' => '机构类型为其他时,监管机构不能为空',
        'return_money_mode' => '回款方式不能为空',
        'return_money_amount' => '回款金额不能为空',
        'return_money_amount.float|length' => '回款金额格式有误',
    ];
    protected $scene = [
        'AddDpBankInfo' => ['dp_supervise_date', 'dp_money', 'dp_organization_type', 'dp_supervise_bank', 'dp_supervise_bank_branch', 'dp_organization'], //首期监管银行
        'AddReturnway' => ['return_money_mode', 'return_money_amount'], //回款方式
    ];

}
