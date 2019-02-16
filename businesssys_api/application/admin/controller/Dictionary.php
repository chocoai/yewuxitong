<?php
/* * *用户 */

namespace app\admin\controller;
use app\util\ReturnCode;
use app\model\Dictionary as Datasjzd;

class Dictionary extends Base {

    /**
     * @api {get} admin/Credit/getDictionaryByType 获取数据字典类型[admin/Credit/getDictionaryByType]
     * @apiVersion 1.0.0
     * @apiName getDictionaryByType
     * @apiGroup Credit
     * @apiSampleRequest admin/Credit/getDictionaryByType
     *
     * @apiSuccess {array} data    数据字典数据集
     */
    public function getDictionaryByType() {
        $type = $this->request->post('type/a');
        $data = getdictionarylist($type);
        return $this->buildSuccess($data);
    }

    /**
     * @api {post} admin/Dictionary/getPrimaryData 获取数据字典列表[admin/Dictionary/getPrimaryData]
     * @apiVersion 1.0.0
     * @apiName getPrimaryData
     * @apiGroup Dictionary
     * @apiSampleRequest admin/Dictionary/getPrimaryData
     *
     * @apiParam {string} type    一级数据字典传0 二级传对应的type
     * @apiParam {string} key    名称值等关键字
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccess {int} id    数据字典表主键id
     * @apiSuccess {string} type    数据类型
     * @apiSuccess {string} code    标识
     * @apiSuccess {string} valname    名称
     * @apiSuccess {string} remark    备注
     * @apiSuccess {int} status    状态
     * @apiSuccess {string} create_time    录入时间
     */
    public function getPrimaryData() {
        $type = input('type')?:0;
        $searchText = input('key');
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
        $searchText && $whereMap['valname|code'] = ['like', "%{$searchText}%"];
        $whereMap['status'] = ['<>',-1];
        $whereMap['type'] = $type;
        try{
            return $this->buildSuccess(Datasjzd::dictionaryList($whereMap,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '获取失败!');
        }

    }

    /**
     * @api {post} admin/Dictionary/showDictionary 数据字典查询[admin/Dictionary/showDictionary]
     * @apiVersion 1.0.0
     * @apiName showDictionary
     * @apiGroup Dictionary
     * @apiSampleRequest admin/Dictionary/showDictionary
     *
     * @apiParam {int} id   数据字典表主键id
     *
     * @apiSuccess {int} id    数据字典表主键id
     * @apiSuccess {string} code    标识
     * @apiSuccess {string} valname    名称
     * @apiSuccess {string} nameone    一级分类名称(编辑二级分类时才返回该值)
     */
    public function showDictionary() {
        $dictionaryId = input('id');
        if(empty($dictionaryId)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');
        try{
            $dataInfo = Datasjzd::getOne(['id' => $dictionaryId],'id,type,code,valname');
            if(empty($dataInfo['type'])){ //一级分类

            }else{
                $nameInfo = Datasjzd::getOne(['code' => $dataInfo['type']],'valname');
                $dataInfo['nameone'] = $nameInfo['valname'];
            }
            unset($dataInfo['type']);
            return $this->buildSuccess($dataInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '获取失败!');
        }

    }

    /**
     * @api {post} admin/Dictionary/editDictionary 数据字典编辑[admin/Dictionary/editDictionary]
     * @apiVersion 1.0.0
     * @apiName editDictionary
     * @apiGroup Dictionary
     * @apiSampleRequest admin/Dictionary/editDictionary
     *
     * @apiParam {int} id   数据字典表主键id
     * @apiParam  {string} type    所属分类(编辑二级分类时才传该参数)
     * @apiParam  {string} code    标识
     * @apiParam  {string} valname    名称
     *
     */
    public function editDictionary() {
        $dataInfo['id'] = input('id');
        $dataInfo['code'] = input('code');
        $dataInfo['valname'] = input('valname');
        $dictInfo = Datasjzd::get(['id' => $dataInfo['id']]);
        if(empty($dictInfo['type'])){  //编辑一级分类
            $type = 0;
        }else{
            $type = input('type');
            if(empty($type)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '所属分类不能为空!');
        }

        $valiDate = validate('DictionaryVali');
        if(!$valiDate->check($dataInfo)){
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
        }
        try{
            $dictInfo->type = $type;
            $dictInfo->code = $dataInfo['code'];
            $dictInfo->valname = $dataInfo['valname'];
            $dictInfo->update_time = time();
            $dictInfo->save();
        return $this->buildSuccess('编辑成功!');
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '编辑失败!');
        }

    }

    /**
     * @api {post} admin/Dictionary/delDictionary 数据字典禁用和删除[admin/Dictionary/delDictionary]
     * @apiVersion 1.0.0
     * @apiName delDictionary
     * @apiGroup Dictionary
     * @apiSampleRequest admin/Dictionary/delDictionary
     *
     * @apiParam {int} id   数据字典表主键id
     * @apiParam {int} type   1 代表禁用  2代表删除
     *
     */
    public function delDictionary() {
        $dictionaryId = input('id');
        $lxType = input('type')?:1;
        if(empty($dictionaryId)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');
        try{
            $dictInfo = Datasjzd::get(['id' => $dictionaryId]);
            if($lxType == 1){
                $dictInfo->status = 0;
                $okMsg = "禁用成功!";
            }else{
                $dictInfo->status = -1;
                $okMsg = "删除成功!";
            }
            $dictInfo->save();
            return $this->buildSuccess($okMsg);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '操作失败!');
        }

    }

    /**
     * @api {post} admin/Dictionary/addDictionary 添加数据字典[admin/Dictionary/addDictionary]
     * @apiVersion 1.0.0
     * @apiName addDictionary
     * @apiGroup Dictionary
     * @apiSampleRequest admin/Dictionary/addDictionary
     *
     * @apiParam  {string} type    所属分类(添加二级分类时才传该参数)
     * @apiParam  {string} code    标识
     * @apiParam  {string} valname    名称
     *
     */
    public function addDictionary() {
        $type = input('type');
        $code = input('code');
        $valname = input('valname');
        if(empty($code)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '标识不能为空!');
        if(empty($valname)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '名称不能为空!');
        try{
            if(!empty($type) && isset($type)){  //添加二级分类
                $dataInfo['type'] = $type;
                $dataInfo['code'] = $code;
                $dataInfo['valname'] = $valname;
                $dataInfo['create_time'] = time();
                Datasjzd::create($dataInfo);
            }else{  //添加一级分类
                $dataInfo['type'] = 0;
                $dataInfo['code'] = $code;
                $dataInfo['valname'] = $valname;
                $dataInfo['create_time'] = time();
                Datasjzd::create($dataInfo);
            }
            return $this->buildSuccess('添加成功');
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '添加失败!');
        }

    }





}
