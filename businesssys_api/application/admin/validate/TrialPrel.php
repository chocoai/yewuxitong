<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/20
 * Time: 16:09
 */
//提交初审的验证器
namespace app\admin\validate;
use think\Validate;


class TrialPrel extends Validate{

    protected $rule = [
        'order_sn|订单编号' =>'require|max:20',
        'balance_per|负债成数'  =>  'require|number|max:6|between:0,100',
        'is_normal|是否正常单' =>  'require|number|max:3',
        'review_rating|审查评级'=> 'require|max:3',
        'risk_rating|风险评级' =>'require|max:3',
        'is_material|是否缺资料通过'  =>  'require|number|max:1',
        'is_guarantee|是否提供反担保' =>  'require|number|max:1',
        'is_asset_prove|是否提供资产证明'=> 'require|number|max:1',
        'is_guarantee_estate|是否房产反担保' =>'require|number|max:1',
        'is_guarantee_money|是否保证金反担保'  =>  'require|number|max:1',
        'is_guarantee_other|是否其它方式反担保' =>  'require|number|max:1',
        'guarantee_money|保证金'=> 'number|max:10'
    ];

}