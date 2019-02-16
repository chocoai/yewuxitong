<?php
/**
 * Created by PhpStorm.
 * User: bordon
 * Date: 2018-09-04
 * Time: 15:20
 */

namespace app\admin\validate;

use \think\Validate;

class OrderOther extends Validate
{
    protected $rule = [
        ['order_sn', 'require', '参数错误'],
        ['order_type', 'require', '订单类型不能为空'],
        ['guarantee_rate', 'checkGuaranteeRate:guarantee_rate'],
        ['reason', 'require|max:100', '折扣原因不能为空|折扣原因最多不能超过100个字符'],
        ['otherDiscount', 'checkOtherDiscount:otherDiscount'],
    ];

    protected $scene = [
        'addDiscount' => ['order_sn', 'order_type', 'reason', 'guarantee_rate', 'otherDiscount'], //添加折扣申请
    ];

    /**验证垫资信息 现金类
     * @param $value
     * @param $rule
     * @param $data
     * @author: bordon
     */
    protected function checkOtherDiscount($value, $rule, $data)
    {
        if ($this->checkOrderType($data['order_type'])) {
            if (!$value) {
                return '垫资信息不能为空';
            }
            foreach ($value as $item) {
                if (!$item['new_rate'] || !filter_var($item['new_rate'], FILTER_VALIDATE_FLOAT)) {
                    $msg = '现日垫资费率格式错误';
                    break;
                }
            }
            if (isset($msg)) {
                return $msg;
            }
            return true;
        }
        return true;
    }

    /**验证担保费率
     * @param $value
     * @param $rule
     * @param $data
     * @author: bordon
     */
    protected function checkGuaranteeRate($value, $rule, $data)
    {
        if (!$this->checkOrderType($data['order_type'])) {
            if (!$value) {
                return '担保费率不能为空';
            }
            if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
                return '担保费率格式错误';
            }
            return true;
        }
        return true;
    }

    /**是否为交易现金
     * @param $type 订单类型
     * @return bool true 为现金，false额度
     * @author: bordon
     */
    public function checkOrderType($type)
    {
        return in_array($type, ['JYXJ', 'TMXJ', 'DQJK', 'PDXJ', 'GMDZ', 'SQDZ']);
    }
}