<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/4
 * Time: 13:44
 * 组织结构操作类
 */
namespace app\admin\controller;
use app\model\SystemDept;
use app\util\ReturnCode;


class OrganiZation extends Base{
        protected $resHighest = [];
        /**
         * @api {post} admin/OrganiZation/strucList 组织结构列表[admin/OrganiZation/strucList]
         * @apiVersion 1.0.0
         * @apiName strucList
         * @apiGroup OrganiZation
         * @apiSampleRequest admin/OrganiZation/strucList
         *
         * @apiSuccess {int} id    主键id
         * @apiSuccess {string} name    部门名称
         * @apiSuccess {int} parentid    父亲部门id
         * @apiSuccess {int} sort    排序
         * @apiSuccess {string} create_time    录入时间
         */

        public function strucList(){
            try{
                $mapWhere['parentid'] = 0;
                $mapWhere['delete_time'] = NULL;
                $mapWhere['status'] = 1;
                $res0Highest = SystemDept::getAll($mapWhere,'id,name,parentid,sort,create_time');
                foreach ($res0Highest as $k => $v){
                    $res1Highest = SystemDept::getAll(['parentid' => $v['id'],'delete_time' => NULL,'status' => 1],'id,name,parentid,sort,create_time');
                    foreach ($res1Highest as $k2 => $v2){
                        $res2Highest = SystemDept::getAll(['parentid' => $v2['id'],'delete_time' => NULL,'status' => 1],'id,name,parentid,sort,create_time');
                        foreach ($res2Highest as $k3 => $v3){
                            $res3Highest = SystemDept::getAll(['parentid' => $v3['id'],'delete_time' => NULL,'status' => 1],'id,name,parentid,sort,create_time');
                            foreach ($res3Highest as $k4 => $v4){
                                $res4Highest = SystemDept::getAll(['parentid' => $v4['id'],'delete_time' => NULL,'status' => 1],'id,name,parentid,sort,create_time');
                                foreach ($res4Highest as $k5 => $v5){
                                    $res5Highest = SystemDept::getAll(['parentid' => $v5['id'],'delete_time' => NULL,'status' => 1],'id,name,parentid,sort,create_time');
                                    foreach ($res5Highest as $k6 => $v6){
                                        $res6Highest = SystemDept::getAll(['parentid' => $v6['id'],'delete_time' => NULL,'status' => 1],'id,name,parentid,sort,create_time');
                                        $res5Highest[$k6]['six'] = $res6Highest;
                                    }
                                    $res4Highest[$k5]['five'] = $res5Highest;
                                }
                                $res3Highest[$k4]['four'] = $res4Highest;
                            }
                            $res2Highest[$k3]['three'] = $res3Highest;
                        }
                        $res1Highest[$k2]['two'] = $res2Highest;
                    }
                    $res0Highest[$k]['one'] = $res1Highest;
                }
                return $this->buildSuccess($res0Highest);
            }catch (\Exception $e){
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
            }
        }

        /**
         * @api {post} admin/OrganiZation/showDigui 递归查询组织结构[admin/OrganiZation/showDigui ]
         * @apiVersion 1.0.0
         * @apiName showDigui
         * @apiGroup OrganiZation
         * @apiSampleRequest admin/OrganiZation/showDigui
         *
         */
        public function showDigui(){
            self::diGui(0);
            return $this->buildSuccess($this->resHighest);
        }

        /*
         * 递归函数查询组织架构
         * */
        protected function diGui($id){
            $mapWhere['parentid'] = $id;
            $mapWhere['delete_time'] = NULL;
            $mapWhere['status'] = 1;
            $dataInfo = SystemDept::getAll($mapWhere,'id,name,parentid,sort,create_time');
            foreach ($dataInfo as $k => $v) {
                $v['stotal'] = SystemDept::getAll(['parentid' => $v['id'], 'delete_time' => NULL, 'status' => 1], 'id,name,parentid,sort,create_time');
                $this->resHighest[] = $v;
                self::diGui($v['id']);
            }
        }

        /**
         * @api {post} admin/OrganiZation/addOrgani 添加组织结构[admin/OrganiZation/addOrgani ]
         * @apiVersion 1.0.0
         * @apiName addOrgani
         * @apiGroup OrganiZation
         * @apiSampleRequest admin/OrganiZation/addOrgani
         *
         *
         * @apiParam {int}  type   类型
         * @apiParam {string}  name   部门名称
         * @apiParam {int}  parentid   父部门id
         * @apiParam {string}  remark   备注
         * @apiParam {int}  sort   排序
         *
         */
        public function addOrgani(){
            $organiDate['type'] = input('type');
            $organiDate['name'] = input('name');
            $organiDate['parentid'] = input('parentid');
            $organiDate['remark'] = input('remark');
            $organiDate['sort'] = input('sort');
            $valiDate = validate('OrgZation');
            if(!$valiDate->check($organiDate)){
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
            }
            try{
                $organiDate['status'] = 1;
                $organiDate['create_time'] = time();
                SystemDept::create($organiDate);
                return $this->buildSuccess('添加成功');
            }catch (\Exception $e){
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '添加失败!');
            }

        }

        /**
         * @api {post} admin/OrganiZation/bumenInfo 部门信息[admin/OrganiZation/bumenInfo ]
         * @apiVersion 1.0.0
         * @apiName bumenInfo
         * @apiGroup OrganiZation
         * @apiSampleRequest admin/OrganiZation/bumenInfo
         *
         * @apiParam {int}  id   主键id
         *
         * @apiSuccess{int}  type   类型
         * @apiSuccess {string}  name   部门名称
         * @apiSuccess {int}  parentid   父部门id
         * @apiSuccess {string}  remark   备注
         * @apiSuccess {int}  sort   排序
         */
        public function bumenInfo(){
            $id = input('id');
            if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');
            try{
                $deptInfo = SystemDept::getOne(['id' => $id],'type,name,parentid,remark,sort');
                return $this->buildSuccess($deptInfo);
            }catch (\Exception $e){
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
            }

        }

        /**
         * @api {post} admin/OrganiZation/editOrgani 编辑组织结构[admin/OrganiZation/editOrgani ]
         * @apiVersion 1.0.0
         * @apiName editOrgani
         * @apiGroup OrganiZation
         * @apiSampleRequest admin/OrganiZation/editOrgani
         *
         * @apiParam {int}  id   主键id
         * @apiParam {int}  type   类型
         * @apiParam {string}  name   部门名称
         * @apiParam {int}  parentid   父部门id
         * @apiParam {string}  remark   备注
         * @apiParam {int}  sort   排序
         *
         */
        public function editOrgani(){
            $id = input('id');
            $organiDate['type'] = input('type');
            $organiDate['name'] = input('name');
            $organiDate['parentid'] = input('parentid');
            $organiDate['remark'] = input('remark');
            $organiDate['sort'] = input('sort');
            $valiDate = validate('OrgZation');
            if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');
            if(!$valiDate->check($organiDate)){
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
            }
            try{
                $deptInfo = SystemDept::get($id);
                $deptInfo->type = $organiDate['type'];
                $deptInfo->name = $organiDate['name'];
                $deptInfo->parentid = $organiDate['parentid'];
                $deptInfo->remark = $organiDate['remark'];
                $deptInfo->sort = $organiDate['sort'];
                $deptInfo->update_time = time();
                $deptInfo->save();
                return $this->buildSuccess('修改成功');
            }catch (\Exception $e){
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '修改失败!');
            }

        }

        /**
         * @api {post} admin/OrganiZation/delOrgani 删除部门[admin/OrganiZation/delOrgani ]
         * @apiVersion 1.0.0
         * @apiName delOrgani
         * @apiGroup OrganiZation
         * @apiSampleRequest admin/OrganiZation/delOrgani
         *
         * @apiParam {int}  id   主键id
         *
         */
        public function delOrgani(){
            $id = input('id');
            if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');
            try{
                $deptInfo = SystemDept::get($id);
                $deptInfo->status = -1;
                $deptInfo->delete_time = time();
                $deptInfo->save();
                return $this->buildSuccess('删除成功');
            }catch (\Exception $e){
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
            }

        }




}