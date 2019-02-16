<?php

/**
 * 订单验证类
 */

namespace app\admin\validate;

use \think\Validate;

class AddUser extends Validate {

    protected $rule = [
        'name' => 'require', //真实姓名
        'num' => 'require', //工号
        'mobile' => 'require|max:11|/^1[3-8]{1}[0-9]{9}$/', //联系电话
        'ranking' => 'require', //职位
        'companyid' => 'require', //公司
        'deptpath' => 'require', //部门
    ];
    protected $message = [
        'name' => '真实姓名不能为空', //真实姓名
        'num' => '工号不能为空', //工号
        'mobile' => '联系电话有误', //联系电话
        'ranking' => '职位不能为空', //职位
        'companyid' => '公司不能为空', //职位
        'deptpath' => '请选择部门', //部门
    ];

}
