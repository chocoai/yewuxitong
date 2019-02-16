<?php

/* 银行控制器 */

namespace app\admin\controller;

use app\model\BankCard as BankCardModel;
use app\util\ReturnCode;

class BankCard extends Base {

    private $bankcardmodel;

    public function _initialize() {
        parent::_initialize();
        $this->bankcardmodel = new BankCardModel();
    }

    /**
     * @api {get} admin/BankCard/getAllbank 获取所有公司银行[admin/BankCard/getAllbank]
     * @apiVersion 1.0.0
     * @apiName getAllbank
     * @apiGroup BankCard
     * @apiSampleRequest admin/BankCard/getAllbank
     *
     * @apiParam {string} order_type 订单类型
     * 
     * @apiSuccess {int} id    数据id
     * @apiSuccess {int} name    银行别名（下拉时展示）
     * @apiSuccess {string} bank    银行
     * @apiSuccess {string} bank_account    银行账户
     * @apiSuccess {string} bank_card    银行卡号
     */
    public function getAllbank() {
        $order_type = $this->request->get('order_type', '');
        if ($order_type) {
            if ($order_type == 'JYDB') {
                $where['type'] = 4; //额度类出账
            } else {
                $where['type'] = 5; //现金类出账
            }
            $where['status'] = 1;
            $field = 'id,name,bank_account,bank_card,bank';
            $data = $this->bankcardmodel->where($where)->field($field)->order('sort DESC')->select();
            return $this->buildSuccess($data);
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

}
