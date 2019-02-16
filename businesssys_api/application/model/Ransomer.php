<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/18
 * Time: 18:35
 */

namespace app\model;

use app\util\Tools;

class Ransomer extends Base {

    /**
     * 获取赎楼派单列表 超级管理员和赎楼主管能看到所有订单  其他人只有赎楼员能看到自己的订单
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @author zhongjiaqi 6.15
     */
    public function getRansomerup($user_id) {
        $data = [];
        $isSupper = Tools::isAdministrator($user_id);
        $where = ['position' => '赎楼主管'];
        $ransomerUp = db('system_user')->where($where)->column('id');
        if ($isSupper || in_array($user_id, $ransomerUp)) {
            $data = implode(',', $this->column('id'));
        } else {
            $data = $this->where('user_id', $user_id)->value('id');
        }
        return $data;
    }

}
