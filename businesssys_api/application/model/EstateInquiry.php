<?php

/**
 * Created by PhpStorm.
 * User:  ZJQ
 * Date: 2018/5/11
 * Time: 09:40
 */

namespace app\model;

use think\Db;

class EstateInquiry extends Base {

    /**
     * 批量获取查档房产信息操作记录
     * @return array 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param where 查询条件
     * @param field 查询字段
     * @author zhongjiaqi 5.11
     */
    public function getCheckrecord($where, $field) {
        $data = $this->where($where)->field($field)->order('create_time DESC')->select();
        $this->dictionary = new Dictionary();
        foreach ($data as &$value) {
            $value['user_id'] = Db::name('system_user')->where('id', $value['user_id'])->value('name');
            $value['dept_id'] = Db::name('system_dept')->where('id', $value['dept_id'])->value('name');
            if (!empty($value['estate_inquiry_text'])) {
                $value['estate_inquiry_status'] = $value['estate_inquiry_text'];
            } else {
                $value['estate_inquiry_status'] = $this->dictionary->getValnameByCode('PROPERTY_STATUS', $value['estate_inquiry_status']);
            }
            $value['house_type'] = $value['house_type'] == 1 ? '分户' : '分栋';
        }
        return $data;
    }

    /**
     * 获取订单最新查档状态
     * @return array 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param where 查询条件
     * @param field 查询字段
     * @author zhongjiaqi 7.20
     */
    public function getnewEstateinquirystatus($order_sn) {
        $estate_inquiry_status = $this->where(['order_sn' => $order_sn])->order('create_time DESC')->value('estate_inquiry_status'); //房产状态正常
        if ($estate_inquiry_status == 3) {
            return false;
        }
        return true;
    }

}
