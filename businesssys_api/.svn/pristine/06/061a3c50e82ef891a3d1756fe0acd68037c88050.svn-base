<?php

/**
 * 订单验证类
 */

namespace app\admin\validate;

use \think\Validate;

class CreditValidate extends Validate {

    protected $rule = [
        'type' => 'require', //所属类型
        'certdata' => 'require', //证件信息
        'customer_name' => 'require', //名称
        'financing_manager_id' => 'require', //理财经理
        'picture' => 'require', //授权材料
    ];
    protected $message = [
        'type' => '所属类型不能为空', //所属类型
        'certdata' => '证件信息不能为空', //证件信息
        'customer_name' => '姓名不能为空', //名称
        'financing_manager_id' => '理财经理不能为空', //理财经理
        'picture' => '授权资料至少上传一张', //理财经理
    ];

}
