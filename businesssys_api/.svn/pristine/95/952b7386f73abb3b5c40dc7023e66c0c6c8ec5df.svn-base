<?php

/**
 * 系统控制器
 */

namespace app\admin\controller;

use app\model\System as SystemModel;
use think\Db;
use app\util\ReturnCode;

class System extends Base {

    private $system;

    public function _initialize() {
        parent::_initialize();
        $this->system = new SystemModel();
    }

    /**
     * @api {get} admin/System/getAllsystem 获取所有后台系统[admin/System/getAllsystem]
     * @apiVersion 1.0.0
     * @apiName getAllsystem
     * @apiGroup System
     * @apiSampleRequest admin/System/getAllsystem
     *
     * @apiSuccess {array} data    系统数据集
     */
    public function getAllsystem() {
        $data = $this->system->getAllsystem();
        return $this->buildSuccess($data);
    }
    /**
     * @api {post} admin/System/getAllcompany 获取所有公司[admin/System/getAllcompany]
     * @apiVersion 1.0.0
     * @apiName getAllcompany
     * @apiGroup System
     * @apiSampleRequest admin/System/getAllcompany
     *
     * @apiSuccess {array} data    系统数据集
     */
    public function getAllcompany() {
        $data = DB::name('oa_company')->field('id,name')->order('sort')->select();
        return $this->buildSuccess($data);
    }

}
