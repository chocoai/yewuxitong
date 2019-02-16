<?php

/**
 * 用户部门控制器
 */

namespace app\admin\controller;

use app\model\SystemUser;
use app\model\SystemPosition as SystemPositionModel;
use app\util\ReturnCode;

class SystemPosition extends Base {

    private $systemuser;
    private $systempositionmodel;

    public function _initialize() {
        parent::_initialize();
        $this->systemuser = new SystemUser();
        $this->systempositionmodel = new SystemPositionModel();
    }

    /**
     * @api {get} admin/SystemPosition/getAllposition 模糊匹配岗位[admin/SystemPosition/getAllposition]
     * @apiVersion 1.0.0
     * @apiName getAllposition
     * @apiGroup SystemPosition
     * @apiSampleRequest admin/SystemPosition/getAllposition
     * @param name  岗位名称
     * 
     * @apiSuccess {array} data    匹配岗位数据集
     */
    public function getAllposition() {
        $name = $this->request->get('name', '');
        if ($name) {
            $where['name'] = ['like', "%{$name}%"];
            $field = 'id,name';
            $data = $this->systempositionmodel->getAllposition($where, $field, 10);
            return $this->buildSuccess($data);
        }
        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!');
    }

}
