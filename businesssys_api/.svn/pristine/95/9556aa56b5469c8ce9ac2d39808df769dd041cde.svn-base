<?php
/*栋阁/楼层/房号信息控制器*/
namespace app\admin\controller;

use app\admin\service\Zcdc;
use app\model\TrialData;
use think\Db;

class VersionUpdate extends Base {

    /**
     * 
     */
    public function index()
    {
        if(request()->isPost()){


        }else{
            $id = input('id');
        }

        $buildingId = $this->request->post('buildingId');
        if(empty($buildingId))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '缺少参数或无效的参数!');
        $zcdc = new Zcdc;
        $result = $zcdc->getUnit(['buildingId'=>$buildingId]);
        if($result !== false) return $this->buildSuccess($result);
        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '栋阁信息读取失败!');

    }


}
