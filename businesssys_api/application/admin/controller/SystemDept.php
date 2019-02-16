<?php

/**
 * 用户部门控制器
 */

namespace app\admin\controller;

use app\model\SystemUser;
use app\model\SystemDept as SystemDeptModel;
use app\util\ReturnCode;

class SystemDept extends Base {

    private $systemuser;
    private $systemdeptmodel;

    public function _initialize() {
        parent::_initialize();
        $this->systemuser = new SystemUser();
        $this->systemdeptmodel = new SystemDeptModel();
    }

    /**
     * @api {get} admin/SystemDept/getTopdept 获取顶级部门[admin/SystemDept/getTopdept]
     * @apiVersion 1.0.0
     * @apiName getTopdept
     * @apiGroup SystemDept
     * @apiSampleRequest admin/SystemDept/getTopdept
     *
     * @apiSuccess {array} data    顶级部门数据集
     */
    public function getTopdept() {
        $data = $this->systemdeptmodel->getTopdept();
        return $this->buildSuccess($data);
    }

    /**
     * @api {get} admin/SystemDept/getDowndept 获取下级部门[admin/SystemDept/getDowndept]
     * @apiVersion 1.0.0
     * @apiName getDowndept
     * @apiGroup SystemDept
     * @apiSampleRequest admin/SystemDept/getDowndept
     *
     * @apiSuccess {array} data    下级部门数据集
     */
    public function getDowndept() {
        $id = $this->request->get('id', '');
        if ($id) {
            $data = $this->systemdeptmodel->getDowndept($id);
            return $this->buildSuccess($data);
        }
        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!');
    }

    /**
     * @api {get} admin/SystemDept/getUpdept 获取上级部门[admin/SystemDept/getUpdept]
     * @apiVersion 1.0.0
     * @apiName getUpdept
     * @apiGroup SystemDept
     * @apiSampleRequest admin/SystemDept/getUpdept
     *
     * @apiSuccess {array} data    上级部门数据集
     */
    public function getUpdept() {
        $id = $this->request->get('id', '');
        if ($id) {
            $data = $this->systemdeptmodel->getUpdept($id);
            return $this->buildSuccess($data);
        }
        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!');
    }

    /**
     * @api {post} admin/SystemDept/index 获取系统所有部门[admin/SystemDept/index]
     * @apiVersion 1.0.0
     * @apiName index
     * @apiGroup SystemDept
     * @apiSampleRequest admin/SystemDept/index
     *
     */
    public function index()
    {
        $result = SystemDeptModel::getAll(['parentid' => 0, 'status' => 1], 'name as title,parentid,id');

        if ($result) {
            SystemDeptModel::getAllDept($result);

            return $this->buildSuccess($result);
        }
        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '部门信息获取失败!');
    }

}
