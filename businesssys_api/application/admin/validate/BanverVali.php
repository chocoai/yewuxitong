<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/27
 * Time: 10:59
 */
namespace app\admin\validate;
use think\Validate;


class BanverVali extends Validate{

    protected $rule = [
        'id|支票id' =>'require|number|max:10',
        'cheque_num|支票号码' =>'require|number|max:8|min:8',
        'bankname|银行名称' =>  'require'
    ];

}