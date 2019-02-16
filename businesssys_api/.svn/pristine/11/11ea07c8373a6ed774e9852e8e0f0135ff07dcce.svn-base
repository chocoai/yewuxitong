<?php

/**
 *
 * @since   2018-06-05
 * @author  ZJQ
 */

namespace app\model;

use app\util\Tools;

class System extends Base {

    /**
     * 获取系统
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @author zhongjiaqi 6.15
     */
    public function getAllsystem() {
        $where = ['hide' => 1];
        $data = $this->where($where)->field('id,name')->order('sort')->select();
        return Tools::buildArrFromObj($data);
    }

}
