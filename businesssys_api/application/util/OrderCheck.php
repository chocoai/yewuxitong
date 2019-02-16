<?php

/**
  订单验证
 */

namespace app\util;

use think\Loader;

class OrderCheck {

    private $request;
    private $type;
    private $checkData = array(
        'orderInfo' => [], //订单信息
        'customerInfo' => [], //客户信息
        'estateData' => [], //房产信息
        'guaranteeBankInfo' => [], //银行账户信息
        'mortgageInfo' => [], //按揭信息
        'guaranteeInfo' => [], //赎楼信息
        'dpInfo' => [], //首期款信息
        'channelInfo' => [], //渠道信息
        'advanceData' => [], //垫资费计算
    ); //校验数据
    private $temporary = array(
        'is_need_verify_card' => 0, //是否核卡
    ); //临时数据处理

    function __construct() {
        $this->request = request();
        $this->temporary['buyWay'] = input('post.buyWay', ''); //购房方式
        $this->temporary['nowMortgageMoney'] = 0; //现按揭金额
        $this->temporary['is_combined_loan'] = 0; //是否组合贷
    }

    /* 订单验证基础方法 */

    public function baseCheck() {
        $result = $this->checkOrder();
        if ($result !== true)
            return $result;
        //校验订单客户信息
        $result = $this->checkCustomer();
        if ($result !== true)
            return $result;
        //校验房产
        $result = $this->checkEstate();
        if ($result !== true)
            return $result;
        return true;
    }

    /**
     * 校验交易担保
     * @return array|mixed|string|true
     */
    public function checkJYDB() {
        $this->type = 'JYDB';
        $result = $this->baseCheck();
        if ($result !== true)
            return $result;
        //校验按揭信息
        $result = $this->checkMortgage();
        if ($result !== true)
            return $result;
        //校验首期款信息
        $result = $this->checkDp();
        if ($result !== true)
            return $result;
        //校验赎楼信息
        $result = $this->checkGuarantee();
        if ($result !== true)
            return $result;
        //验证赎楼银行信息
        $result = $this->checkGuaranteeBank();
        if ($result !== true)
            return $result;
        $this->checkData['guaranteeInfo']['is_foreclosure'] = 1; //需要赎楼
        $this->checkData['dpInfo']['dp_now_mortgage'] = $this->temporary['nowMortgageMoney'] > 0 ? sprintf("%.2f", $this->temporary['nowMortgageMoney'] / $this->temporary['strikePrice'] * 10) : 0; //现按揭成数
        if ($this->checkData['dpInfo']['dp_now_mortgage'] > 10)
            return '现按揭成数不能大于10成';
        $this->checkData['guaranteeInfo']['is_need_verify_card'] = $this->temporary['is_need_verify_card']; //核卡
        return $this->checkData;
    }

    /**
     * 校验现金交易订单
     * @return array|mixed|string|true
     */
    public function checkJYXJ() {
        $this->type = 'JYXJ';
        $result = $this->baseCheck();
        if ($result !== true)
            return $result;

        //校验按揭信息
        $result = $this->checkJYXJMortgage();
        if ($result !== true)
            return $result;

        //校验首期款信息
        $result = $this->checkDp();
        if ($result !== true)
            return $result;

        //交易赎楼信息
        $result = $this->checkJYXJGuarantee();
        if ($result !== true)
            return $result;

        //校验垫资费
        $result = $this->checkJYXJAdvance();
        if ($result !== true)
            return $result;
        //校验渠道信息
        $result = $this->checkChannel();
        if ($result !== true)
            return $result;
        //验证赎楼银行信息
        $result = $this->checkGuaranteeBank();
        if ($result !== true)
            return $result;
        $this->checkData['guaranteeInfo']['money'] = $this->temporary['total']; //订单金额（渠道总资金）
        $this->checkData['guaranteeInfo']['guarantee_fee'] = $this->temporary['guarantee_fee']; //垫资费总计
        $this->checkData['guaranteeInfo']['total_fee'] = $this->temporary['guarantee_fee'] + $this->checkData['guaranteeInfo']['fee']; //垫资费总计+手续费
        $this->checkData['guaranteeInfo']['guarantee_per'] = sprintf('%.2f', $this->temporary['total'] / $this->checkData['dpInfo']['dp_strike_price'] * 10); //渠道信息-垫资层数
        $messsage = $this->checkDZGuaranteeperCount($this->checkData['guaranteeInfo']['guarantee_per']);
        if ($messsage !== 1)
            return $messsage;
        $this->checkData['guaranteeInfo']['is_dispatch'] = 1;
//        $this->checkData['guaranteeInfo']['is_instruct'] = $this->temporary['is_instruct'];
//        $this->checkData['guaranteeInfo']['instruct_status'] = $this->temporary['instruct_status'];
        $this->checkData['guaranteeInfo']['is_foreclosure'] = 1; //需要赎楼
        $this->checkData['guaranteeInfo']['is_combined_loan'] = $this->temporary['is_combined_loan']; //是否组合贷
//        $this->checkData['guaranteeInfo']['com_loan_money'] = $this->temporary['com_loan_money']; //公司放款金额
//        $this->checkData['guaranteeInfo']['is_loan_finish'] = $this->temporary['is_loan_finish']; //放款放款是否完成
        $this->checkData['dpInfo']['dp_now_mortgage'] = $this->temporary['nowMortgageMoney'] > 0 ? sprintf("%.2f", $this->temporary['nowMortgageMoney'] / $this->temporary['strikePrice'] * 10) : 0; //现按揭成数
        if ($this->checkData['dpInfo']['dp_now_mortgage'] > 10)
            return '现按揭成数不能大于10成';
        $this->checkData['guaranteeInfo']['is_need_verify_card'] = $this->temporary['is_need_verify_card']; //核卡
        return $this->checkData;
    }

    /**
     * 校验非交易现金订单
     * @return array|mixed|string|true
     */
    public function checkTMXJ() {
        $this->type = 'TMXJ';
        //校验订单
        $result = $this->checkOrder();
        if ($result !== true)
            return $result;

        //校验订单客户信息
        $result = $this->checkTMXJCustomer();
        if ($result !== true)
            return $result;
        //校验房产
        $result = $this->checkEstate();
        if ($result !== true)
            return $result;

        //校验按揭信息
        $result = $this->checkJYXJMortgage();
        if ($result !== true)
            return $result;

        //交易赎楼信息
        $result = $this->checkTMXJGuarantee();
        if ($result !== true)
            return $result;

        //校验垫资费
        $result = $this->checkJYXJAdvance();
        if ($result !== true)
            return $result;
        //校验渠道信息
        $result = $this->checkChannel();
        if ($result !== true)
            return $result;
        //验证赎楼银行信息
        $result = $this->checkGuaranteeBank();
        if ($result !== true)
            return $result;
        $this->checkData['guaranteeInfo']['money'] = $this->temporary['total']; //订单金额（渠道总资金）
        $this->checkData['guaranteeInfo']['guarantee_fee'] = $this->temporary['guarantee_fee']; //垫资费总计
        $this->checkData['guaranteeInfo']['guarantee_per'] = sprintf('%.2f', $this->temporary['total'] / $this->checkData['guaranteeInfo']['evaluation_price'] * 10); //渠道信息-垫资层数
        $messsage = $this->checkDZGuaranteeperCount($this->checkData['guaranteeInfo']['guarantee_per']);
        if ($messsage !== 1)
            return $messsage;
        $this->checkData['guaranteeInfo']['total_fee'] = $this->temporary['guarantee_fee'] + $this->checkData['guaranteeInfo']['fee']; //垫资费总计+手续费
        $businnessType = input('post.business_type'); //非交易类型
        if ($businnessType == '1' || $businnessType == '2') {
            $this->checkData['orderInfo']['business_type'] = $businnessType;
        } else {
            return '非交易类型有误';
        }

        $this->checkData['guaranteeInfo']['is_dispatch'] = 1;
//        $this->checkData['guaranteeInfo']['is_instruct'] = $this->temporary['is_instruct'];
//        $this->checkData['guaranteeInfo']['instruct_status'] = $this->temporary['instruct_status'];
        $this->checkData['guaranteeInfo']['is_foreclosure'] = 1; //需要赎楼
        $this->checkData['guaranteeInfo']['is_combined_loan'] = $this->temporary['is_combined_loan']; //是否组合贷
//        $this->checkData['guaranteeInfo']['com_loan_money'] = $this->temporary['com_loan_money']; //公司放款金额
        $this->checkData['guaranteeInfo']['account_per'] = sprintf("%1.2f", $this->checkData['guaranteeInfo']['out_account_total'] / $this->temporary['evaluation_price'] * 10); //出账成数
//        $this->checkData['guaranteeInfo']['is_loan_finish'] = $this->temporary['is_loan_finish']; //放款放款是否完成
        $this->checkData['guaranteeInfo']['now_mortgage'] = $this->temporary['nowMortgageMoney'] > 0 ? sprintf("%.2f", $this->temporary['nowMortgageMoney'] / $this->temporary['evaluation_price'] * 10) : 0; //现按揭成数
        if ($this->checkData['guaranteeInfo']['now_mortgage'] > 10)
            return '现按揭成数不能大于10成';
        $this->checkData['guaranteeInfo']['is_need_verify_card'] = $this->temporary['is_need_verify_card']; //核卡
        return $this->checkData;
    }

    /**
     * 校验凭抵押回执放款
     * @return array|mixed|string|true
     */
    public function checkPDXJ() {
        $this->type = 'PDXJ';
        $result = $this->baseCheck();
        if ($result !== true)
            return $result;

        //校验按揭信息
        $result = $this->checkPDXJMortgage();
        if ($result !== true)
            return $result;

        //校验首期款信息
        $result = $this->checkDp();
        if ($result !== true)
            return $result;

        //交易赎楼信息
        $result = $this->checkPDXJGuarantee();
        if ($result !== true)
            return $result;

        //校验垫资费
        $result = $this->checkJYXJAdvance();
        if ($result !== true)
            return $result;
        //校验渠道信息
        $result = $this->checkChannel();
        if ($result !== true)
            return $result;
        //验证赎楼银行信息
        $result = $this->checkPDXJGuaranteeBank();
        if ($result !== true)
            return $result;
        $this->checkData['guaranteeInfo']['money'] = $this->temporary['total']; //订单金额（渠道总资金）
        $this->checkData['guaranteeInfo']['guarantee_fee'] = $this->temporary['guarantee_fee']; //垫资费总计
        $this->checkData['guaranteeInfo']['guarantee_per'] = sprintf('%.2f', $this->temporary['total'] / $this->checkData['dpInfo']['dp_strike_price'] * 10); //渠道信息-垫资层数
        $messsage = $this->checkDZGuaranteeperCount($this->checkData['guaranteeInfo']['guarantee_per']);
        if ($messsage !== 1)
            return $messsage;
        $this->checkData['guaranteeInfo']['total_fee'] = $this->temporary['guarantee_fee'] + $this->checkData['guaranteeInfo']['fee']; //垫资费总计+手续费
        $this->checkData['guaranteeInfo']['is_dispatch'] = 1;
//        $this->checkData['guaranteeInfo']['is_instruct'] = $this->temporary['is_instruct'];
//        $this->checkData['guaranteeInfo']['instruct_status'] = $this->temporary['instruct_status'];
//        $this->checkData['guaranteeInfo']['com_loan_money'] = $this->temporary['com_loan_money']; //公司放款金额
//        $this->checkData['guaranteeInfo']['is_loan_finish'] = $this->temporary['is_loan_finish']; //放款放款是否完成
        $this->checkData['dpInfo']['dp_now_mortgage'] = $this->temporary['nowMortgageMoney'] > 0 ? sprintf("%.2f", $this->temporary['nowMortgageMoney'] / $this->temporary['strikePrice'] * 10) : 0; //现按揭成数
        if ($this->checkData['dpInfo']['dp_now_mortgage'] > 10)
            return '现按揭成数不能大于10成';
        $this->checkData['guaranteeInfo']['is_need_verify_card'] = $this->temporary['is_need_verify_card']; //核卡
        return $this->checkData;
    }

    /**
     * 校验更名垫资
     * @return array|mixed|string|true
     */
    public function checkGMDZ() {
        $this->type = 'GMDZ';
        //校验订单
        $result = $this->checkOrder();
        if ($result !== true)
            return $result;

        //校验订单客户信息
        $result = $this->checkTMXJCustomer();
        if ($result !== true)
            return $result;
        //校验房产
        $result = $this->checkEstate();
        if ($result !== true)
            return $result;

        //校验按揭信息
        $result = $this->checkJYXJMortgage();
        if ($result !== true)
            return $result;
        //交易赎楼信息
        $result = $this->checkTMXJGuarantee();
        if ($result !== true)
            return $result;
        //校验垫资费
        $result = $this->checkJYXJAdvance();
        if ($result !== true)
            return $result;
        //校验渠道信息
        $result = $this->checkChannel();
        if ($result !== true)
            return $result;
        //验证赎楼银行信息
        $result = $this->checkGuaranteeBank();
        if ($result !== true)
            return $result;
        $this->checkData['guaranteeInfo']['money'] = $this->temporary['total']; //订单金额（渠道总资金）
        $this->checkData['guaranteeInfo']['guarantee_fee'] = $this->temporary['guarantee_fee']; //垫资费总计
        $this->checkData['guaranteeInfo']['guarantee_per'] = sprintf('%.2f', $this->temporary['total'] / $this->checkData['guaranteeInfo']['evaluation_price'] * 10); //渠道信息-垫资层数
        $messsage = $this->checkDZGuaranteeperCount($this->checkData['guaranteeInfo']['guarantee_per']);
        if ($messsage !== 1)
            return $messsage;
        $this->checkData['guaranteeInfo']['total_fee'] = $this->temporary['guarantee_fee'] + $this->checkData['guaranteeInfo']['fee']; //垫资费总计+手续费
        $this->checkData['guaranteeInfo']['is_dispatch'] = 1;
//        $this->checkData['guaranteeInfo']['is_instruct'] = $this->temporary['is_instruct'];
//        $this->checkData['guaranteeInfo']['instruct_status'] = $this->temporary['instruct_status'];
        $this->checkData['guaranteeInfo']['is_foreclosure'] = 1; //需要赎楼
        $this->checkData['guaranteeInfo']['is_combined_loan'] = $this->temporary['is_combined_loan']; //是否组合贷
//        $this->checkData['guaranteeInfo']['com_loan_money'] = $this->temporary['com_loan_money']; //公司放款金额
        $this->checkData['guaranteeInfo']['account_per'] = sprintf("%1.2f", $this->checkData['guaranteeInfo']['out_account_total'] / $this->temporary['evaluation_price'] * 10); //出账成数
//        $this->checkData['guaranteeInfo']['is_loan_finish'] = $this->temporary['is_loan_finish']; //放款放款是否完成
        $this->checkData['guaranteeInfo']['now_mortgage'] = $this->temporary['nowMortgageMoney'] > 0 ? sprintf("%.2f", $this->temporary['nowMortgageMoney'] / $this->temporary['evaluation_price'] * 10) : 0; //现按揭成数
        if ($this->checkData['guaranteeInfo']['now_mortgage'] > 10)
            return '现按揭成数不能大于10成';
        $this->checkData['guaranteeInfo']['is_need_verify_card'] = $this->temporary['is_need_verify_card']; //核卡
        return $this->checkData;
    }

    /**
     * 校验首期垫款
     * @return array|mixed|string|true
     */
    public function checkSQDZ() {
        $this->type = 'SQDZ';
        $result = $this->baseCheck();
        if ($result !== true)
            return $result;
        //校验首期款信息
        $result = $this->checkDp();
        if ($result !== true)
            return $result;

        //校验按揭信息
        $result = $this->checkSQDZMortgage();
        if ($result !== true)
            return $result;


        //交易赎楼信息
        $result = $this->checkPDXJGuarantee();
        if ($result !== true)
            return $result;

        //校验垫资费
        $result = $this->checkJYXJAdvance();
        if ($result !== true)
            return $result;
        //校验渠道信息
        $result = $this->checkChannel();
        if ($result !== true)
            return $result;
        //验证赎楼银行信息
        $result = $this->checkGuaranteeBank();
        if ($result !== true)
            return $result;
        $this->checkData['guaranteeInfo']['money'] = $this->temporary['total']; //订单金额（渠道总资金）
        $this->checkData['guaranteeInfo']['guarantee_fee'] = $this->temporary['guarantee_fee']; //垫资费总计
        $this->checkData['guaranteeInfo']['guarantee_per'] = sprintf('%.2f', $this->temporary['total'] / $this->checkData['dpInfo']['dp_strike_price'] * 10); //渠道信息-垫资层数
        $messsage = $this->checkDZGuaranteeperCount($this->checkData['guaranteeInfo']['guarantee_per']);
        if ($messsage !== 1)
            return $messsage;

        $this->checkData['guaranteeInfo']['total_fee'] = $this->temporary['guarantee_fee'] + $this->checkData['guaranteeInfo']['fee']; //垫资费总计+手续费
        $businnessType = input('post.business_type'); //首期款垫资类型
        if ($businnessType == '1' || $businnessType == '2' || $businnessType == '3' || $businnessType == '4') {
            $this->checkData['orderInfo']['business_type'] = $businnessType;
        } else {
            return '首期垫资类型有误';
        }
        $this->checkData['guaranteeInfo']['is_dispatch'] = 1;
//        $this->checkData['guaranteeInfo']['is_instruct'] = $this->temporary['is_instruct'];
//        $this->checkData['guaranteeInfo']['instruct_status'] = $this->temporary['instruct_status'];
//        $this->checkData['guaranteeInfo']['com_loan_money'] = $this->temporary['com_loan_money']; //公司放款金额
//        $this->checkData['guaranteeInfo']['is_loan_finish'] = 1; //放款已完成
        $this->checkData['dpInfo']['dp_now_mortgage'] = $this->temporary['nowMortgageMoney'] > 0 ? sprintf("%.2f", $this->temporary['nowMortgageMoney'] / $this->temporary['strikePrice'] * 10) : 0; //现按揭成数
        if ($this->checkData['dpInfo']['dp_now_mortgage'] > 10)
            return '现按揭成数不能大于10成';
        $this->checkData['guaranteeInfo']['is_need_verify_card'] = 0; //首期款业务不需要核卡
        return $this->checkData;
    }

    /**
     * 校验短期借款
     * @return array|mixed|string|true
     */
    public function checkDQJK() {
        $this->type = 'DQJK';
        //校验订单
        $result = $this->checkOrder();
        if ($result !== true)
            return $result;
        //校验垫资费
        $result = $this->checkJYXJAdvance();
        if ($result !== true)
            return $result;
        //校验渠道信息
        $result = $this->checkChannel();
        if ($result !== true)
            return $result;

        //交易赎楼信息
        $result = $this->checkDQJKGuarantee();
        if ($result !== true)
            return $result;
        //验证赎楼银行信息
        $result = $this->checkDQJKGuaranteeBank();
        if ($result !== true)
            return $result;
        $this->checkData['guaranteeInfo']['money'] = $this->temporary['total']; //订单金额（渠道总资金）
        $this->checkData['guaranteeInfo']['guarantee_fee'] = $this->temporary['guarantee_fee']; //垫资费总计
        $this->checkData['guaranteeInfo']['total_fee'] = $this->temporary['guarantee_fee'] + $this->checkData['guaranteeInfo']['fee']; //垫资费总计+手续费
        $this->checkData['guaranteeInfo']['is_dispatch'] = 1;
//        $this->checkData['guaranteeInfo']['is_instruct'] = $this->temporary['is_instruct'];
//        $this->checkData['guaranteeInfo']['instruct_status'] = $this->temporary['instruct_status'];
//        $this->checkData['guaranteeInfo']['com_loan_money'] = $this->temporary['com_loan_money']; //公司放款金额
//        $this->checkData['guaranteeInfo']['is_loan_finish'] = 1; //放款已完成
        $this->checkData['guaranteeInfo']['is_need_verify_card'] = 1; //短期借款必须核卡
        return $this->checkData;
    }

    /**
     * 校验订单信息
     * @return array
     */
    private function checkOrder() {
        //验证订单信息
        $orderData['type'] = $this->request->post('type');
        $orderData['category'] = $this->request->post('category');
        $orderData['money'] = $this->request->post('money');
        $orderData['financing_manager_id'] = $this->request->post('financingManager');
        $orderData['financing_dept_id'] = $this->request->post('depId'); //部门ID
        $orderData['mortgage_name'] = $this->request->post('mortgageName'); //按揭姓名
        $orderData['mortgage_mobile'] = $this->request->post('mortgageMobile');
        $orderData['dept_manager_id'] = $this->request->post('managerId'); //部门经理ID
        $orderVail = validate('OrderValidate');
        $msg = $orderVail->check($orderData);
        if ($msg !== true)
            return $orderVail->getError();
        $orderSource = $this->request->post('orderSource', '', 'int');
        if ($orderSource) {
            $orderData['order_source'] = $orderSource;
        } else {
            $orderData['order_source'] = null;
        }
        $orderData['source_info'] = $this->request->post('sourceInfo', '');
        $orderData['remark'] = input('post.remark', '');
        $this->checkData['orderInfo'] = $orderData;
        unset($orderData);
        return true;
    }

    /**
     * 校验房产
     * @param $nameStr 产权人姓名
     * @param $ownerType  产权类型
     * @return array|mixed|string|true
     */
    private function checkEstate() {
        $estateDatas = input('post.estateData/a');

        //校验房产信息
        if (!$estateDatas || !is_array($estateDatas))
            return '房产信息不能为空';
        $estateValidate = validate('EstateValidate');
        foreach ($estateDatas as $key => $val) {
            $estateDatas[$key]['estate_name'] = trim($val['estate_name']);
            $estateDatas[$key]['building_name'] = trim($val['building_name']);
            $estateDatas[$key]['estate_alias'] = trim($val['estate_alias']);
            $estateDatas[$key]['estate_unit'] = trim($val['estate_unit']);
            $estateDatas[$key]['estate_unit_alias'] = trim($val['estate_unit_alias']);
            $estateDatas[$key]['estate_house'] = trim($val['estate_house']);
            $msg = $estateValidate->check($estateDatas[$key]);
            if ($msg !== true)
                return $estateValidate->getError();
            $estateDatas[$key]['estate_owner'] = $this->temporary['nameStr'];
            $estateDatas[$key]['estate_owner_type'] = $this->temporary['ownerType'];
            isset($val['house_id']) && !empty($val['house_id']) && $estateDatas[$key]['house_id'] = $val['house_id'];
        }
        $this->checkData['estateData'] = $estateDatas;
        unset($estateDatas);
        return true;
    }

    /**
     * 校验订单客户
     * @return array
     */
    private function checkCustomer() {
        $financingManagerId = $this->checkData['orderInfo']['financing_manager_id'];
        /* 获取卖方信息 */
        $sellerInfos = $this->request->post('seller/a');
        $isSellerComborrower = $this->request->post('isSellerComborrower'); //0无1有共同借款人
        $isBuyerComborrower = $this->request->post('isBuyerComborrower');
        if (($isSellerComborrower !== '1' && $isSellerComborrower !== '0') || ($isBuyerComborrower !== '1' && $isBuyerComborrower !== '0'))
            return '客户信息参数有误';
        $isguarantee = 0; //是否担保申请人
        $guaranteeCustomer = false; //担保申请人一方'
        $sellerIsset = 0; //卖方信息判断
        $buyIsset = 0; //买方信息判断
        $sellerComborrowerInfo = 0; //是否存在卖方共同借款人信息
        $buyerComborrowerInfo = 0; //是否存在买方共同借款人信息
        if (!$sellerInfos)
            return '卖方信息为空';
        $estateCustomer = [];
        $nameStr = '';
        $ownerType = '';
        $customerVail = validate('CustomerValidate');

        foreach ($sellerInfos as $key => $sellerInfo) {

            if (empty($sellerInfo['certtype']) || empty($sellerInfo['certcode']))
                return '证件信息有误'; //验证证件
            $vailArr = [];
            $vailArr['certtype'] = $sellerInfo['certtype'];
            $vailArr['certcode'] = $sellerInfo['certcode'];
            $vailArr['financing_manager_id'] = $financingManagerId;
            $vailArr['ctype'] = $sellerInfo['ctype'];
            $vailArr['is_seller'] = $sellerInfo['is_seller'];
            $vailArr['is_comborrower'] = $sellerInfo['is_comborrower'];
            $vailArr['cname'] = $sellerInfo['cname'];
            $vailArr['mobile'] = $sellerInfo['mobile'];
            $vailArr['is_guarantee'] = $sellerInfo['is_guarantee'];
            $vailArr['datacenter_id'] = $sellerInfo['datacenter_id'];
            $msg = $customerVail->check($vailArr);
            if ($msg !== true)
                return $customerVail->getError();
            if ($vailArr['is_seller'] == '2' && $vailArr['is_comborrower'] == '0') {
                $nameStr === '' ? $nameStr = $vailArr['cname'] : $nameStr .= ',' . $vailArr['cname'];
                $estateCustomer[]['customer_id'] = $vailArr['datacenter_id'];
                $ownerType === '' && $ownerType = $vailArr['ctype'];
            }
            if ($isguarantee === 1 && $sellerInfo['is_guarantee'] == '1' && $guaranteeCustomer == $sellerInfo['is_seller'])
                return '买卖双方只能有一方是担保申请人';
            $isguarantee === 0 && $sellerInfo['is_guarantee'] == '1' && $isguarantee = 1 && $guaranteeCustomer = $sellerInfo['is_seller'];
            ($sellerIsset === 0 && $vailArr['is_seller'] == '2') && $sellerIsset = 1;
            ($buyIsset === 0 && $vailArr['is_seller'] == '1') && $buyIsset = 1;
            ($isSellerComborrower == '1' && $sellerComborrowerInfo === 0 && ( $sellerInfo['is_seller'] == '2' && $sellerInfo['is_comborrower'] == '1')) && $sellerComborrowerInfo = 1;
            ($isBuyerComborrower == '1' && $buyerComborrowerInfo === 0 && ( $sellerInfo['is_seller'] == '1' && $sellerInfo['is_comborrower'] == '1')) && $buyerComborrowerInfo = 1;
        }
        if ($sellerIsset === 0)
            return '卖方信息不存在';
        if ($buyIsset === 0)
            return '买方信息不存在';
        if (($isSellerComborrower == '1' && $sellerComborrowerInfo === 0) || ($isBuyerComborrower == '1' && $buyerComborrowerInfo === 0))
            return '买方或卖方共同借款人信息为空';
        $this->checkData['customerInfo'] = $sellerInfos;
        $this->checkData['estateCustomer'] = $estateCustomer;
        $this->checkData['orderInfo']['is_comborrower_sell'] = $isSellerComborrower;
        $this->checkData['orderInfo']['is_comborrower_buy'] = $isBuyerComborrower;
        $this->temporary['nameStr'] = $nameStr;
        $this->temporary['ownerType'] = $ownerType;
        unset($sellerInfos, $estateCustomer);
        return true;
    }

    /**
     * 校验非交易现金客户信息
     * TMXJ、GMDZ
     * @return array|bool|string
     */
    private function checkTMXJCustomer() {

        /* 获取卖方信息 */
        $sellerInfos = $this->request->post('seller/a');
        $isSellerComborrower = $this->request->post('isSellerComborrower'); //0无1有共同借款人
        if (!$sellerInfos)
            return '卖方信息为空';
        $estateCustomer = [];
        $nameStr = '';
        $ownerType = '';
        $financingManagerId = $this->checkData['orderInfo']['financing_manager_id'];
        $customerVail = validate('CustomerValidate');
        foreach ($sellerInfos as $key => $sellerInfo) {
            if (empty($sellerInfo['certtype']) || empty($sellerInfo['certcode']))
                return '证件信息有误'; //验证证件
            $vailArr = [];
            $vailArr['certtype'] = $sellerInfo['certtype'];
            $vailArr['certcode'] = $sellerInfo['certcode'];
            $vailArr['financing_manager_id'] = $financingManagerId;
            $vailArr['ctype'] = $sellerInfo['ctype'];
            $vailArr['is_seller'] = $sellerInfo['is_seller'];
            $vailArr['is_comborrower'] = $sellerInfo['is_comborrower'];
            $vailArr['cname'] = $sellerInfo['cname'];
            $vailArr['mobile'] = $sellerInfo['mobile'];
            $vailArr['is_guarantee'] = $sellerInfo['is_guarantee'];
            $vailArr['datacenter_id'] = $sellerInfo['datacenter_id'];
            $msg = $customerVail->check($vailArr);
            if ($msg !== true)
                return $customerVail->getError();
            if ($vailArr['is_seller'] == '2' && $vailArr['is_comborrower'] == '0') {
                $nameStr === '' ? $nameStr = $vailArr['cname'] : $nameStr .= ',' . $vailArr['cname'];
                $estateCustomer[]['customer_id'] = $vailArr['datacenter_id'];
                $ownerType === '' && $ownerType = $vailArr['ctype'];
            }
        }
        $this->checkData['customerInfo'] = $sellerInfos;
        $this->checkData['estateCustomer'] = $estateCustomer;
        $this->checkData['orderInfo']['is_comborrower_sell'] = $isSellerComborrower;
        $this->temporary['nameStr'] = $nameStr;
        $this->temporary['ownerType'] = $ownerType;
        unset($sellerInfos, $estateCustomer);
        return true;
    }

    /**
     * 校验按揭信息(JYDB)
     * @return array|mixed|string
     */
    private function checkMortgage() {
        $mortgageDatas = $this->request->post('mortgageData/a');

        if (!$mortgageDatas || !is_array($mortgageDatas)) {
            return '按揭不能为空';
        }
        $is_combined_loan = 0;
        $isFund = 0;
        $isBusiness = 0;
        $isconsumption = 0;
        $orgMortgageData = [];
        $isnowMortGage = 0; //是否现按揭信息
        $mortgageVail = validate('Mortgage');
        foreach ($mortgageDatas as $mortgageData) {
            $msg = $mortgageVail->check($mortgageData);
            if ($msg !== true)
                return $mortgageVail->getError();
            if ($mortgageData['type'] == 'ORIGINAL') {
                $orgMortgageData[] = $mortgageData;
                $mortgageData['mortgage_type'] == '1' && $isFund = 1;
                $mortgageData['mortgage_type'] == '2' && $isBusiness = 1;
                $mortgageData['mortgage_type'] == '3' && $isconsumption = 1;
            } else {
                $isnowMortGage = 1;
                //全款购房
                if ($this->temporary['buyWay'] == 1) {
                    return '全款购房没有现按揭信息';
                }
                $this->temporary['nowMortgageMoney'] += $mortgageData['money'];
            }
        }
        if ($this->temporary['buyWay'] == 2 && $isnowMortGage == 0)
            return '按揭购房现按揭信息不能为空';
        $isFund === 1 && $isBusiness === 1 && $is_combined_loan = 1; //含有公积金和商业贷才是组合贷
        $isFund === 1 && $isconsumption === 1 && $is_combined_loan = 1; //含有公积金和加装消费贷也是组合贷
        if (!$orgMortgageData)
            return '原按揭信息不能为空';
        $redeembank = input('post.redeembank');
        if (!$redeembank)
            return '赎楼短贷银行不能为空';
        $cArr = $this->checkDispatch($redeembank, $orgMortgageData); //派单状态
        $this->checkData['mortgageInfo'] = $mortgageDatas;
        unset($mortgageDatas);
        $this->temporary['is_combined_loan'] = $is_combined_loan;
        $this->temporary['dispatch'] = $cArr[0]; //派单
        $this->temporary['instruct'] = $cArr[1]; //指令
        return true;
    }

    /**
     * 校验JYXJ按揭信息
     * JYXJ、GMDZ、TMXJ
     * @return array|mixed|string
     */
    private function checkJYXJMortgage() {
        $mortgageDatas = $this->request->post('mortgageData/a');
        if (!$mortgageDatas || !is_array($mortgageDatas)) {
            return '按揭不能为空';
        }

        $isFund = 0;
        $isBusiness = 0;
        $orgMortgageData = 0;
        $nowMortgageData = 0;
        $mortgageVail = validate('Mortgage');

        foreach ($mortgageDatas as $val) {
            $msg = $mortgageVail->check($val);
            if ($msg !== true)
                return $mortgageVail->getError();
            if ($val['type'] == 'ORIGINAL') {
                $orgMortgageData === 0 && $orgMortgageData = 1;
                $val['mortgage_type'] == '1' && $isFund = 1;
                $val['mortgage_type'] == '2' && $isBusiness = 1;
            } else {
                $nowMortgageData === 0 && $nowMortgageData = 1;
                //全款购房
                if ($this->temporary['buyWay'] == 1) {
                    return '全款购房没有现按揭信息';
                }
                $this->temporary['nowMortgageMoney'] += $val['money'];
            }
        }
        if ($this->type == 'TMXJ') {
            if ($orgMortgageData === 0 || $nowMortgageData === 0)
                return '原按揭或现按揭信息不能为空';
        }elseif ($this->type == 'GMDZ') {
            if ($orgMortgageData === 0)
                return '原按揭信息不能为空';
        }else {
            if ($orgMortgageData === 0 || ($this->temporary['buyWay'] == 2 && $nowMortgageData === 0))
                return '原按揭或现按揭信息不能为空';
        }

        $isFund === 1 && $isBusiness === 1 && $this->temporary['is_combined_loan'] = 1; //含有公积金和商业贷才是组合贷
        $this->checkData['mortgageInfo'] = $mortgageDatas;
        unset($orgMortgageData, $mortgageDatas);
        return true;
    }

    /**
     * 校验JYXJ按揭信息
     * PDXJ
     * @return array|mixed|string
     */
    private function checkPDXJMortgage() {
        $mortgageDatas = $this->request->post('mortgageData/a');

        if ($this->temporary['buyWay'] == '2') {
            $nowMortgageData = 0;
            $mortgageVail = validate('Mortgage');
            if ($mortgageDatas) {
                foreach ($mortgageDatas as $val) {
                    $msg = $mortgageVail->check($val);
                    if ($msg !== true)
                        return $mortgageVail->getError();
                    if ($val['type'] == 'NOW') {
                        $nowMortgageData === 0 && $nowMortgageData = 1;
                        $this->temporary['nowMortgageMoney'] += $val['money'];
                    } else {
                        return '当前订单按揭信息有误';
                    }
                }
            }
            if ($nowMortgageData === 0)
                return '现按揭信息不能为空';
            $this->checkData['mortgageInfo'] = $mortgageDatas;
            unset($mortgageDatas);
        }
        return true;
    }

    /**
     * 校验SQDZ按揭信息
     * @return array|mixed|string
     */
    private function checkSQDZMortgage() {
        $mortgageDatas = $this->request->post('mortgageData/a');
        if ($this->temporary['buyWay'] == 2) {
            if (!$mortgageDatas || !is_array($mortgageDatas))
                return '现按揭不能为空';
        }else {
            return true;
        }

        $nowMortgageData = 0;
        $mortgageVail = validate('Mortgage');

        foreach ($mortgageDatas as $val) {
            $msg = $mortgageVail->check($val);
            if ($msg !== true)
                return $mortgageVail->getError();
            if ($val['type'] == 'NOW') {
                $nowMortgageData === 0 && $nowMortgageData = 1;
                $this->temporary['nowMortgageMoney'] += $val['money'];
            } else {
                return '当前订单按揭信息有误';
            }
        }
        if ($nowMortgageData === 0)
            return '现按揭信息不能为空';
        $this->checkData['mortgageInfo'] = $mortgageDatas;
        unset($orgMortgageData, $mortgageDatas);
        return true;
    }

    /**
     * 校验首期款信息
     * @return array
     */
    private function checkDp() {
        $dpData['dp_strike_price'] = $this->request->post('strikePrice'); //首期款成交价
        $dpData['dp_earnest_money'] = $this->request->post('earnestMoney'); //首期款定金
        $dpData['dp_money'] = $this->request->post('dpMoney'); //首期款金额
        $dpBankinfo = $this->request->post('DpBankInfo/a'); //首期款监管银行
//        $dpData['dp_supervise_bank_branch'] = $this->request->post('dp_supervise_bank_branch'); //首期款监管银行支行
        $dpData['dp_buy_way'] = $this->request->post('buyWay'); //购房方式

        if ($this->type == 'JYDB') {
            $dpData['dp_redeem_bank'] = $this->request->post('redeembank', ''); //赎楼短贷银行
            $dpData['dp_redeem_bank_branch'] = $this->request->post('dp_redeem_bank_branch', ''); //赎楼短贷银行支行
//            $dpData['dp_supervise_date'] = $this->request->post('superviseDate'); //监管日期
//            if (empty($dpData['dp_supervise_date']))
//                return '监管日期不能为空';
        }elseif ($this->type == 'JYXJ' || $this->type == 'PDXJ') {
            /* JYXJ */
//            $dp_supervise_date = $this->request->post('superviseDate'); //监管日期
//            if (!empty($dp_supervise_date))
//                $dpData['dp_supervise_date'] = $dp_supervise_date;
        }elseif ($this->type == 'SQDZ') {
//            $superviseDate = $this->request->post('superviseDate', ''); //监管日期 非必填项
//            $superviseDate && $dpData['dp_supervise_date'] = $superviseDate;
            $dpData['dp_supervise_guarantee'] = $this->request->post('superviseGuarantee'); //担保公司监管
            $dpData['dp_supervise_buyer'] = $this->request->post('superviseBuyer'); //买方本人监管
            $dpData['dp_money'] = $dpData['dp_supervise_guarantee'] + $dpData['dp_supervise_buyer'];
        }
        if (!empty($dpBankinfo)) {//2018.9.5 监管银行验证
            foreach ($dpBankinfo as $value) {
                $validate = loader::validate('AddDpBankInfo');
                if (!$validate->scene('AddDpBankInfo')->check($value)) {
                    return $validate->getError();
                }
            }
        } else {
            return '监管信息不能为空';
        }
        $dpVail = validate('DpValidate');
        $msg = $dpVail->check($dpData);
        if ($msg !== true)
            return $dpVail->getError();
        $this->checkData['dpBankInfo'] = $dpBankinfo;
        $this->checkData['dpInfo'] = $dpData;
        $this->temporary['strikePrice'] = $dpData['dp_strike_price'];
        unset($dpData);
        return true;
    }

    /**
     * 校验担保赎楼信息
     * @param $strikePrice 成交价格
     * @return array|string|true
     */
    private function checkGuarantee() {
        $guaranteeData['notarization'] = $this->request->post('notarization');
        $guaranteeData['money'] = $this->request->post('money'); //担保费（订单金额）
        $guaranteeData['self_financing'] = $this->request->post('selfFinancing');
        $guaranteeData['guarantee_rate'] = $this->request->post('guaranteeRate');
        $guaranteeData['out_account_total'] = $this->request->post('out_account_total', 0, 'float');
        $guaranteeData['fee'] = $this->request->post('fee');
        $guaranteeData['info_fee'] = $this->request->post('infoFee');
        $guaranteeData['total_fee'] = $this->request->post('totalFee');
        $guaranteeData['project_money_date'] = $this->request->post('project_money_date'); //预计用款日
        if (empty($guaranteeData['notarization']))
            return '公证日期不能为空';
        $guaranteeVail = validate('Guarantee');
        if ($guaranteeVail->check($guaranteeData) !== true)
            return $guaranteeVail->getError();
        $guaranteeData['guarantee_per'] = sprintf("%.2f", $guaranteeData['money'] / $this->temporary['strikePrice'] * 10); //担保成数
        $messsage = $this->checkGuaranteeperCount($guaranteeData['guarantee_per']);
        if ($messsage !== 1)
            return $messsage;
        $guaranteeData['account_per'] = sprintf("%2f", $guaranteeData['out_account_total'] / $this->temporary['strikePrice'] * 10); //出账成数
        $guaranteeData['guarantee_fee'] = sprintf("%1.2f", $guaranteeData['out_account_total'] * $guaranteeData['guarantee_rate'] / 100); //担保费
        $guaranteeData['guarantee_fee'] < 3000 && $guaranteeData['guarantee_fee'] = 3000;
        $guaranteeData['total_fee'] = $guaranteeData['guarantee_fee'] + $guaranteeData['fee']; //费用合计
        $guaranteeData['is_dispatch'] = $this->temporary['dispatch'];
        $guaranteeData['is_instruct'] = $this->temporary['instruct'];
        $guaranteeData['is_combined_loan'] = $this->temporary['is_combined_loan']; //组合贷
        $this->temporary['instruct'] && $guaranteeData['instruct_status'] = 1; //指令状态待申请
        $this->checkData['guaranteeInfo'] = $guaranteeData;
        unset($guaranteeData);
        return true;
    }

    /**
     * 校验担保成数是否超过给定的长度
     * @return array
     */
    private function checkGuaranteeperCount($guarantee_per) {
        if (strlen($guarantee_per) > 5) {
            return "担保成数超过长度";
        }
        return 1;
    }

    /**
     * 校验担保成数是否超过给定的长度
     * @return array
     */
    private function checkDZGuaranteeperCount($guarantee_per) {
        if (strlen($guarantee_per) > 5) {
            return "担垫资成数超过长度";
        }
        return 1;
    }

    /**
     * 校验保赎楼银行信息
     * @return array
     */
    private function checkGuaranteeBank() {
        $bankArr = [];
        $repayment = input('post.repayment/a'); //赎楼还款信息
        !empty($repayment) && $bankArr = array_merge($bankArr, $repayment);
        if ($bankArr) {
            foreach ($bankArr as $gbData) {
                $GuaranteeBankVail = validate('GuaranteeBank');
                $msg = $GuaranteeBankVail->check($gbData);
                if ($msg !== true)
                    return $GuaranteeBankVail->getError();
                $this->temporary['is_need_verify_card'] == 0;
                foreach ($gbData['bankuse'] as $value) {
                    $this->checkCard($value); //判断核卡
                    if ($this->temporary['is_need_verify_card'] == 1) {
                        break;
                    }
                }
            }
        }
        $this->checkData['guaranteeBankInfo'] = $bankArr;
        unset($bankArr);
        return true;
    }

    //校验DQJK银行信息
    private function checkDQJKGuaranteeBank() {
        $returnMoneyInfo = input('post.repayment/a'); //所有银行卡信息
        if (!$returnMoneyInfo) {
            return '短期借款订单缺少回款卡信息';
        }
        $flag = TRUE;
        foreach ($returnMoneyInfo as $value) {
            if (empty($value['openbank'])) {
                return '开户银行不能为空';
            }
            if (!empty($value['bankuse']) && in_array(4, $value['bankuse'])) {
                $flag = FALSE;
            }
        }
        if ($flag) {
            return '短期借款订单缺少回款卡信息';
        } else {
            $this->checkData['guaranteeBankInfo'] = $returnMoneyInfo;
            unset($returnMoneyInfo);
            return true;
        }
    }

    /**
     * 校验保赎楼银行信息
     * @return array
     */
    private function checkPDXJGuaranteeBank() {
        $bankArr = [];
        $repayment = input('post.repayment/a'); //所有银行卡信息
        !empty($repayment) && $bankArr = array_merge($bankArr, $repayment);
        if ($bankArr) {
            foreach ($bankArr as $gbData) {
                $GuaranteeBankVail = validate('GuaranteeBank');
                $msg = $GuaranteeBankVail->check($gbData);
                if ($msg !== true)
                    return $GuaranteeBankVail->getError();
                foreach ($gbData['bankuse'] as $value) {
                    $this->checkCard($value); //判断核卡
                    if ($this->temporary['is_need_verify_card'] == 1) {
                        break;
                    }
                }
            }
        }
        $this->checkData['guaranteeBankInfo'] = $bankArr;
        unset($bankArr);
        return true;
    }

    //校验核卡
    private function checkCard($cardType) {
        if ($cardType == 1 || $cardType == 3 || $cardType == 4 || $cardType == 5) {
            $this->temporary['is_need_verify_card'] = 1; //需要核卡
        }
    }

    /**
     * 验证是否派单，派单是其他派单还是正常派单|是否需要发送指令
     * @param $bank
     * @param $orgMortgageData
     * @return array 索引0 派单状态 索引1发送指令状态
     */
    private function checkDispatch($bank, &$orgMortgageData) {
        $parttern = '/农业银行|中国银行|工商银行/';
        $preg = preg_match($parttern, $bank, $matches);
        $preg !== 0 && $bank = $matches[0];
        if ($bank === '中国银行' || $bank === '农业银行' || $bank === '工商银行') {
            if ($bank === '中国银行' || $bank === '工商银行') {
                return [$this->checkBank($orgMortgageData, $bank), 1]; //中工都需要发送指令
            }
            return [$this->checkBank($orgMortgageData, $bank), $this->checkInstruct($orgMortgageData, $bank)];
        }
        return [1, 0]; //非农中工返回正常派单,不需要发送指令
    }

    /**
     * 判断是否发送指令
     * @param $orgMortgageData
     * @param $name
     * @return int
     */
    private function checkInstruct($orgMortgageData, $name) {
        foreach ($orgMortgageData as $val) {
            if (strpos($val['organization'], $name) === false) {
                return 0; //只要有一个不是农业银行就不需要发送指令
            }
        }
        return 1; //全部为农业银行需要发指令
    }

    /**
     * 校验农中工银行
     * @param $orgMortgageData
     * @param $name
     * @return int
     */
    private function checkBank($orgMortgageData, $name) {
        //1公积金2商业贷
        $count = count($orgMortgageData);
        if ($count === 1) {
            if ($orgMortgageData[0]['mortgage_type'] == '1') {
                return 1;
            } elseif ($orgMortgageData[0]['mortgage_type'] == '2') {
                if (strpos($orgMortgageData[0]['organization'], $name) !== false) {
                    if ($name == '工商银行')
                        return 2; //短贷银行为工行，一个原按揭信息按揭银行为工行返回其他派单
                    return 0;
                }

                return 1;
            }
        }else {

            $isName = 0; //对应银行个数
            foreach ($orgMortgageData as $orgMortgageDatum) {
                if ($orgMortgageDatum['mortgage_type'] == '1')
                    return 1; //一个或多个公积金返回正常派单

                if (strpos($orgMortgageDatum['organization'], $name) !== false) {
                    $isName++;
                }
            }
            //所有为商业贷
            if ($isName === $count) {
                if ($name == '工商银行')
                    return 2; //短贷银行为工行，全部按揭银行为工行返回其他派单
                return 0; //农行和中  行商业贷所有都是本行时不需要派单
            }else {
                return 1; //一个或多个不等时为正常派单
            }
        }
    }

    /**
     * 校验垫资费计算
     * @return array|string
     */
    private function checkJYXJAdvance() {
        $advanceDatas = input('post.advance/a');
        $advanceTotal = 0;
        if (!$advanceDatas || !is_array($advanceDatas))
            return '垫资费计算信息不能为空';
        //校验房产信息
        foreach ($advanceDatas as $key => $val) {
            if ($val['advance_money'] <= 0 || $val['advance_day'] <= 0 || (floor($val['advance_day']) - $val['advance_day']) != 0 || $val['advance_rate'] <= 0) {
                return '垫资费计算信息有误';
            }
            if (isset($val['plus_rate']) && $val['plus_rate'] <= 0)
                return '加收费率信息有误';
            if (isset($val['remark']) && $val['remark'] && mb_strlen($val['remark']) > 100)
                return '垫资费信息至多100个字符';
            $advanceDatas[$key]['advance_fee'] = sprintf("%1.2f", $val['advance_money'] * $val['advance_day'] * $val['advance_rate'] / 100); //垫资费
            $advanceTotal += $advanceDatas[$key]['advance_fee'];
        }

        $this->checkData['advanceData'] = $advanceDatas;
        $this->temporary['guarantee_fee'] = $advanceTotal;
        $this->temporary['guarantee_fee'] < 3000 && $this->temporary['guarantee_fee'] = 3000;
        $money = input('post.money');//2018.09.05 去掉渠道组件 将原来的垫资总金额单独处理
        $this->temporary['total'] = $money;
        $this->checkData['orderInfo']['money'] = $money; //订单金额
        unset($advanceDatas);
        return true;
    }

//    /** 2018.09.05 去掉渠道组件
//     * 校验渠道信息advanceFund
//     * @return array|string
//     */
    private function checkChannel() {
        $this->temporary['com_loan_money'] = 0; //公司放款金额
        $channelInfo = input('post.channel/a');
        $total = 0; //垫资总计
        if (!$channelInfo || !is_array($channelInfo))
            return '资金渠道信息不能为空';
        //只有一个自有资金的渠道不需要发送指令
        if (count($channelInfo) == 1 && $channelInfo[0]['fund_channel_id'] == 1) {
            $this->temporary['is_instruct'] = 0;
            $this->temporary['instruct_status'] = 0;
        } else {
            $this->temporary['is_instruct'] = 1;
            $this->temporary['instruct_status'] = 1;
        }
        $this->temporary['is_loan_finish'] = 1; //银行放款已完成
        //校验渠道信息
        foreach ($channelInfo as $key => $val) {
            if ($val['fund_channel_id'] <= 0 || empty($val['fund_channel_name']) || $val['money'] <= 0) {
                return '资金渠道信息有误';
            }

            //首期垫资只有自有资金
            if (($this->type == 'SQDZ' || $this->type == 'DQJK') && $val['fund_channel_id'] != 1) {
                return '资金渠道有误';
            }

            if ($val['fund_channel_id'] == 1) {
                $channelInfo[$key]['delivery_status'] = -1;
                $channelInfo[$key]['is_instruct'] = 0;
                $channelInfo[$key]['is_loan_finish'] = 1;
                $this->temporary['com_loan_money'] += $val['money'];
            } else {
                $channelInfo[$key]['delivery_status'] = 0;
                $channelInfo[$key]['is_instruct'] = 1; //需要发送指令
                $channelInfo[$key]['instruct_status'] = 1; //指令待申请
                $this->temporary['is_loan_finish'] = 0; //凡是有渠道资金银行放款未完成
            }
            $total += $val['money'];
        }
        $this->temporary['total'] = $total;
        $this->checkData['channelInfo'] = $channelInfo;
        $this->checkData['orderInfo']['money'] = $total; //订单金额

        unset($channelInfo);
        return true;
    }

    /**
     * 校验现金赎楼信息 JYXJ
     * @return array|string|true
     */
    private function checkJYXJGuarantee() {
        $guaranteeData['notarization'] = $this->request->post('notarization', '');
        $guaranteeData['self_financing'] = $this->request->post('selfFinancing');
        $guaranteeData['out_account_total'] = $this->request->post('out_account_total', 0, 'float');
        $guaranteeData['info_fee'] = $this->request->post('infoFee');
        $guaranteeData['fee'] = $this->request->post('fee');
        $guaranteeData['project_money_date'] = $this->request->post('moneyDate'); //预计用款日
        $returnMoneyArr = $this->request->post('returnMoneyArr/a'); //回款方式
//        $guaranteeData['return_money_amount'] = $this->request->post('returnMoney', '');
//        $guaranteeData['return_money_mode'] = $this->request->post('money_mode'); //回款方式
        if (empty($guaranteeData['notarization']))
            return '公正日期不能为空';
        if (empty($guaranteeData['project_money_date']))
            return '预计用款日不能为空';
        $guaranteeVail = validate('Guarantee');
        if ($guaranteeVail->check($guaranteeData) !== true)
            return $guaranteeVail->getError();
        $recode = $this->CheckReturnMoney($returnMoneyArr);
        if ($recode == 1) {
            $this->checkData['returnMoneyArr'] = $returnMoneyArr;
        } else {
            return $recode;
        }
        $guaranteeData['account_per'] = sprintf("%1.2f", $guaranteeData['out_account_total'] / $this->temporary['strikePrice'] * 10); //出账成数
        $this->checkData['guaranteeInfo'] = $guaranteeData;
        unset($guaranteeData);
        return true;
    }

    private function CheckReturnMoney($returnMoneyArr) {
        if (!empty($returnMoneyArr)) {//2018.9.5 回款方式验证
            $validate = loader::validate('AddDpBankInfo');
            foreach ($returnMoneyArr as $value) {
                if (!$validate->scene('AddReturnway')->check($value)) {
                    return $validate->getError();
                }
            }
        } else {
            return '监管信息不能为空';
        }
        return 1;
    }

    /**
     * 校验非交易现金
     * TMXJ、GMDZ
     * @return array|bool
     */
    private function checkTMXJGuarantee() {
        $guaranteeData['notarization'] = $this->request->post('notarization', '');
        $guaranteeData['self_financing'] = $this->request->post('selfFinancing');
        $guaranteeData['return_money_amount'] = $this->request->post('returnMoney', '');
        $guaranteeData['out_account_total'] = $this->request->post('out_account_total', 0, 'float');
        $guaranteeData['info_fee'] = $this->request->post('infoFee', 0, 'float');
        $guaranteeData['fee'] = $this->request->post('fee', 0, 'float');
        $guaranteeData['project_money_date'] = $this->request->post('moneyDate'); //预计用款日
        $guaranteeData['evaluation_price'] = $this->request->post('evaluation_price', 0, 'float'); //评估价
//        $guaranteeData['return_money_mode'] = $this->request->post('money_mode'); //回款方式
//        $guaranteeData['return_money_amount'] = $this->request->post('returnMoney'); //回款金额
        $returnMoneyArr = $this->request->post('returnMoneyArr/a'); //回款方式
        if (empty($guaranteeData['notarization']))
            return '公正日期不能为空';
        if (empty($guaranteeData['project_money_date']))
            return '预计用款日不能为空';
        $guaranteeVail = validate('Guarantee');
        if ($guaranteeVail->check($guaranteeData) !== true)
            return $guaranteeVail->getError();
        $recode = $this->CheckReturnMoney($returnMoneyArr);
        if ($recode == 1) {
            $this->checkData['returnMoneyArr'] = $returnMoneyArr;
        } else {
            return $recode;
        }
        $this->temporary['evaluation_price'] = $guaranteeData['evaluation_price'];
        $this->checkData['guaranteeInfo'] = $guaranteeData;
        unset($guaranteeData);
        return true;
    }

    /**
     * 校验现金赎楼信息 DQJK
     * @return array|string|true
     * DQJK费用总计等于垫资费总计+手续费
     */
    private function checkDQJKGuarantee() {
        $guaranteeData['turn_into_date'] = input('post.turnIntoDate', ''); //存入日期
        $guaranteeData['turn_back_date'] = input('post.turnBackDate', ''); //转回日期
        $guaranteeData['fee'] = $this->request->post('fee'); //手续费
        $guaranteeData['info_fee'] = $this->request->post('infoFee', 0, 'int'); //预计信息费
//        $guaranteeData['return_money_mode'] = $this->request->post('money_mode'); //回款方式
//        $guaranteeData['return_money_amount'] = $this->request->post('returnMoney'); //回款金额
        $returnMoneyArr = $this->request->post('returnMoneyArr/a'); //回款方式
        if (empty($guaranteeData['turn_into_date']))
            return '存入日期不能为空';
        if (empty($guaranteeData['turn_back_date']))
            return '转回日期不能为空';
        if ($guaranteeData['fee'] < 0)
            return '手续费不能小于0';

        $guaranteeVail = validate('Guarantee');
        if ($guaranteeVail->check($guaranteeData) !== true)
            return $guaranteeVail->getError();
        $recode = $this->CheckReturnMoney($returnMoneyArr);
        if ($recode == 1) {
            $this->checkData['returnMoneyArr'] = $returnMoneyArr;
        } else {
            return $recode;
        }
        $this->checkData['guaranteeInfo'] = $guaranteeData;

        unset($guaranteeData);
        return true;
    }

    /**
     * 校验现金赎楼信息 PDXJ
     * @return array|string|true
     * DQJK费用总计等于垫资费总计+手续费
     */
    private function checkPDXJGuarantee() {
        $guaranteeData['notarization'] = $this->request->post('notarization', ''); //公证日期
        $guaranteeData['fee'] = $this->request->post('fee'); //手续费
        $guaranteeData['info_fee'] = $this->request->post('infoFee'); //预计信息费
//        $guaranteeData['return_money_mode'] = $this->request->post('money_mode'); //回款方式
//        $guaranteeData['return_money_amount'] = $this->request->post('returnMoney'); //回款金额
        $returnMoneyArr = $this->request->post('returnMoneyArr/a'); //回款方式
        $guaranteeData['project_money_date'] = $this->request->post('moneyDate'); //预计用款日
        if (empty($guaranteeData['notarization']))
            return '公正日期不能为空';
        if (empty($guaranteeData['project_money_date']))
            return '预计用款日不能为空';
        $guaranteeVail = validate('Guarantee');
        if ($guaranteeVail->check($guaranteeData) !== true)
            return $guaranteeVail->getError();
        $recode = $this->CheckReturnMoney($returnMoneyArr);
        if ($recode == 1) {
            $this->checkData['returnMoneyArr'] = $returnMoneyArr;
        } else {
            return $recode;
        }
        $this->checkData['guaranteeInfo'] = $guaranteeData;
        return true;
    }

    //销毁
    function __destory() {
        unset($this->temporary, $this->checkData);
    }

}
