<?php
/**
 * 城市
 */
namespace app\admin\controller;

use app\model\Region;
use app\util\ReturnCode;


class Regions extends Base{


    // @author 林桂均
    /**
     * @api {post} admin/Regions/getCity 获取城市[admin/Regions/getCity]
     * @apiVersion 1.0.0
     * @apiName getCity
     * @apiGroup Regions
     * @apiSampleRequest admin/Regions/getCity
     *
     */
    public function getCity()
    {
        $result = Region::getAll(['level'=>2,'status'=>1],'id,shortname');
        if($result !== false)
            return $this->buildSuccess($result);
        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '城市读取失败!');
    }

    // @author 林桂均
    /**
     * @api {post} admin/Approval/add_Result 获取城区/片区[admin/Regions/getDistrict]
     * @apiVersion 1.0.0
     * @apiName getDistrict
     * @apiGroup Regions
     * @apiSampleRequest admin/Regions/getDistrict
     * @apiParam {string}  id   地区表id
     *
     */
    public function getDistrict()
    {
        $parentId = $this->request->post('id');
        if(empty($parentId))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '缺少参数!');
        $result = Region::getAll(['parentid'=>$parentId,'status'=>1],'id,shortname');
        if($result !== false)
            return $this->buildSuccess($result);
        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '读取失败!');
    }


    // @author 林桂均
    /**
     * @api {post} admin/Regions/getBuildingCity 获取楼盘城市选择接口[admin/Regions/getBuildingCity]
     * @apiVersion 1.0.0
     * @apiName getBuildingCity
     * @apiGroup Regions
     * @apiSampleRequest admin/Regions/getBuildingCity
     * @apiParam {string}  id   地区表id
     *
     */
    public function getBuildingCity()
    {
        $result = Region::getAll(['level'=>2,'status'=>1,'id'=>['in','420100,440300']],'id,shortname');
        if($result !== false)
            return $this->buildSuccess($result);
        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '城市读取失败!');
    }

    // @author 林桂均
    /**
     * @api {post} admin/Regions/getProvince 获取省接口[admin/Regions/getProvince]
     * @apiVersion 1.0.0
     * @apiName getProvince
     * @apiGroup Regions
     * @apiSampleRequest admin/Regions/getProvince
     *
     */
    public function getProvince()
    {
        $result = Region::getAll(['parentid'=>0,'status'=>1],'id,shortname');
        if($result !== false)
            return $this->buildSuccess($result);
        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '省信息读取失败!');
    }

}
