<?php

namespace app\admin\controller;

use app\model\SystemUser as SystemUserModel;
use app\model\SystemDept as SystemDept;
use app\util\ReturnCode;

class SystemUser extends Base {

    private $systemuser;
    private $systemdept;

    public function _initialize() {
        parent::_initialize();
        $this->systemuser = new SystemUserModel();
        $this->systemdept = new SystemDept();
    }

    /**
     * @api {get} admin/SystemUser/managerList 模糊获取理财经理[admin/SystemUser/managerList]
     * @apiVersion 1.0.0
     * @apiName managerList
     * @apiGroup SystemUser
     * @apiSampleRequest admin/SystemUser/managerList
     *
     *  @apiParam {string} name    客户经理姓名
     * 
     * @apiSuccess {array} data    客户经理列表
     */
    public function managerList() {
        $name = $this->request->get('name', '');
        if ($name) {
            $where = [
                'su.status' => 1,
                'su.name' => ['like', "%{$name}%"],
                'su.is_deleted' => 0,
                'su.position' => '理财经理'
            ];
            $data = $this->systemuser->getmanagernameList($where);
            if ($data) {
                foreach ($data as $key => $value) {
                    $newarray[] = ['id' => $value['id'], 'managername' => $value['name'], 'deptname' => $value['deptname'], 'deptid' => $value['deptid']];
                }
                return $this->buildSuccess($newarray);
            }
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '暂无数据!');
            ;
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!');
        }
    }

    /**
     * @api {get} admin/SystemUser/getDowndeptperson 选择上级主管（根据部门获取下级部门以及下级部门下面的所有人）[admin/SystemUser/getDowndeptperson]
     * @apiVersion 1.0.0
     * @apiName getDowndeptperson
     * @apiGroup SystemUser
     * @apiSampleRequest admin/SystemUser/getDowndeptperson
     *
     *  @apiParam {int} id  部门id
     * 
     * @apiSuccess {array} data  数据集
     */
    public function getDowndeptperson() {
        $id = $this->request->get('id', '');
        if ($id) {
            $data = $this->systemdept->getDowndept($id);
            if (!empty($data)) {
                foreach ($data as &$value) {
                    $value['deptdata'] = $this->systemuser->getDeptpeopleList($value['id']);
                }
            }
            return $this->buildSuccess($data);
        }
    }

}
