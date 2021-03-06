<?php
/**
*订单验证类
 */
namespace app\admin\validate;
use \think\Validate;
class OrderValidate extends Validate{
    protected $rule = [
        //订单

        'type' => 'require|in:JYDB,JYXJ,TMXJ,DQJK,PDXJ,GMDZ,SQDZ',
        'money'                 =>      'float|length:1,15>0',
        'financing_manager_id'             =>      'require',//理财经理
        'financing_dept_id'                =>      'require',//部门ID
        'mortgage_mobile'         =>      'length:1,15',//按揭电话



    ];

    protected $message = [

        'type.require' => '订单类型不能为空',
        'type.in' => '订单类型格式有误',

        'money' => '订单金额有误',
        'money.float|length' => '订单金额格式有误',
        'financing_manager_id'         => '理财经理不能为空',
        'financing_dept_id' => '部门不能为空',//部门ID
        'mortgage_mobile' => '按揭员电话有误',
    ];
}
