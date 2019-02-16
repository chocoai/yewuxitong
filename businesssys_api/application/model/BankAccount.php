<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/8/3
 * Time: 10:41
 */

namespace app\model;


class BankAccount extends Base{
    /* @author 赵光帅
     * 银行账户列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function bankAccountList($map, $field, $page, $pageSize) {
        $resInfo = self::field($field)
            ->where($map)
            ->order('update_time desc')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        return $resInfo;
    }

}