<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/12
 * Time: 15:45
 */
namespace app\admin\validate;
use think\Validate;


class SubmitExamination extends Validate{

    protected $rule = [
        'order_sn|订单编号' =>'require|max:20',
        'stage|订单状态' =>'require|max:4|number',
        'is_approval|审批结果'  =>  'require|number|max:1',
        'proc_id|处理明细表主键id'  =>  'require|number',
        'next_user_id|下一步审批人员'=> 'max:12|number',
        'backtoback|是否退回之后直接返回本节点' =>'max:12|number',
        'back_proc_id|退回节点id'  =>  'max:12|number',
        'process_id|流程步骤表主键id'  =>  'require|number',
        'process_name|节点名称' =>  'require',
        'content|审批意见' =>'length:0,200',
        'item|注意事项' =>'length:0,500',
        'next_process_name|流向的节点名称' =>  'require',
        'is_next_user|是否需要选择审查员'=> 'require|in:0,1',
    ];

}