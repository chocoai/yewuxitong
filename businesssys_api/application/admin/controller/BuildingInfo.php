<?php
/**
 * 楼盘信息
 */
namespace app\admin\controller;

use app\admin\service\Zcdc;
use app\util\ReturnCode;

class BuildingInfo extends Base {

    // @author 林桂均
    /**
     * @api {post} admin/BuildingInfo/getBuilding 获取楼盘名称[admin/BuildingInfo/getBuilding]
     * @apiVersion 1.0.0
     * @apiName getBuilding
     * @apiGroup BuildingInfo
     * @apiSampleRequest admin/BuildingInfo/getBuilding
     * @apiParam {string}  districtId   地区表id
     * @apiParam {string}  buildingName   楼盘名称
     */
    public function getBuilding()
    {
        $districtId = $this->request->post('districtId');
        $buildingName = input('post.buildingName','');
        $zcdc = new Zcdc;
        $par['districtId'] = $districtId;
        $buildingName && $par['buildingName'] = $buildingName;
        $result = $zcdc->buildingInfo($par);
        if($result !== false) return $this->buildSuccess($result);
        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '楼盘信息读取失败!');
    }



    // @author 林桂均
    /**
     * @api {post} admin/BuildingInfo/addBuilding 添加楼盘[admin/BuildingInfo/addBuilding]
     * @apiVersion 1.0.0
     * @apiName addBuilding
     * @apiGroup BuildingInfo
     * @apiSampleRequest admin/BuildingInfo/addBuilding
     * @apiParam {string}  districtId   地区表id
     * @apiParam {string}  cityId  市id
     * @apiParam {string}  buildingName   楼盘名称
     * @apiParam {string}  buildingAlias   楼盘别名
     */

    public function addBuilding()
    {
        $data['building_name'] = trim($this->request->post('buildingName'));
        $data['building_alias'] = trim($this->request->post('buildingAlias',''));
        $data['city_id'] = $this->request->post('cityId');
        $data['district_id'] = $this->request->post('districtId');
        $zcdc = new Zcdc;
        $result = $zcdc->addBuilding($data);
        if($result!==false && !is_array($result)) return $this->buildFailed(ReturnCode::DB_READ_ERROR, $result);
        if($result === false) return $this->buildFailed(ReturnCode::DB_READ_ERROR, '楼盘信息添加失败!');
        if($result['code'] === 1) return $this->buildSuccess($result['data']);
        return $this->buildFailed(ReturnCode::DB_READ_ERROR, $result['msg']);
    }










}
