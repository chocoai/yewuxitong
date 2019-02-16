<?php

namespace app\model;

/**
 * 楼盘模型
 */
class Dictionary extends Base {

    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * 根据类型查询数据字典
     * @param string $type
     * @return array
     * @author zhongjiaqi
     */
    public function getDictionaryByType($type) {
        $where = array(
            'status' => 1,
            'type' => $type,
        );
        $data = $this->where($where)->field('code,valname')->order('sort asc')->select();
        return $data;
    }

    /**
     * 根据类型和值查询名称
     * @param string $type
     * @return array
     * @author zhongjiaqi
     */
    public function getValnameByCode($type, $code) {
        $where = array(
            'status' => 1,
            'type' => $type,
            'code' => $code
        );
        $valname = $this->where($where)->value('valname');
        return $valname;
    }

    /**
     * 根据类型和值查询名称
     * @param string $type
     * @return array
     * @author zhongjiaqi
     */
    public function getcodeByvalname($type, $valname) {
        $where = array(
            'status' => 1,
            'type' => $type,
            'valname' => $valname
        );
        $code = $this->where($where)->value('code');
        return $code;
    }

    /* @author 赵光帅
     * 获取数据字典列表
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function dictionaryList($map, $page, $pageSize) {
        $res = self::alias('a')
                ->field('id,type,code,valname,remark,status,create_time')
                ->where($map)
                ->order('sort asc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
        return $res;
    }

    /**
     * 查询多个字典类型
     * @param $typeArr
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function dictionaryMultiType($typeArr)
    {
        return self::where(['type'=>['in',$typeArr],'status'=>1])->field('type,code,valname')->select();
    }

    /*
     * @author 赵光帅
     * 获取器 对status字段的转化
     * 支票状态 1使用中 2禁用
     * */

    public function getStatusAttr($value) {
        $status = [1 => '使用中', 0 => '禁用'];
        return $status[$value];
    }

}
