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

class RenewalValid extends Validate{
    protected $rule = [
        'order_sn|订单编号' => 'require|length:0,20',
        'return_money|待回款金额' => 'require|float|length:0,16',
        'exhibition_rate|展期费率' => 'require',
        'exhibition_starttime|展期开始时间' => 'require|date',
        'exhibition_endtime|展期结束时间' => 'require|date',
        'exhibition_day|展期天数' => 'require|number',
        'exhibition_fee|展期费用' => 'require|float|length:0,16',
        'exhibition_guarantee_fee|担保费抵扣金额' => 'float|length:0,16',
        'exhibition_info_fee|信息费抵扣金额'   => 'float|length:0,16',
        'total_money|应交金额' => 'float|length:0,16',
        'money|实交金额' => 'require|float|length:0,16',
        'reason|原因' => 'length:0,100',
        'attachment|附件材料' => 'array'
    ];
}