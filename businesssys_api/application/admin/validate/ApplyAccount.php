<?php

/**
 * 订单验证类
 */

namespace app\admin\validate;

use \think\Validate;

class ApplyAccount extends Validate {

    protected $rule = [
        'item' => 'require', //出账类型
        'money' => 'require', //出账金额
        'way' => 'require|in:1,2', //出账方式
        'is_prestore' => 'requireIf:way,1|in:1,0', //是否预存
        'account_type' => 'requireIf:way,1', //账户类型
        'prestore_day' => 'requireIf:is_prestore,1', //预存天数
        'out_bank_card' => 'requireIf:way,1', //出账卡号
        'out_bank' => 'requireIf:way,1', //出账银行
        'out_bank_account' => 'requireIf:way,1', //出账账户
        'bank' => 'requireIf:way,2', //支票银行
        'cheque_num' => 'requireIf:way,2', //支票号码
    ];
    protected $message = [
        'item' => '出账类型不能为空', //出账类型
        'money' => '出账金额不能为空', //出账金额
        'way' => '请选择出账方式', //出账金额
        'is_prestore' => '现金出账,请选择是否预存', //是否预存
        'is_prestore.in' => '现金出账是否预存参数有误', //是否预存
        'account_type' => '现金出账,请选择账户类型', //账户类型
        'prestore_day' => '现金出账预存单,预存天数不能为空', //出账卡号
        'out_bank_card' => '现金出账,出账卡号必选', //出账卡号
        'out_bank' => '现金出账,出账银行必选', //出账银行
        'out_bank_account' => '现金出账,出账账户必选', //出账账户
        'bank' => '支票出账,支票银行必选', //支票银行
        'cheque_num' => '支票出账,支票号码必选', //支票号码
    ];

}
