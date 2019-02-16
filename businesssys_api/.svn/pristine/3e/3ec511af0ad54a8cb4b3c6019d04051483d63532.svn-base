<?php

namespace app\model;


use think\Model;

class AppUpdate extends Model {


    public static function modify($where,$field='',$order='',$limit='')
    {
         return self::where($where)->field($field)->order($order)->limit($limit)->select();
    }

    /**
     * @author 林桂均
     * 获取单条信息
     * @param $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOne($where,$field)
    {
        return self::where($where)->field($field)->find();
    }

    /**
     * @author 林桂均
     * 获取条数
     * @param $where
     * @return int|string
     */
    public static function getCount($where,$lock=false)
    {
        return self::where($where)->lock($lock)->count();
    }



    /**
     * @author 林桂均
     * 添加/主键更新单条记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        return $this->save($data);
    }




}