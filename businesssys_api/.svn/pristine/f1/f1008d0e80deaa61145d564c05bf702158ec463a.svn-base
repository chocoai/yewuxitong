<?php

/* 客户信息接口控制器 */

namespace app\admin\controller;

use app\model\Customer as modelCustomer;
use app\model\Dictionary;
use app\model\CreditInquiry;
use app\util\ReturnCode;
use app\admin\service\Zcdc;
use think\Exception;
use think\Loader;

class Customer extends Base {

    private $modelCustomer;
    private $dictionary;
    private $creditinquiry;

    public function _initialize() {
        parent::_initialize();
        $this->modelCustomer = new modelCustomer();
        $this->dictionary = new Dictionary();
        $this->creditinquiry = new CreditInquiry();
    }

    /**
     * @api {post} admin/Customer/getCusinfo 根据证件号自动匹配用户信息[admin/Customer/getCusinfo]
     * @apiVersion 1.0.0
     * @apiName getCusinfo
     * @apiGroup Customer
     * @apiSampleRequest admin/Customer/getCusinfo
     *
     * @apiParam {int} certcode    证件号码
     * @apiParam {int} certtype    证件类型
     * @apiParam {int} type    类型： 1 个人 2企业
     *
     * @apiSuccess {array} selectdata    用户名称下拉框数据
     * @apiSuccess {array} carddata    证件信息
     *
     */
    public function getCusinfo() {
        $type = input('post.type', 0, 'int');
        $certcode = input('post.certcode', '', 'trim');
        $certtype = input('post.certtype', '', 'trim');
        $zcdc = new Zcdc;
        $result = $zcdc->cerditcustomerList(['type' => $type, 'certcode' => $certcode, 'certtype' => $certtype]);
        if (is_array($result)) {
            if ($result['code'] === 1)
                return $this->buildSuccess($result['data']);
        }
        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $result !== false ? $result : '客户信息读取失败');
    }

    /**
     * @api {post} admin/Customer/addCard 新增证件[admin/Customer/addCard]
     * @apiVersion 1.0.0
     * @apiName addCard
     * @apiGroup Customer
     * @apiSampleRequest admin/Customer/addCard
     *
     * @apiParam {int} id    用户id（老系统用户id）
     * @apiParam {int} type    1个人2企业
     * @apiParam {array} certdata    证件信息
     *
     */
    public function addCard() {
        $adddata = $this->request->Post();
        if (!empty($adddata['certdata']) && $adddata['id']) {
            foreach ($adddata['certdata'] as $k => $v) {
                if (empty($v['certtype']) || empty($v['certcode'])) {
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '证件信息不全,未选择证件类型或未填写证件号码');
                }
            }
            $zcdc = new Zcdc;
            $res = $zcdc->getPostData('customer/addCard', ['id' => $adddata['id'], 'certdata' => $adddata['certdata']]);
            $typecode = $adddata['type'] == 1 ? 'CERTTYPE' : 'ENTERPRICE_CERTTYPE';
            if ($res['code'] == 1) {
                foreach ($res['data'] as $key => $value) {
                    $res['data'][$key]['certname'] = $this->dictionary->getValnameByCode($typecode, $value['certtype']);
                }
                return $this->buildSuccess($res['data']);
            }
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, $res['msg']);
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!');
        }
    }

    /**
     * @api {post} admin/Customer/zcCustomer 获取客户管理系统客户列表[admin/Customer/zcCustomer]
     * @apiVersion 1.0.0
     * @apiName zcCustomer
     * @apiGroup Customer
     * @apiSampleRequest admin/Customer/zcCustomer
     *
     * @apiParam {int} type    客户类型
     * @apiParam {str} certType    证件类型
     * @apiParam {str} certcode    证件号码
     */
    public function zcCustomer() {
        $type = input('post.type', 0, 'int');
        $certType = input('post.certType', '');
        $certcode = input('post.certcode', '', 'trim');

        $zcdc = new Zcdc;
        $result = $zcdc->customerList(['type' => $type, 'certType' => $certType, 'certcode' => $certcode]);


        if (is_array($result)) {
            if ($result['code'] === 1)
                return $this->buildSuccess($result['data']);
        }
        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $result !== false ? $result : '客户信息读取失败');
    }

    /**
     * @api {post} admin/Customer/addZCCustomer 添加客户管理系统客户[admin/Customer/addZCCustomer]
     * @apiVersion 1.0.0
     * @apiName addZCCustomer
     * @apiGroup Customer
     * @apiSampleRequest admin/Customer/addZCCustomer
     * @apiParam {int} type    客户类型
     * @apiParam {array} name    客户姓名
     * @apiParam {array} gender    客户性别（征信才需要）
     * @apiParam {str} mobile    电话
     * @apiParam {array} certdata    证件数据 certcode证件号码certtype证件类型
     * @apiParam {string} customermanager    客户经理（征信才需要）
     */
    public function addZCCustomer() {
        try {
            $certData = input('post.certdata/a');
            $data['ctype'] = input('post.type');
            $data['cname'] = input('post.name');
            $data['mobile'] = input('post.mobile', '');
            $validate = loader::validate('AddQuota');
            if (!$validate->scene('checkmobile')->check($data)) {
                return $this->buildFailed(ReturnCode::PARAM_INVALID, $validate->getError());
            }
            $customermanager = input('post.customermanager');
            $data['gender'] = $data['ctype'] == 1 ? input('post.gender') : 0;
            !empty($customermanager) && $data['customermanager'] = $customermanager;
            if (!is_array($certData) || empty($certData) || empty($data['cname']) || ($data['ctype'] != '1' && $data['ctype'] != '2'))
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!');
            if (count(array_column($certData, 'certtype')) !== count(array_unique(array_column($certData, 'certtype')))) {
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '请确定是否重复提交相同类型证件!');
            }
            $arr = $this->dealwithCarddata($certData);
            if ($arr === false)
                return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!');
            $data['certcode'] = $arr['certcode'];
            $data['certtype'] = $arr['certtype'];
            $data['certother'] = $certData;
            $zcdc = new Zcdc;
            $result = $zcdc->addCustomer($data);
            if ($result === false)
                return $this->buildFailed(ReturnCode::DB_READ_ERROR, '添加客户失败!');
            if ($result['code'] === 1)
                return $this->buildSuccess($result['data']);
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, $result['msg']);
        } catch (Exception $e) {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Customer/updateZCCustomer 更新客户管理系统客户[admin/Customer/updateZCCustomer]
     * @apiVersion 1.0.0
     * @apiName updateZCCustomer
     * @apiGroup Customer
     * @apiSampleRequest admin/Customer/updateZCCustomer
     * @apiParam {int} id    客户id
     * @apiParam {str} mobile    电话
     */
    public function updateZCCustomer() {
        $data['id'] = input('post.id');
        $data['mobile'] = input('post.mobile');
        $validate = loader::validate('AddQuota');
        if (!$validate->scene('checkmobile')->check($data)) {
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $validate->getError());
        }
        $zcdc = new Zcdc;
        $result = $zcdc->updateCustomer($data);
        if (is_array($result)) {
            if ($result['code'] === 1)
                return $this->buildSuccess('客户信息更新成功');
        }
        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $result !== false ? $result : '客户更新失败');
    }

    /**
     * 处理证件信息
     * @param $data
     * @return array|bool
     */
    private function dealwithCarddata($data) {
        $newarray = array();
        foreach ($data as $val) {
            if (empty($val['certtype']) || empty($val['certcode']))
                return false; //验证证件
            $val['certtype'] == 1 && $newarray = $val;
        }
        empty($newarray) && $newarray = $data[0];
        return $newarray;
    }

}
