<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/26
 * Time: 17:32
 */
namespace app\admin\validate;
use think\Validate;


class BanksVerify extends Validate{

    protected $rule = [
        'starebanks|起始票号' =>'require|number|max:8|min:8',
        'endbanks|结束票号'  =>  'require|number|max:8|min:8',
        'bankname|银行名称' =>  'require'
    ];

}