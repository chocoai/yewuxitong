<?php

namespace app\workflow\model;

class WorkflowFlow extends Base
{
    //
    protected $autoWriteTimestamp = true;

    const FLOW_TYPE_RISK = 'risk';
    const FLOW_TYPE_FINANCE = 'finance';
    const FLOW_TYPE_INFO_FEE = 'info_fee';
    const FLOW_TYPE_OTHER_REFUND = 'other_refund';
    const FLOW_TYPE_EXTENSION = 'Extension';
    const FLOW_TYPE_UPDATE_PROFILE = 'update_profile';
    const FLOW_TYPE_DISCOUNT_APPLY = 'discount_apply';
    const FLOW_TYPE_CANCEL_ORDER = 'canel_order';
    const FLOW_TYPE_IMPORT_ORDER_ITEM = 'import_order_item';

    /**流程类型
     * @var array
     */
    public static $flowTypeMap = [
        self::FLOW_TYPE_RISK => '风控审批',
        self::FLOW_TYPE_FINANCE => '财务审批',
        self::FLOW_TYPE_INFO_FEE => '信息费支付',
        self::FLOW_TYPE_OTHER_REFUND => '其它退费',
        self::FLOW_TYPE_EXTENSION => '展期申请',
        self::FLOW_TYPE_UPDATE_PROFILE => '展期申请',
        self::FLOW_TYPE_DISCOUNT_APPLY => '折扣申请',
        self::FLOW_TYPE_UPDATE_PROFILE => '资料修改申请',
        self::FLOW_TYPE_CANCEL_ORDER => '撤单申请',
        self::FLOW_TYPE_IMPORT_ORDER_ITEM => '要事审批',
    ];

}
