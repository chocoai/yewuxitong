<?php

/**
 * Created by PhpStorm.
 * User: ZJQ
 * Date: 2018/6/4
 * Time: 17:55
 */

namespace app\model;

class Estate extends Base {

    /**
     * 批量获取查档房产信息
     * @return array 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param where 查询条件
     * @param field 查询字段
     * @author zhongjiaqi 5.11
     */
    public function getEstateinfo($where, $field) {
        $data = $this->where($where)->field($field)->select();
        $this->dictionary = new Dictionary();
        foreach ($data as &$value) {
            $value['estate_certtype'] = $this->dictionary->getValnameByCode('HOUSECERTTYPE', $value['estate_certtype']);
            $value['estate_inquiry_status'] = !empty($value['estate_inquiry_status']) ? $this->dictionary->getValnameByCode('PROPERTY_STATUS', $value['estate_inquiry_status']) : '';
            $value['estate_inquiry_time'] = empty($value['estate_inquiry_time']) ? '' : date('Y-m-d H:i', $value['estate_inquiry_time']);
            $value['house_type'] = $value['house_type'] == 1 ? '分户' : '分栋';
        }
        return $data;
    }

    /**
     * 获取单条查档房产信息
     * @return array 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param where 查询条件
     * @param field 查询字段
     * @author zhongjiaqi 5.11
     */
    public function getonlyEstateinfo($where, $field) {
        $data = $this->where($where)->field($field)->find()->toArray();
        return $data;
    }

    /**
     * 更新征信
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @param $data 与数据库字段对应的数组集合  $id 需要更新数据的用户id
     * @author zhongjiaqi 5.11
     */
    public function updateEstate($id, $data) {
        $res = $this->where('id', $id)->update($data);
        return $flag = $res > 0 ? TRUE : FALSE;
    }

}
