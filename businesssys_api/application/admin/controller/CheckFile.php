<?php

/**
 * 查档控制器
 */

namespace app\admin\controller;

use think\Db;
use app\model\Estate;
use app\model\EstateInquiry;
use app\model\TrialFirst;
use app\model\Dictionary;
use app\model\SystemUser;
use app\util\ReturnCode;
use app\admin\service\Consultfiles;

class CheckFile extends Base {

    private $trialfirst;
    private $estate;
    private $estateinquiry;
    private $systemuser;
    private $dictionary;

    public function _initialize() {
        parent::_initialize();
        $this->trialfirst = new TrialFirst();
        $this->estateinquiry = new EstateInquiry();
        $this->estate = new Estate();
        $this->systemuser = new SystemUser();
        $this->dictionary = new Dictionary();
    }

    /**
     * @api {get} admin/CheckFile/getEstateinfo 获取订单房产信息[admin/CheckFile/getEstateinfo]
     * @apiVersion 1.0.0
     * @apiName getEstateinfo
     * @apiGroup CheckFile
     * @apiSampleRequest admin/CheckFile/getEstateinfo
     *
     * @apiParam {string} order_sn    订单号
     * 
     * @apiSuccess {array} base_data    担保信息
     * @apiSuccess {array} counter_data    反担保（为空时说明该订单不存在反担保房产）
     * @apiSuccess {array} review_data    资产证明（为空时说明该订单不存在资产证明房产）
     */
    public function getEstateinfo() {
        $order_sn = $this->request->get('order_sn', '');
        if ($order_sn) {
            $where = ['status' => 1, 'order_sn' => $order_sn];
            $checkisfist = $this->trialfirst->where($where)->field('is_guarantee,is_asset_prove,is_guarantee_estate')->find();
            $counter_data = [];
            $assets_data = [];
            $field = 'id,estate_owner,estate_name,estate_certtype,estate_certnum,house_type,estate_inquiry_status,estate_inquiry_time';
            $where = ['status' => 1, 'order_sn' => $order_sn];
            if ($checkisfist['is_guarantee'] == 1) {
                $where['estate_usage'] = 'FDB';
                $counter_data = $this->estate->getEstateinfo($where, $field);
            }
            if ($checkisfist['is_asset_prove'] == 1) {
                $where['estate_usage'] = 'ZCZM';
                $assets_data = $this->estate->getEstateinfo($where, $field);
            }
            $where['estate_usage'] = 'DB';
            $base_data = $this->estate->getEstateinfo($where, $field);
            //if (!empty($base_data)) {
            return $this->buildSuccess(['base_data' => $base_data, 'assets_data' => $assets_data, 'counter_data' => $counter_data]);
            //} else {
            //return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '此订单信息存在错误，请确认后重试');
            //}
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/CheckFile/checkAgain 再次查档[admin/CheckFile/checkAgain]
     * @apiVersion 1.0.0
     * @apiName checkAgain
     * @apiGroup CheckFile
     * @apiSampleRequest admin/CheckFile/checkAgain
     *
     * @apiParam {string} id    房产id
     * 
     * @apiSuccess {string} data    查询状态（红本在收，抵押等）
     */
    public function checkAgain() {
        $id = $this->request->get('id', '');
        if ($id) {
            $where = ['status' => 1, 'id' => $id];
            $field = 'order_sn,house_type,estate_owner';
            $adddata = $this->estate->getonlyEstateinfo($where, $field);
            if (!empty($adddata)) {
                $adddata['user_id'] = $this->userInfo['id'];
                if (empty($adddata['user_id']))
                    return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
                $adddata['create_time'] = time();
                $adddata['dept_id'] = $this->systemuser->where('id', $adddata['user_id'])->value('deptid');
                $adddata['estate_id'] = $id;
                //****调查档的接口******//
                $consultfile = new Consultfiles;
                $res = $consultfile->Consultfiles($where);
                Db::startTrans();
                try {
                    if ($res['code'] == -1) {
                        $adddata['estate_inquiry_text'] = $res['estate_inquiry_text']; //获取到查询房产最新状态内容
                        $adddata['result_code'] = $res['result_code']; //查档接口返回值
                        if ($this->estateinquiry->insert($adddata)) {//新增操作记录
                            Db::commit();
                            return $this->buildFailed(ReturnCode::UNKNOWN, $res['msg']);
                        }
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::ADD_FAILED, '操作记录新增失败！');
                    } else {
                        $estate_inquiry_status = $this->dictionary->getcodeByvalname('PROPERTY_STATUS', $res['estatestatus']); //根据返回的查询结果获得房产状态
                        $adddata['estate_inquiry_status'] = $estate_inquiry_status; //获取到查询房产最新状态
                        $adddata['estate_inquiry_text'] = $res['estatestatus']; //获取到查询房产最新状态内容
                        $adddata['result_code'] = 1; //查档接口返回值
                        if ($this->estateinquiry->insert($adddata)) {//新增操作记录
                            if ($this->estate->updateEstate($id, ['estate_inquiry_time' => time(), 'estate_inquiry_status' => $estate_inquiry_status])) {//更新房产最新状态
                                Db::commit();
                                return $this->buildSuccess($res['estatestatus']);
                            }
                            Db::rollback();
                            return $this->buildFailed(ReturnCode::ADD_FAILED, '房产查档信息更新失败！');
                        }
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::ADD_FAILED, '操作记录新增失败！');
                    }
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            } else {
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '该房产信息不存在！');
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/CheckFile/checkRecords 房产查询操作记录[admin/CheckFile/checkRecords]
     * @apiVersion 1.0.0
     * @apiName checkRecords
     * @apiGroup CheckFile
     * @apiSampleRequest admin/CheckFile/checkRecords
     *
     * @apiParam {string} id    房产id
     * 
     * @apiSuccess {array}  data  操作记录信息
     * @apiSuccess {string}  estate_name  房产名称
     */
    public function checkRecords() {
        $id = $this->request->get('id', '');
        if ($id) {
            $where = ['estate_id' => $id];
            $estate_name = $this->estate->where('id', $id)->value('estate_name');
            $field = 'estate_id,house_type,estate_owner,estate_inquiry_status,estate_inquiry_text,create_time,user_id,dept_id';
            $data = $this->estateinquiry->getCheckrecord($where, $field);
            return $this->buildSuccess(['data' => $data, 'estate_name' => $estate_name]);
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

}
