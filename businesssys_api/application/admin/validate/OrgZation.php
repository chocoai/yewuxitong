<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/4
 * Time: 18:10
 */
namespace app\admin\validate;
use think\Validate;


class OrgZation extends Validate{

    protected $rule = [
        'type|部门类型' =>'require|number|max:2',
        'name|部门名称'  =>  'require|max:15',
        'parentid|父部门id' =>  'require|number',
        'sort|排序'=> 'require|number'
    ];

}