<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/8
 * Time: 11:09
 */
namespace app\admin\validate;
use think\Validate;


class DictionaryVali extends Validate{

    protected $rule = [
        'id|主键id' =>'require|number',
        'type|所属分类'  =>  'max:50',
        'code|标识'  =>  'require|max:50',
        'valname|名称' =>  'require|max:50'
    ];

}