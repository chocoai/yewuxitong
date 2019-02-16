<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/24
 * Time: 11:09
 */

namespace app\model;

use app\util\Tools;

class SystemPosition extends Base {

    /**
     * 获取所有岗位
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param $where 条件（默认不限制）
     * @param $field 查询字段限制（默认查所有）
     * @param $limit 查询数量限制（默认查所有）
     * @author zhongjiaqi 5.15
     */
    public function getAllposition($where = [], $field = '*', $limit = 0) {
        $data = $this->where($where)->field($field)->limit($limit)->select();
        return Tools::buildArrFromObj($data);
    }

}
