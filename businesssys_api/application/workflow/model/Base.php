<?php
/**
 * Created by PhpStorm.
 * User: bordon
 * Date: 2018-05-08
 */

namespace app\workflow\model;

use think\Model;
use traits\model\SoftDelete;

class Base extends Model
{
    use SoftDelete;

    /**删除数据
     * @param $where 删除条件
     * @return $this
     */
    public static function del($where)
    {
        return self::where($where)->update(['status' => -1, 'delete_time' => time()]);
    }
}