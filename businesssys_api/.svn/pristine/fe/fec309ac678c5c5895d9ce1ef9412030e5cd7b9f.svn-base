<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/9
 * Time: 9:56
 */
namespace app\model;


class OrderLog extends Base {

    /* @author 赵光帅
     * 查询财务赎楼流程
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function fincList($map, $page, $pageSize) {
        return self::alias('x')
            ->field('x.create_time,x.operate,x.operate_node,x.operate_det,z.name')
            ->join('system_user z', 'x.create_uid=z.id')
            ->where($map)
            ->order('x.create_time desc')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
    }


}