<?php

namespace app\model;

/**费用信息
 * Class OrderCollectFee
 * @package app\model
 * @author: bordon
 */
class OrderCollectFee extends Base
{
    /**费用类型
     * @var array
     */
    public static $typeMap = [
        1 => '正常担保',
        2 => '展期',
        3 => '逾期',
    ];

    public function getTypeAttr($value)
    {
        return self::$typeMap[$value];
    }

    /**获取费用信息
     * @param array $where 查询条件
     * @param bool $filed 查询字典
     * @param array $paginate 分页参数
     * @return array
     * @author: bordon
     */
    public static function getList($where = [], $field = true, $paginate = [])
    {
        return self::where($where)->field($field)->order('create_time desc')->paginate($paginate)->toArray();
    }
}
