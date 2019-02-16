<?php
/**
 * 银行账户信息验证类
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/8/4
 * Time: 10:35
 */
namespace app\admin\validate;

use \think\Validate;

class ValidAccount extends Validate{
    protected $rule = [
        'account_type|账户类型' => 'require|in:1,2',
        'bank_account|银行户名' => 'require|length:0,30',
        'bank_card|银行卡号' => 'require|number|length:0,30',
        'open_city|开户城市' => 'require|in:深圳,武汉',
        'bank|开户银行' => 'require',
        'bank_branch|开户支行' => 'length:0,50',
        'status|账号状态' => 'require|in:-1,1,2,3,4',
        'account_manager|账号负责人' => 'length:0,50',
        'account_manager_uid|账号负责人id'   => 'number',
        'account_nature|账号性质' => 'in:1,2,3,4',
        'key_transactor|经办key管理员' => 'length:0,50',
        'key_transactor_uid|经办key管理员id' => 'number',
        'key_reviewer|复核key管理员' => 'length:0,50',
        'key_reviewer_uid|复核key管理员id' => 'number',
        'account_way|对账方式' => 'in:1,2,3',
        'is_review|是否需要复核' => 'in:0,1',
        'account_cycle|对账周期'   => 'in:1,2',
        'account_time|对账时间' => 'in:1,2',
        'single_limit_public|单笔限额 对公' => 'float|length:0,16|>:0',
        'single_limit_private|单笔限额 对私' => 'float|length:0,16|>:0',
        'day_limit_public|单日限额 对公' => 'float|length:0,16|>:0',
        'day_limit_private|单日限额 对私' => 'float|length:0,16|>:0',
        'customer_manager|客户经理姓名' => 'length:0,50',
        'customer_manager_mobile|客户经理电话'   => 'number|length:0,30',
        'bank_branch_address|支行地址' => 'length:0,255',
        'account_use|账号用途' => 'length:0,20',
        'card_front|卡号照片正面地址' => 'length:0,255',
        'card_back|卡号照片反面地址' => 'length:0,255',
        'remark|备注说明' => 'length:0,1000'

    ];
}