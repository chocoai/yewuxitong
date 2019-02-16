<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/26
 * Time: 19:14
 */
namespace app\model;


class ChequeLog extends Base {

    /*
     * @author 赵光帅
     * 获取支票操作列表
     * @Param {array} $map    条件
     * @Param {int} $page   分页起始条数
     * @Param {int} $pageSize    每页数量
     * */
    public static function getLogList($map,$page,$pageSize)
    {
        $res = self::alias('a')
            ->field('create_time,operate_name,operate_deptname,remark,operate_det,note')
            ->where($map)
            ->order('create_time desc')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        return $res;
    }

}