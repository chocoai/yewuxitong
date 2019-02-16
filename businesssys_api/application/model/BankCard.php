<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/18
 * Time: 18:35
 */

namespace app\model;

class BankCard extends Base{
    /* @author 赵光帅
     * 账号设置列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function accountSettingList($map, $field, $page, $pageSize) {
        $resInfo = self::alias('bc')
            ->field($field)
            ->join('bank_account ba','bc.bank_account_id = ba.id')
            ->where($map)
            ->order('bc.create_time desc')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        return $resInfo;
    }

}