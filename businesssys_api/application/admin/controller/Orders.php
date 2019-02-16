<?php

/**
 * 订单
 */

namespace app\admin\controller;

use think\Db;
use app\model\Order;
use app\model\Estate;
use app\model\OrderDp;
use app\model\OrderDpBank;
use app\model\OrderMortgage;
use app\model\OrderGuarantee;
use app\model\OrderGuaranteeBank;
use app\model\OrderGuaranteeBankType;
use app\model\OrderGuaranteeReturn;
use app\model\Customer;
use app\model\Dictionary;
use app\model\SystemUser;
use app\model\SystemDept;
use app\model\OrderStaff;
use app\model\OrderAdvanceMoney;
use app\model\OrderFundChannel;
use app\model\WorkflowProc;
use app\util\ReturnCode;
use app\util\OrderCheck;
use app\util\OrderComponents;
use think\Exception;
use Workflow\Workflow;
use app\model\WorkflowFlow;
use app\workflow\model\WorkflowEntry;
use app\model\Message;
use app\model\OrderRansomOut;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Orders extends Base {

    private $orderSn; //订单编号
    private $type; //订单类型
    private $time; //时间
    private $message;

    public function _initialize() {
        parent::_initialize();
        $this->message = new Message();
    }

    // @author 林桂均

    /**
     * @api {post} admin/Orders/orderList 订单列表[admin/Orders/orderList]
     * @apiVersion 1.0.0
     * @apiName orderList
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/orderList
     * @apiParam {string}  startTime   订单开始时间
     * @apiParam {string}  endTime   订单结束时间
     * @apiParam {string}  search   查询名称
     * @apiParam {string}  managerId   理财经理
     * @apiParam {string}  estateCity   所属城市
     * @apiParam {string}  estateDistrict   所属城区
     * @apiParam {string}  stage   订单状态
     * @apiParam {int}  guaranteeFeeStatus  待收担保费 1
     * @apiParam {int}  isBankLoan 待银行放款 1
     * @apiParam {int}  isMortgage  待过户抵押 1
     * @apiParam {int}  isForeclosure  待完成赎楼 1
     * @apiParam {int}  isWindControl  待风控审批 1
     * @apiParam type 订单类型
     * @apiSuccess {int} is_data_entry 待风控审批 0未审批1已审批
     * @apiSuccess {int} guarantee_fee_status 担保费是否已收 1未收 2已收
     * @apiSuccess {int} is_loan_finish 待银行放款 0未完成1已完成
     * @apiSuccess {int} is_foreclosure_finish 待赎楼完成 0未完成1已完成
     * @apiSuccess {int} is_mortgage_finish 待过户抵押 0未完成1已完成
     */
    public function orderList() {
        $this->type = input('type', '');
        if (empty($this->type))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '缺少参数!');
        $where['x.type'] = $this->type;
        $startTime = $this->request->post('startTime', '', 'strtotime');
        $endTime = $this->request->post('endTime', '', 'strtotime');
        $search = $this->request->post('search', '', 'trim');
        $managerId = $this->request->post('managerId', 0, 'int');
        $estateCity = $this->request->post('estateCity', '');
        $estateDistrict = $this->request->post('estateDistrict', '');
        $stage = $this->request->post('stage', 0, 'int');
        $subordinates = $this->request->post('subordinates', 0, 'int');
        $pageSize = $this->request->post('pageSize', 0, 'int');
        $page = $this->request->post('page', 1, 'int');
        $isMortgage = input('post.isMortgage');
        $isForeclosure = input('post.isForeclosure');
        $isWindControl = input('post.isWindControl');
        $guaranteeFeeStatus = input('post.guaranteeFeeStatus');
        $isBankLosn = input('post.isBankLoan');
        $userId = $this->userInfo['id'];
        if ($startTime > $endTime) {
            $mtime = $startTime;
            $startTime = $endTime;
            $endTime = $mtime;
        }

        //用户判断
        //$userStr = $managerId ==  '0'  ? SystemUser::getOrderPowerStr($userId) : SystemUser::orderCheckPower($userId,$managerId,$subordinates);//return $userStr;

        $userStr = SystemUser::getOrderPowerStr($userId, $this->userInfo['ranking'], $this->userInfo['deptid']);
        //todo 特权人可以查看所有单
        if (!$this->checkPrivilegeAuth()) {
            if ($userStr != 'super') {
                $where['x.financing_manager_id|x.create_uid'] = ['in', $userStr]; //理财经理或者提交人
            }
            if ($managerId != '0') {
                if ($subordinates == '0') {
                    $where['x.financing_manager_id'] = $managerId;
                } else {
                    $managerStr = SystemUser::getOrderPowerStr($managerId);
                    if ($managerStr != 'super')
                        $where['x.financing_manager_id'] = ['in', $managerStr];
                }
            }
        }

        if ($startTime && $endTime) {
            $startTime !== $endTime ? $where['x.create_time'] = ['between', [$startTime, $endTime + 86400]] : $where['x.create_time'] = ['between', [$startTime, $startTime + 86400]];
        } elseif ($startTime) {
            $where['x.create_time'] = ['egt', $startTime];
        } elseif ($endTime) {
            $where['x.create_time'] = ['elt', $endTime];
        }
        $guaranteeFeeStatus == 1 && $where['z.guarantee_fee_status'] = 1; //待收担保费
        $isBankLosn == 1 && $where['z.is_loan_finish'] = 0; //待放款完成
        $isMortgage == 1 && $where['x.is_mortgage_finish'] = 0; //完成抵押
        $isForeclosure == 1 && $where['x.is_foreclosure_finish'] = 0; //待完成赎楼
        $isWindControl == 1 && $where['x.is_data_entry'] = 0; //待风控完成
        $where['y.estate_usage'] = 'DB';
        $search && $where['x.order_sn|y.estate_name'] = ['like', "%{$search}%"];
        $estateCity && $where['y.estate_ecity'] = $estateCity;
        $estateDistrict && $where['y.estate_district'] = $estateDistrict;
        $stage && $where['x.stage'] = $stage;
        //获取查询的用户数据
        $result = Order::orderList($where, $page, $pageSize);
        if ($result === false)
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, '订单读取失败!');
        $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_JYDB_STATUS'));
        if ($result['data']) {
            foreach ($result['data'] as &$val) {
                $val['estateInfo'] = OrderComponents::showEstateList($val['order_sn'], 'estate_name,estate_owner,estate_region', 'DB');
                isset($val['sellerInfo'][0]['cname']) && $val['estate_owner'] = $val['sellerInfo'][0]['cname'];
                $val['name'] = (new SystemUser)->where(['id' => $val['financing_manager_id']])->value('name');
                $val['stageStr'] = isset($newStageArr[$val['stage']]) ? $newStageArr[$val['stage']] : '';
            }
        }
        return $this->buildSuccess($result);
    }

    /**
     * @api {post} admin/Orders/dqjkList 短期借款列表[admin/Orders/dqjkList]
     * @apiVersion 1.0.0
     * @apiName dqjkList
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/dqjkList
     * @apiParam {string}  startTime   订单开始时间
     * @apiParam {string}  endTime   订单结束时间
     * @apiParam {string}  search_text   查询名称
     * @apiParam {string}  managerId   理财经理
     * @apiParam {string}  stage   订单状态
     * @apiParam {int}  guaranteeFeeStatus  待收担保费 1
     * @apiParam {int}  isReturnMoneyFinish 待完成回款 1
     * @apiParam {int}  isWindControl  待风控审批 1
     * @apiParam type 订单类型
     * @apiSuccess {int} is_data_entry 待风控审批 0未审批1已审批
     * @apiSuccess {int} guarantee_fee_status 担保费是否已收 1未收 2已收
     * @apiSuccess {int} is_return_money_finish 待完成回款 0未完成1已完成
     */
    public function dqjkList() {
        $where['x.type'] = 'DQJK';
        $startTime = $this->request->post('startTime', '', 'strtotime');
        $endTime = $this->request->post('endTime', '', 'strtotime');
        $search = $this->request->post('search_text', '', 'trim');
        $managerId = $this->request->post('managerId', 0, 'int');

        $stage = $this->request->post('stage', 0, 'int');
        $subordinates = $this->request->post('subordinates', 0, 'int');
        $pageSize = $this->request->post('pageSize', 0, 'int');
        $page = $this->request->post('page', 1, 'int');
        $isWindControl = input('post.isWindControl');
        $guaranteeFeeStatus = input('post.guaranteeFeeStatus');
        $isReturnMoneyFinish = input('post.isReturnMoneyFinish');
        $userId = $this->userInfo['id'];
        if ($startTime > $endTime) {
            $mtime = $startTime;
            $startTime = $endTime;
            $endTime = $mtime;
        }
        //用户判断
        $userStr = SystemUser::getOrderPowerStr($userId, $this->userInfo['ranking'], $this->userInfo['deptid']);
        //todo 特权人可以查看所有单
        if (!$this->checkPrivilegeAuth()) {
            if ($userStr != 'super') {
                $where['x.financing_manager_id|x.create_uid'] = ['in', $userStr]; //理财经理或者提交人
            }
            if ($managerId != '0') {
                if ($subordinates == '0') {
                    $where['x.financing_manager_id'] = $managerId;
                } else {
                    $managerStr = SystemUser::getOrderPowerStr($managerId);
                    if ($managerStr != 'super')
                        $where['x.financing_manager_id'] = ['in', $managerStr];
                }
            }
        }
        $guaranteeFeeStatus == 1 && $where['z.guarantee_fee_status'] = 1;
        $isWindControl == 1 && $where['x.is_data_entry'] = 0; //待风控完成
        $isReturnMoneyFinish == 1 && $where['x.is_return_money_finish'] = 0; //待完成回款

        if ($startTime && $endTime) {
            $startTime !== $endTime ? $where['x.create_time'] = ['between', [$startTime, $endTime + 86400]] : $where['x.create_time'] = ['between', [$startTime, $startTime + 86400]];
        } elseif ($startTime) {
            $where['x.create_time'] = ['egt', $startTime];
        } elseif ($endTime) {
            $where['x.create_time'] = ['elt', $endTime];
        }

        $stage && $where['x.stage'] = $stage;
        $search && $where['x.order_sn'] = ['like', "%{$search}%"];
        //获取查询的用户数据
        $result = Order::dqjkList($where, $page, $pageSize);
        if ($result === false)
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, '订单读取失败!');
        $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_JYDB_STATUS'));
        if ($result['data']) {
            foreach ($result['data'] as &$val) {
                $val['name'] = (new SystemUser)->where(['id' => $val['financing_manager_id']])->value('name');
                $val['stageStr'] = isset($newStageArr[$val['stage']]) ? $newStageArr[$val['stage']] : '';
                $val['openbank'] = OrderGuaranteeBank::where(['order_sn' => $val['order_sn'], 'status' => 1])->value('openbank');
            }
        }
        return $this->buildSuccess($result);
    }

    /**
     * @api {post} admin/Orders/totalOrderList 综合订单列表[admin/Orders/totalOrderList]
     * @apiVersion 1.0.0
     * @apiName totalOrderList
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/totalOrderList
     * @apiParam {string}  startTime   订单开始时间
     * @apiParam {string}  endTime   订单结束时间
     * @apiParam {string}  search   查询名称
     * @apiParam {string}  managerId   理财经理
     * @apiParam {string}  estateCity   所属城市
     * @apiParam {string}  estateDistrict   所属城区
     * @apiParam {string}  stage   订单状态
     * @apiParam {int}  subordinates   是否含下属1含下属
     * @apiParam {int}  page   页
     * @apiParam {int}  pageSize   页码
     * @apiParam type 订单类型
     * @apiSuccess {int} order_sn 订单编号
     * @apiSuccess {int} type 订单类型
     * @apiSuccess {int} typeStr 订单类型文本
     * @apiSuccess {int} create_time 报单时间
     * @apiSuccess {int} stage 订单状态
     * @apiSuccess {int} stageStr 订单状态描述
     * @apiSuccess {int} estate_region 房产地区
     * @apiSuccess {int} estate_name 房产名称
     * @apiSuccess {int} estate_owner 业主产权人
     * @apiSuccess {int} estateInfo 房产信息
     * @apiSuccess {int} name 理财经理姓名
     * @apiSuccess {int} financing_manager_id 理财经理id
     */
    public function totalOrderList() {
        $startTime = $this->request->post('startTime', '', 'strtotime');
        $endTime = $this->request->post('endTime', '', 'strtotime');
        $search = $this->request->post('search', '', 'trim');
        $managerId = $this->request->post('managerId', 0, 'int');
        $estateCity = $this->request->post('estateCity', '');
        $estateDistrict = $this->request->post('estateDistrict', '');
        $stage = $this->request->post('stage', 0, 'int');
        $subordinates = $this->request->post('subordinates', 0, 'int');
        $pageSize = $this->request->post('pageSize', 0, 'int');
        $page = $this->request->post('page', 1, 'int');
        $type = $this->request->post('type', '');
        $where = [];
        if ($startTime > $endTime) {
            $mtime = $startTime;
            $startTime = $endTime;
            $endTime = $mtime;
        }
        if ($managerId != '0') {
            if ($subordinates == '0') {
                $where['x.financing_manager_id'] = $managerId;
            } else {
                $managerStr = SystemUser::getOrderPowerStr($managerId);
                if ($managerStr != 'super')
                    $where['x.financing_manager_id'] = ['in', $managerStr];
            }
        }
        !empty($type) && $where['x.type'] = $type;
        if ($startTime && $endTime) {
            $startTime !== $endTime ? $where['x.create_time'] = ['between', [$startTime, $endTime + 86400]] : $where['x.create_time'] = ['between', [$startTime, $startTime + 86400]];
        } elseif ($startTime) {
            $where['x.create_time'] = ['egt', $startTime];
        } elseif ($endTime) {
            $where['x.create_time'] = ['elt', $endTime];
        }


        $search && $where['x.order_sn|y.estate_name'] = ['like', "%{$search}%"];
        $estateCity && $where['y.estate_ecity'] = $estateCity;
        $estateDistrict && $where['y.estate_district'] = $estateDistrict;
        $stage && $where['x.stage'] = $stage;
        //获取查询的用户数据
        $resultInfo = Order::totalOrderList($where, $page, $pageSize);
        //return json($resultInfo);
        if ($resultInfo === false)
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, '订单读取失败!');
        $result = Order::getOrderInfo($resultInfo);
        $dictonaryType = ['ORDER_JYDB_STATUS', 'ORDER_TYPE'];
        $dictonaryTypeArr = dictionary_reset(Dictionary::dictionaryMultiType($dictonaryType), 1);
        if ($result['data']) {
            foreach ($result['data'] as &$val) {
                $val['estateInfo'] = OrderComponents::showEstateList($val['order_sn'], 'estate_name,estate_owner,estate_region', 'DB');
                isset($val['sellerInfo'][0]['cname']) && $val['estate_owner'] = $val['sellerInfo'][0]['cname'];
                $val['name'] = (new SystemUser)->where(['id' => $val['financing_manager_id']])->value('name');
                $val['stageStr'] = isset($dictonaryTypeArr['ORDER_JYDB_STATUS'][$val['stage']]) ? $dictonaryTypeArr['ORDER_JYDB_STATUS'][$val['stage']] : '';
                $val['typeStr'] = isset($dictonaryTypeArr['ORDER_TYPE'][$val['type']]) ? $dictonaryTypeArr['ORDER_TYPE'][$val['type']] : '';
            }
        }
        return $this->buildSuccess($result);
    }

    // @author 林桂均

    /**
     * @api {post} admin/Orders/orderDetails JYDB、JYXJ、TMXJ、GMDZ订单详情[admin/Orders/orderDetails]
     * @apiVersion 1.0.0
     * @apiName orderDetails
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/orderDetails
     * @apiParam {string}  orderSn   订单编号
     * @apiParam type 订单类型
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *  "data": {
     * "evaluation_price" :房产评估价
     * "now_mortgage" :房价评估信息-现按揭层数
     * "estateInfo": [
     * {
     * "estate_name": "名称1a11dsadaaa", 楼盘名称
     * "estate_region": "深圳-罗湖桂园街道", 楼盘市+区
     * "estate_area": 12,面积
     * "estate_certtype": 1,房产证类型
     * "estate_certnum": "23",产证编号
     * "house_type": 1,房屋类型
     * "id": 390,房产id
     * "estate_floor_plusminus": "up",楼层类型(up:地上楼层；down：地下楼层)
     * "estate_ecity": "440300",市编号
     * "estate_district": "440303001",区编号
     * "estate_unit_alias": "阁栋别名",栋阁别名
     * "estate_alias": "别名1",楼盘别名
     * "house_type_str": "分户",
     * "estate_certtype_str": "房产证".
     * "house_id" 房号id
     * }
     * "sellerInfo(客户信息)": {
     * {
     * "ctype": 1,所属类型 1个人 2企业
     * "is_seller": 2,客户 1买方 2卖方
     * "is_comborrower": 0,共同借款人属性  0借款人 1共同借款人
     * "cname": "李四",姓名
     * "certtype": 1,证件类型
     * "certcode": "123456789",证件编号
     * "mobile": "18825454079",电话
     * "is_guarantee": 0,是否担保申请人0不是1是
     * "id": 827,客户id
     * "ctype_str": "个人",
     * "certtype_str": "身份证",
     * "is_guarantee_str": "否"
     * "datacenter_id" 客户管理系统id
     * }
     * ],
     * "dp_strike_price": "123.00",首期款成交价
     * "dp_earnest_money": "1.00",首期款定金
     * "dp_money": "10.00",首期款金额
     * "dp_supervise_bank": "农业银行",监管银行
     * "dp_buy_way": 1,购房方式
     * "dp_now_mortgage": "1.00",现按揭成数(首期款信息使用)
     * "dp_redeem_bank": "农业银行",赎楼短贷银行
     * "dp_supervise_date": null,监管日期
     * "dp_supervise_guarantee": null,担保公司监管
     * "dp_supervise_buyer": null,买方本人监管
     * "dp_supervise_bank_branch": null,   首期款 监管银行 支行
     * "dp_redeem_bank_branch": null,   首期款 赎楼短贷银行 支行
     * "dp_buy_way_str": "全款购房"
     *
     * },
     */
    public function orderDetails() {
        $orderSn = $this->request->post('orderSn', '');
        $type = input('type', '', 'strtoupper');
        if (empty($orderSn) || empty($type))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数无效!');
        $result = Order::orderDetail($orderSn, $type);
        if ($result === false)
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, '订单详情未找到!');
        /* 获取字典数据 */
        $dictonaryType = ['MORTGAGE_TYPE', 'CERTTYPE', 'ORDER_HOUSE_TYPE', 'PROPERTY_TYPE'];
        $dictonaryTypeArr = dictionary_reset(Dictionary::dictionaryMultiType($dictonaryType), 1);
        //$userId = $this->userInfo['id'];
        //if(SystemUser::orderCheckPower($userId,$result['create_uid']) === false || SystemUser::orderCheckPower($userId,$result['financing_manager_id']) === false) return $this->buildFailed(ReturnCode::DB_READ_ERROR, '权限不足!');
        //订单按揭信息
        $mortgage = OrderComponents::showMortgage($orderSn, 'out_account,type,mortgage_type,money,organization_type,organization,interest_balance,id');
        $orgMortgage = $nowMortgage = [];

        if ($mortgage) {
            foreach ($mortgage as $val) {
                $val['mortgage_type_str'] = isset($dictonaryTypeArr['MORTGAGE_TYPE'][$val['mortgage_type']]) ? $dictonaryTypeArr['MORTGAGE_TYPE'][$val['mortgage_type']] : '';
                $val['organization_type_str'] = $val['organization_type'] == '1' ? '银行' : '其他';
                if ($val['type'] === 'ORIGINAL') {
                    $orgMortgage[] = $val;
                } else {
                    $nowMortgage[] = $val;
                }
            }
        }

        $result['orgMortgage'] = $orgMortgage; //原按揭信息
        $result['nowMortgage'] = $nowMortgage; //现按揭信息
        //订单用户信息
        $customerInfo = OrderComponents::showCustomerInfo($orderSn, 'ctype,is_seller,is_comborrower,cname,certtype,certcode,mobile,is_guarantee,id,datacenter_id');
        $sellInfo = $buyerInfo = [];
        if ($customerInfo) {
            foreach ($customerInfo as $val) {
                $val['ctype_str'] = $val['ctype'] == '1' ? '个人' : '企业';
                $val['certtype_str'] = isset($dictonaryTypeArr['CERTTYPE'][$val['certtype']]) ? $dictonaryTypeArr['CERTTYPE'][$val['certtype']] : '';
                $val['is_guarantee_str'] = $val['is_guarantee'] == '1' ? '是' : '否';
                if ($val['is_seller'] == '1') {
                    $val['is_comborrower'] == '0' ? $buyerInfo['customer'][] = $val : $buyerInfo['combor'][] = $val;
                } elseif ($val['is_seller'] == '2') {
                    $val['is_comborrower'] == '0' ? $sellInfo['customer'][] = $val : $sellInfo['combor'][] = $val;
                }
            }
        }
        $result['sellerInfo'] = $sellInfo;
        $result['buyerInfo'] = $buyerInfo;
        //房产信息
        $estateInfo = OrderComponents::showEstateList($orderSn, 'estate_name,replace(estate_region,\'|\',\'-\') estate_region,estate_area,estate_certtype,estate_certnum,house_type,id,estate_floor_plusminus,estate_ecity,estate_district,estate_unit_alias,estate_alias,estate_unit,estate_floor,estate_house,building_name,house_id', 'DB');
        if ($estateInfo) {
            foreach ($estateInfo as &$val) {
                $val['house_type_str'] = isset($dictonaryTypeArr['ORDER_HOUSE_TYPE'][$val['house_type']]) ? $dictonaryTypeArr['ORDER_HOUSE_TYPE'][$val['house_type']] : '';
                $val['estate_certtype_str'] = isset($dictonaryTypeArr['PROPERTY_TYPE'][$val['estate_certtype']]) ? $dictonaryTypeArr['PROPERTY_TYPE'][$val['estate_certtype']] : '';
            }
        }
        $result['estateInfo'] = $estateInfo;
        $result['out_account_total'] = Db::name('order_guarantee')->where(['order_sn' => $orderSn])->value('out_account_total');
        if ($type !== 'TMXJ' && $type !== 'GMDZ' && $type !== 'DQJK') {//2018.9.6 加上了 短期借款没有首期款信息 不知道之前为什么没加
            $dpInfo = OrderComponents::orderDp($orderSn, 'id,dp_strike_price,dp_earnest_money,dp_buy_way,dp_now_mortgage,dp_redeem_bank,dp_money,dp_redeem_bank_branch,dp_supervise_guarantee,dp_supervise_buyer,dp_redeem_bank_branch');
            $dpInfo['dp_buy_way_str'] = $dpInfo['dp_buy_way'] == '2' ? '按揭购房' : '全款购房';
            $result['dpInfo'] = $dpInfo;
            //监管信息
            $result['dbBankInfo'] = OrderComponents::orderBankDp($orderSn, 'id,dp_supervise_date,dp_money,dp_organization_type,dp_supervise_bank,dp_supervise_bank_branch,dp_organization');
        }
        return $this->buildSuccess($result);
    }

    /**
     * @api {post} admin/Orders/orderGuarantee 担保赎楼信息 [admin/Orders/orderGuarantee]
     * @apiVersion 1.0.0
     * @apiName orderGuarantee
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/orderGuarantee
     *
     * @apiParam {string} orderSn    订单编号
     * @apiParam {string} type    订单类型
     * @apiSuccess {string} notarization    公证日期
     * @apiSuccess {int} guarantee_money    担保金额
     * @apiSuccess {int} self_financing    自筹金额
     * @apiSuccess {int} guarantee_per    担保成数
     * @apiSuccess {float} guarantee_rate    担保费率
     * @apiSuccess {float} out_account_total    预计出账总额
     * @apiSuccess {float} account_per    出账成数
     * @apiSuccess {float} guarantee_fee 担保费
     * @apiSuccess {float} fee    手续费
     * @apiSuccess {float} info_fee    预计信息费
     * @apiSuccess {float} total_fee    费用合计
     * @apiSuccess {int} order_source    业务来源1合作中介 2银行介绍 3个人介绍 4房帮帮 5其它来源
     * @apiSuccess {string} source_info 来源信息(来源机构)
     * @apiSuccess {array} guaranteeBank    赎楼还款银行信息type还款账号类型：1赎楼还款账户2尾款账号信息bankaccount银行户名accounttype账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它（当type为1时只能选1、2,bankcard卡号openbank银行
     * @apiSuccess {string} mortgage_name    按揭人姓名
     * @apiSuccess {string} mortgage_mobile    按揭人电话
     * @apiSuccess {string} remark    业务说明
     * @apiSuccess {array} attachInfo    附件信息name附件名称
     * @apiSuccess {string} financing_dept_id 理财经理部门id
     * @apiSuccess {int} financing_manager_id 理财经理id
     * @apiSuccess {int} dept_manager_id 部门经理id
     * @apiSuccess {int} order_source 订单来源id
     * @apiSuccess {int} stage 订单状态
     */
    public function orderGuarantee() {
        $orderSn = $this->request->post('orderSn', '');
        $type = $this->request->post('type', '');
        if (empty($orderSn) || empty($type))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数无效!');

        //if(SystemUser::orderCheckPower($userId,$result['create_uid']) === false || SystemUser::orderCheckPower($userId,$result['financing_manager_id']) === false) return $this->buildFailed(ReturnCode::DB_READ_ERROR, '权限不足!');
        $result = OrderGuarantee::orderGuarantee($orderSn, $type);
        if ($result === false)
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, '担保赎楼信息读取失败!');
        $dictonaryTypeArr = dictionary_reset((new Dictionary)->getDictionaryByType('JYDB_ACCOUNT_TYPE'));
        //赎楼银行信息
        $guaranteeBank = OrderComponents::showGuaranteeBank($orderSn, 'bankaccount,verify_card_status,accounttype,bankcard,openbank,id');
        if ($guaranteeBank) {
            foreach ($guaranteeBank as $key => $val) {
                $guaranteeBank[$key]['accounttype_str'] = isset($dictonaryTypeArr[$val['accounttype']]) ? $dictonaryTypeArr[$val['accounttype']] : '';
            }
        } else {
            $guaranteeBank = [];
        }
        $result['guaranteeBank'] = $guaranteeBank;
        //附件信息
        $result['attachInfo'] = OrderComponents::attachInfo($orderSn);
        return $this->buildSuccess($result);
    }

    /**
     * @api {post} admin/Orders/cashMatInfo 现金垫资信息 [admin/Orders/cashMatInfo]
     * @apiVersion 1.0.0
     * @apiName cashMatInfo
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/cashMatInfo
     *
     * @apiParam {string} orderSn    订单编号
     * @apiParam {string} type    订单类型
     * @apiSuccess {string} notarization    公证日期
     * @apiSuccess {int} self_financing    自筹金额
     * @apiSuccess {int} guarantee_per    担保成数
     * @apiSuccess {float} guarantee_rate    担保费率
     * @apiSuccess {float} out_account_total    预计出账总额
     * @apiSuccess {float} account_per    出账成数
     * @apiSuccess {float} guarantee_fee 担保费
     * @apiSuccess {float} fee    手续费
     * @apiSuccess {float} info_fee    预计信息费
     * @apiSuccess {float} total_fee    费用合计
     * @apiSuccess {int} order_source    业务来源1合作中介 2银行介绍 3个人介绍 4房帮帮 5其它来源
     * @apiSuccess fund_channel_per 垫资成数
     * @apiSuccess guarantee_fee 垫资费总计
     * @apiSuccess money 订单金额（JYXJ 垫资总额）
     * @apiSuccess {string} source_info 来源信息(来源机构)
     * @apiSuccess {array} guaranteeBank     赎楼还款银行信息type还款账号类型：1赎楼还款账户2尾款账号信息,3过账账户信息,4回款账户信息  bankaccount银行户名accounttype账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它（当type为1时只能选1、2,bankcard卡号openbank银行
     * @apiSuccess {string} mortgage_name    按揭人姓名
     * @apiSuccess {string} mortgage_mobile    按揭人电话
     * @apiSuccess {string} remark    业务说明
     * @apiSuccess {array} attachInfo    附件信息name附件名称
     * @apiSuccess {array} fundChannel 资金渠道信息
     * @apiSuccess {array} advanceMoney 垫资费计算信息
     * @apiSuccess {string} turn_into_date 存入日期
     * @apiSuccess {string} turn_back_date 转回日期
     * @apiSuccess {string} return_money_mode 回款方式
     * @apiSuccess {string} return_money_amount回款金额
     * @apiSuccess {string} financing_dept_id 理财经理部门id
     * @apiSuccess {int} financing_manager_id 理财经理id
     * @apiSuccess {int} dept_manager_id 部门经理id
     * @apiSuccess {int} order_source 订单来源id
     * @apiSuccess {int} stage 订单状态
     */
    public function cashMatInfo() {
        $type = input('type', '', 'strtoupper');
        $orderSn = $this->request->post('orderSn', '');
        if ($type == '' || empty($orderSn))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数无效!');

        $result = OrderGuarantee::orderGuarantee($orderSn, $type);

        if ($result === false)
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, '垫资信息读取失败!');
        $dictonaryType = ['JYDB_ACCOUNT_TYPE', 'ORDER_REPAY_METHOD'];
        $dictionary = new Dictionary();
        $dictonaryTypeArr = dictionary_reset($dictionary::dictionaryMultiType($dictonaryType), 1);
        //订单银行信息
        $guaranteeBank = OrderComponents::showGuaranteeBank($orderSn, 'bankaccount,accounttype,verify_card_status,bankcard,openbank,id');
        if ($guaranteeBank) {
            foreach ($guaranteeBank as $key => $val) {
                $guaranteeBank[$key]['accounttype_str'] = isset($dictonaryTypeArr['JYDB_ACCOUNT_TYPE'][$val['accounttype']]) ? $dictonaryTypeArr['JYDB_ACCOUNT_TYPE'][$val['accounttype']] : '';
            }
        } else {
            $guaranteeBank = [];
        }

        $result['guaranteeBank'] = $guaranteeBank;
        if ($type == 'TMXJ' || $type == 'SQDZ') {
            $result['business_type_str'] = $dictionary->getValnameByCode($type . '_BUSINESS_TYPE', $result['business_type']);
        }
        $result['return_money_mode_str'] = isset($dictonaryTypeArr['ORDER_REPAY_METHOD'][$result['return_money_mode']]) ? $dictonaryTypeArr['ORDER_REPAY_METHOD'][$result['return_money_mode']] : '';
//        //资金渠道信息
//        $result['fundChannel'] = OrderComponents::fundChannel($orderSn);
        //垫资费计算信息
        $result['advanceMoney'] = OrderComponents::advanceMoney($orderSn);
        //附件信息
        $result['attachInfo'] = OrderComponents::attachInfo($orderSn);
        //回款方式信息
        $result['returnMoney'] = OrderComponents::orderReturnMoney($orderSn);
        return $this->buildSuccess($result);
    }

    // @author 林桂均

    /**
     * @api {post} admin/Orders/addOrder 新增订单[admin/Orders/addOrder]
     * @apiVersion 1.0.0
     * @apiName addOrder
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/addOrder
     * @apiParam {string} type 业务类型
     * @apiParam {string} category 业务分类 1额度 2现金
     * @apiParam {float} money 担保金额
     * @apiParam {string} financingManager 理财经理id
     * @apiParam {string} depId 理财经理部门id
     * @apiParam {string} mortgageName 按揭人姓名
     * @apiParam {string} mortgageMobile 按揭人电话
     * @apiParam {string} managerId 部门经理id
     * @apiParam {string} remark 业务说明
     * @apiParam {string} orderSource 业务来源
     * @apiParam {string} sourceInfo 来源信息(来源机构)
     * @apiParam {array} estateData 房产信息estate_name房产名称,estate_ecity（string)城市,estate_district{string}城区,estate_zone（string）片区estate_region地址名称house_type(int)房屋类型estate_certtype产证类型estate_certnum产证编码estate_area面积building_name楼盘名称estate_alias楼盘别名estate_unit栋阁名称estate_unit_alias栋阁别名estate_floor楼层estate_floor_plusminus楼层正负+-estate_house房号
     * @apiParam {array} mortgageData 按揭信息  按揭数据类型type(string  'ORIGINAL','NOW') ,按揭类型mortgage_type(int),按揭金额money(float),按揭机构类型organization_type(string),按揭机构organization(string)本息余额,interest_balance(float)
     * @apiParam {float} strikePrice 首期款成交价
     * @apiParam {float} earnestMoney 首期款定金
     * @apiParam {float} dpMoney 首期款金额
     * @apiParam {int} buyWay 购房方式
     * @apiParam {int} nowMortgage 首期款按揭成数
     * @apiParam {string} redeembank 赎楼短贷银行
     * @apiParam {array} DpBankInfo 首期款监管银行 dp_supervise_date(date)监管日期 dp_money(float)监管金额 dp_organization_type（int）监管机构 1银行2其他 dp_supervise_bank(string)监管银行（监管机构为1） dp_supervise_bank_branch监管支行（监管机构为1） dp_organization监管机构（监管机构为2）
     * @apiParam {array} returnMoneyArr 回款方式(现金单) return_money_mode(int)回款方式 return_money_amount(float)回款金额 remark（string）回款备注     
     * @apiParam {array} advance 垫资费数组新增一个字段 plus_rate 加收费率     
     * @apiParam  {array} seller 客户信息'ctype(int)所属类型,买卖方is_seller(int)1卖方2卖方,是否共同借款人is_comborrower(int)1是共同借款人0不是,姓名cname(string),certtype证件类型certtype,证件编号certcode,电话mobile电话,是否担保申请人is_guarantee0不是1是,datacenter_id客户管理系统ID
     * @apiParam {int} isSellerComborrower 卖方共同借款人0否1是
     * @apiParam {int} isBuyerComborrower 买方共同借款人0否1是
     * @apiParam {string} notarization 公证日期
     * @apiParam {float} selfFinancing 自筹金额
     * @apiParam {float} guaranteePer 担保成数
     * @apiParam {float} guaranteeRate 担保费率
     * @apiParam {float} accumulationFund 公积金贷款出账
     * @apiParam {float} bussinessLoan 商贷贷款出账
     * @apiParam {float} accountPer 出账成数
     * @apiParam {float} guaranteeFee 担保费
     * @apiParam {float} fee 手续费
     * @apiParam {float} infoFee 预计信息费
     * @apiParam {float} totalFee 费用合计
     * @apiParam {array} attach 附件['attachment_id'=>]
     * @apiParam {array}  lastParagrah 尾款银行信息  还款账号类型type =2固定值，赎楼还款账户, 银行户名bankaccount , 账户类型accounttype 账户类型：1卖方 2卖方共同借款人, 银行卡号bankcard, 银行名称openbank
     * @apiParam {array}  repayment 赎楼银行信息  还款账号类型type =1固定值，赎楼还款账户,2尾款账户, 银行户名bankaccount , 账户类型accounttype 账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它, 银行卡号bankcard, 银行名称openbank
     */
    public function addOrder() {
        $this->type = $this->request->post('type');
        $checkData = $this->orderCheck();
        if (!is_array($checkData))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $checkData);
        if (!isset($this->userInfo['id']) || $this->userInfo['id'] <= 0)
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '登录信息失效，请重新登录!');
        $orderData = $checkData['orderInfo'];
        $financingManagerId = $orderData['financing_manager_id']; //理财经理
        Db::startTrans();
        try {
            $companyId = Db::name('system_user')->where(['id' => $financingManagerId])->value('companyid'); //公司ID
            $orderData['companyid'] = $companyId;
            $orderData['order_sn'] = $this->_systemSequence($this->type);
            if ($orderData['order_sn'] === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '订单编号生成失败');
            }

            $orderData['finance_sn'] = $this->financeSn();
            if ($orderData['finance_sn'] === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '财务编号生成失败');
            }
            //获取理财经理部门经理
            $userModel = new SystemUser();
            $dept_manager_id = $userModel->where(['deptid' => $orderData['financing_dept_id'], 'status' => 1, 'ranking' => '经理'])->value('id');
            if (!($dept_manager_id > 0))
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '获取理财经理部门经理失败');

            $orderData['dept_manager_id'] = $dept_manager_id;
            $this->orderSn = $orderData['order_sn'];
            $orderData['create_uid'] = $this->userInfo['id'];
            $orderData['assistant_name'] = $this->userInfo['name'];
            $this->time = time();
            $orderData['create_time'] = $this->time;
            $orderData['update_time'] = $this->time;
            $orderData['stage'] = '1001';
            //添加订单归属
            $staffModel = new OrderStaff();
            $res = $staffModel->addOrderstaff($this->orderSn, $financingManagerId);
            if (!$res) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $res);
            }
            //添加订单
            $orderModel = new Order;
            if (($id = $orderModel->insertGetId($orderData)) === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '订单新增失败');
            }
            if ($this->type != 'DQJK') {
                //添加房产
                $estateInfo = $this->addEstate($checkData['estateData']);
                if ($estateInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $estateInfo);
                }
            }
            //加APP消息推送记录  2018.8.29
            if (!$this->message->AddmessageRecord($financingManagerId, 2, 5, $id, $this->orderSn, 1012, '订单消息', '订单号' . $this->orderSn . '已报单成功，点击查看详情', 1, 1, 0, 0, '', 'PC报单', 'order_ransom_dispatch')) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '消息推送记录新增失败！');
            }

            if ($this->type != 'DQJK') {
                //添加按揭信息
                if (isset($checkData['mortgageInfo']) && !empty($checkData['mortgageInfo'])) {
                    $mortageInfo = $this->addMortgage($checkData['mortgageInfo']);
                    if ($mortageInfo !== 1) {
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $mortageInfo);
                    }
                }
            }
            if ($this->type != 'DQJK' && $this->type != 'TMXJ' && $this->type != 'GMDZ') {
                //添加首期款信息
                $DpInfo = $this->addDp($checkData['dpInfo']);
                if ($DpInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $DpInfo);
                }
                //添加监管信息2018.9.5 ZJQ
                $DpbankInfo = $this->addBankDp($checkData['dpBankInfo']);
                if ($DpbankInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $DpbankInfo);
                }
            }
            if ($this->type != 'DQJK') {
                //添加客户信息
                $customerInfo = $this->addOrderCustomer($checkData['customerInfo'], $financingManagerId);
                if ($customerInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $customerInfo);
                }
            }

            //添加担保赎楼信息
            $guaranteeInfo = $this->addGuarantee($checkData['guaranteeInfo']);
            if ($guaranteeInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $guaranteeInfo);
            }
            //添加赎楼银行信息
            if (isset($checkData['guaranteeBankInfo']) && !empty($checkData['guaranteeBankInfo'])) {
                $guaranteeBank = $this->addGuaranteeBank($checkData['guaranteeBankInfo']);
                if ($guaranteeBank !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $guaranteeBank);
                }
            }
            if ($this->type != 'JYDB') {
                //添加垫资费计算信息
                $advanceInfo = $this->addAdvance($checkData['advanceData']);
                if ($advanceInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $advanceInfo);
                }
                //添加回款方式信息 2018.9.5
                $returnMoney = $this->addReturnMoney($checkData['returnMoneyArr']);
                if ($returnMoney !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $returnMoney);
                }

//                //添加资金渠道信息  2018.9.5新增订单去掉渠道信息
//                $channelInfo = $this->addChannel($checkData['channelInfo']);
//                if ($channelInfo !== 1) {
//                    Db::rollback();
//                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $channelInfo);
//                }
            }

            //添加附件
            $attachmentInfo = $this->addAttachment();
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }
            $stageStr = (new Dictionary)->getValnameByCode('ORDER_JYDB_STATUS', 1001);
            //添加订单日志
            if (OrderComponents::addOrderLog($this->userInfo, $this->orderSn, $stageStr, '创建订单', $stageStr, '创建订单', '', '1001') === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '订单日志添加失败');
            }

            trace('添加订单信息', '订单添加成功');
            //流程初始化
            $resInitInfo = $this->initProcess($orderData['order_sn'], $id, $this->type);
            if ($resInitInfo['code'] == -1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
            }

            Db::commit();
            return $this->buildSuccess();
        } catch (Exception $e) {
            Db::rollback();
            trace('添加订单错误信息', $e->getMessage());
            return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Orders/orderEdit 编辑订单[admin/Orders/orderEdit]
     * @apiVersion 1.0.0
     * @apiName orderEdit
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/orderEdit
     * @apiParam {string} orderSn 订单编号
     * @apiParam {array} estateData 房产信息（DQJK没有）estate_name房产名称,estate_ecity（string)城市,estate_district{string}城区,estate_zone（string）片区estate_region地址名称house_type(int)房屋类型estate_certtype产证类型estate_certnum产证编码estate_area面积building_name楼盘名称estate_alias楼盘别名estate_unit栋阁名称estate_unit_alias栋阁别名estate_floor楼层estate_floor_plusminus楼层正负+-estate_house房号
     * @apiParam  {array} seller 客户信息（DQJK没有）'ctype(int)所属类型,买卖方is_seller(int)1卖方2卖方,是否共同借款人is_comborrower(int)1是共同借款人0不是,姓名cname(string),certtype证件类型certtype,证件编号certcode,电话mobile电话,是否担保申请人is_guarantee0不是1是,datacenter_id客户管理系统ID
     * @apiParam {float} strikePrice 首期款成交价（JYDB、JYXJ、PDXJ、SQDZ）
     * @apiParam {float} earnestMoney 首期款定金（JYDB、JYXJ、PDXJ、SQDZ）
     * @apiParam {float} dpMoney 首期款金额（JYDB、JYXJ、PDXJ、SQDZ）
     * @apiParam {string} superviseBank 首期款监管银行
     * @apiParam {string} superviseDate 首期款监管日期
     * @apiParam {int} nowMortgage 首期款按揭成数
     * @apiParam {int} buyWay 购房方式 （JYDB、JYXJ、PDXJ、SQDZ）
     * @apiParam {int} superviseGuarantee 担保公司监管 （SQDZ）
     * @apiParam {int} superviseBuyer 买方本人监管 （SQDZ）
     * @apiParam {float} evaluation_price 评估价(GMDZ、TMXJ)
     * @apiParam {float} now_mortgage 现按揭成数(GMDZ、TMXJ)
     * @apiParam {array} mortgageData 按揭信息（DQJK没有）  按揭数据类型type(string  'ORIGINAL','NOW') ,按揭类型mortgage_type(int),按揭金额money(float),按揭机构类型organization_type(string),按揭机构organization(string)本息余额,interest_balance(float)
     * @apiParam {array} DpBankInfo 首期款监管银行 dp_supervise_date(date)监管日期 dp_money(float)监管金额 dp_organization_type（int）监管机构 1银行2其他 dp_supervise_bank(string)监管银行（监管机构为1） dp_supervise_bank_branch监管支行（监管机构为1） dp_organization监管机构（监管机构为2）
     * @apiParam {array} returnMoneyArr 回款方式(现金单) return_money_mode(int)回款方式 return_money_amount(float)回款金额 remark（string）回款备注     
     * @apiParam {array} advance 垫资费数组新增一个字段 plus_rate 加收费率
     * @apiParam {string} notarization 公证日期
     * @apiParam {float} money 担保金额|垫资金额总计
     * @apiParam {float} selfFinancing 自筹金额
     * @apiParam {float} guaranteePer 担保成数|垫资成数
     * @apiParam {float} guaranteeRate 担保费率（JYDB）
     * @apiParam {float} accumulationFund 公积金贷款出账
     * @apiParam {float} bussinessLoan 商贷贷款出账
     * @apiParam {float} consumerLoan 消费贷款出账
     * @apiParam {float} guaranteeFee 担保费
     * @apiParam {float} fee 手续费
     * @apiParam {float} infoFee 预计信息费
     * @apiParam  money_mode 回款方式
     * @apiParam {float} returnMoney 回款金额
     * @apiParam {array}  channel 渠道信息 fund_channel_id 渠道id fund_channel_name 渠道名称 money 渠道金额
     * @apiParam {array} advance 垫资费计算信息 advance_money 垫资金额 advance_day 垫资天数 advance_rate 垫资费率 remark 备注说明
     * @apiParam {array}  repayment 预录赎楼还款银行信息  还款账号类型type =1固定值，赎楼还款账户, 银行户名bankaccount , 账户类型accounttype 账户类型：1卖方 2卖方共同借款人, 银行卡号bankcard, 银行名称openbank
     * @apiParam {array}  lastParagrah 尾款银行信息  还款账号类型type =2固定值，赎楼还款账户, 银行户名bankaccount , 账户类型accounttype 账户类型：1卖方 2卖方共同借款人, 银行卡号bankcard, 银行名称openbank
     * @apiParam {array}  postInfo 过账账户信息  还款账号类型type =3 固定值，赎楼还款账户, 银行户名bankaccount , 账户类型 accounttype , 银行卡号bankcard, 银行名称openbank
     * @apiParam {array}  returnMoneyInfo 回款账户信息  还款账号类型type =4 固定值，赎楼还款账户, 银行户名bankaccount , 账户类型 accounttype , 银行卡号bankcard, 银行名称openbank
     * @apiParam {array}  supervision 监管银行信息  还款账号类型type =5 固定值，赎楼还款账户, 银行户名bankaccount , 账户类型 accounttype , 银行卡号bankcard, 银行名称openbank
     * @apiParam {array}  debitInfo 出账银行信息  还款账号类型type =6 固定值，赎楼还款账户, 银行户名bankaccount , 账户类型 accounttype , 银行卡号bankcard, 银行名称openbank
     * @apiParam {string} financingManager 理财经理id
     * @apiParam {string} managerId 部门经理id
     * @apiParam {string} depId 理财经理部门id
     * @apiParam {string} type 业务类型
     * @apiParam {string} orderSource 业务来源
     * @apiParam {string} sourceInfo 来源信息(来源机构)
     * @apiParam {string} mortgageName 按揭人姓名
     * @apiParam {string} mortgageMobile 按揭人电话
     * @apiParam {string} remark 业务说明
     * @apiParam {array} attach 附件['attachment_id'=>,id订单附件id]
     */
    public function orderEdit() {
        $this->orderSn = input('post.orderSn', '');
        $this->type = input('post.type', '');
        if (empty($this->type) || empty($this->orderSn))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '无效的参数!');
        if (!isset($this->userInfo['id']) || $this->userInfo['id'] <= 0)
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '登录信息失效，请重新登录!');
        $checkData = $this->orderCheck();
        if (!is_array($checkData))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $checkData);
        //todo 特权人
        $Privilege = $this->checkPrivilegeAuth();
        Db::startTrans();
        try {
            /* 查询订单状态 */
            $orderModel = new Order;
            //TODO 特权人不需要stage=1001限制
            if ($Privilege) {
                $orderInfo = $orderModel->where(['order_sn' => $this->orderSn, 'status' => 1, 'type' => $this->type])->lock(true)->find();
            } else {
                $orderInfo = $orderModel->where(['order_sn' => $this->orderSn, 'stage' => 1001, 'status' => 1, 'type' => $this->type])->lock(true)->find(); //待业务报单的订单才能编辑
            }
            if (!$orderInfo)
                return $this->buildFailed(ReturnCode::PARAM_INVALID, '未找符合条件的订单!');
            $this->time = time();
            $orderData = $checkData['orderInfo'];
            //TODO 特权人 不修改create_uid
            if (!$Privilege) {
                $orderData['create_uid'] = $this->userInfo['id'];
            }
            $orderData['update_time'] = $this->time;
            $financingManagerId = $orderData['financing_manager_id']; //理财经理
            $companyId = Db::name('system_user')->where(['id' => $financingManagerId])->value('companyid'); //公司ID
            $orderData['companyid'] = $companyId;
            //获取理财经理部门经理
            $userModel = new SystemUser();
            $dept_manager_id = $userModel->where(['deptid' => $orderData['financing_dept_id'], 'status' => 1, 'ranking' => '经理'])->value('id');
            if (!($dept_manager_id > 0))
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '获取理财经理部门经理失败');
            $orderData['dept_manager_id'] = $dept_manager_id;
            //更新订单
            //TODO 特权人不需要stage=1001限制
            if (!$Privilege) {
                if ($orderModel->where(['id' => $orderInfo['id'], 'stage' => 1001])->update($orderData) === false) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '订单更新失败');
                }
            } else {
                if ($orderModel->where(['id' => $orderInfo['id']])->update($orderData) === false) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '订单更新失败');
                }
            }
            //添加订单归属
            $staffModel = new OrderStaff();
            $res = $staffModel->editOrderstaff($this->orderSn, $financingManagerId);
            if ($res !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $res);
            }
            //更新房产
            if ($this->type != 'DQJK') {
                $estateInfo = $this->updateEstate($checkData['estateData']);
                if ($estateInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $estateInfo);
                }
            }
            //更新按揭信息
            if ($this->type != 'DQJK') {
                $mortageInfo = $this->updateMortgage(isset($checkData['mortgageInfo']) ? $checkData['mortgageInfo'] : '');
                if ($mortageInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $mortageInfo);
                }
            }
            //添加首期款信息
            if ($this->type != 'DQJK' && $this->type != 'TMXJ' && $this->type != 'GMDZ') {
                $DpInfo = $this->updateDp($checkData['dpInfo']);
                if ($DpInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $DpInfo);
                }
                //添加监管信息2018.9.5 ZJQ
                $DpbankInfo = $this->updateBankDp($checkData['dpBankInfo']);
                if ($DpbankInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $DpbankInfo);
                }
            }

            //更新客户信息
            if ($this->type != 'DQJK') {
                $customerInfo = $this->updateOrderCustomer($checkData['customerInfo'], $financingManagerId);
                if ($customerInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $customerInfo);
                }
            }


            //添加担保赎楼信息
            $guaranteeInfo = $this->updateGuarantee($checkData['guaranteeInfo']);
            if ($guaranteeInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $guaranteeInfo);
            }
            //添加赎楼银行信息
            $guaranteeBank = $this->updateGuaranteeBank(isset($checkData['guaranteeBankInfo']) ? $checkData['guaranteeBankInfo'] : '');
            if ($guaranteeBank !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $guaranteeBank);
            }

            if ($this->type != 'JYDB') {

                //添加垫资费计算信息
                $advanceInfo = $this->updateAdvance($checkData['advanceData']);
                if ($advanceInfo !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $advanceInfo);
                }
                //添加回款方式信息
                $returnMoney = $this->updateReturnMoney($checkData['returnMoneyArr']);
                if ($returnMoney !== 1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $returnMoney);
                }

//                //更新资金渠道信息
//                $channelInfo = $this->updateChannel($checkData['channelInfo']);
//                if ($channelInfo !== 1) {
//                    Db::rollback();
//                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $channelInfo);
//                }
            }
            //添加附件
            $attachmentInfo = $this->updateAttachment();
            if ($attachmentInfo !== 1) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $attachmentInfo);
            }
            //TODO 特权人不需要stage=1001限制
            if ($Privilege) {
                $stageStr = (new Dictionary)->getValnameByCode('ORDER_JYDB_STATUS', $orderInfo->stage);
                $code = $orderInfo->stage;
                $operate_det = "特权人:{$this->userInfo['name']}修改订单";
            } else {
                $stageStr = (new Dictionary)->getValnameByCode('ORDER_JYDB_STATUS', 1001);
                $code = 1001;
                $operate_det = "提交编辑订单";
            }
            //添加订单日志
            if (OrderComponents::addOrderLog($this->userInfo, $this->orderSn, $stageStr, $stageStr, $stageStr, $operate_det, '', $code) === false) {
//            if (OrderComponents::addOrderLog($this->userInfo, $this->orderSn, $stageStr, $stageStr, $stageStr, '提交编辑订单', '', 1001) === false) {
                Db::rollback();
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '订单日志添加失败');
            }
            trace('编辑订单错误信息', '订单编号：' . $this->orderSn . '编辑成功');
            //编辑订单流程初始化
            //TODO 特权人--特权人不需要重新初始化
            if (!$Privilege) {
                $resInitInfo = $this->editProcess($orderInfo['id'], $this->orderSn);
                if ($resInitInfo['code'] == -1) {
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::DB_SAVE_ERROR, $resInitInfo['msg']);
                }
            }
            Db::commit();
            return $this->buildSuccess();
        } catch (Exception $e) {
            Db::rollback();
            trace('编辑订单错误信息', '订单编号：' . $this->orderSn . $e->getMessage());
            return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $e->getMessage());
        }
    }

    //编辑订单初始化

    /* @Param {int}  id   订单表id
     * @Param {string}  $order_sn  订单号
     * */
    private function editProcess($id, $order_sn) {
        $workflow = new Workflow();
        $flow_id = $workflow->getFlowId($this->type . '_RISK');
        $entry_id = WorkflowEntry::where(['mid' => $id, 'order_sn' => $order_sn, 'status' => -1, 'flow_id' => $flow_id])->value('id');
        if (isset($entry_id) && !empty($entry_id)) {

            $workflow->resend($entry_id, $this->userInfo['id']);
        } else {
            return ['code' => -1, 'msg' => "编辑订单流程初始化获取flow_id失败"];
        }
    }

    /*
     * 流程初始化
     * */

    private function initProcess($order_sn, $order_id, $type) {
        $workflow = new Workflow();
        $flow_id = $workflow->getFlowId([$type, 'RISK']);
        if (empty($flow_id))
            return (['code' => -1, 'msg' => "添加订单流程初始化获取flow_id失败"]);
        $params['flow_id'] = $flow_id;
        $params['user_id'] = $this->userInfo['id'];
        $params['order_sn'] = $order_sn;
        $params['mid'] = $order_id;
        $workflow->init($params);
    }

    //添加房产
    private function addEstate($estateDatas) {
        $estate = new Estate;
        foreach ($estateDatas as $key => $val) {
            $estateDatas[$key]['estate_usage'] = 'DB';
            $estateDatas[$key]['order_sn'] = $this->orderSn;
            $estateDatas[$key]['create_time'] = $this->time;
        }
        if ($estate->insertAll($estateDatas) > 0) {
            unset($estateDatas);
            return 1;
        }
        unset($estateDatas);
        return '房产信息添加失败';
    }

    //更新房产
    private function updateEstate($estateDatas) {

        $estate = new Estate();
        //查询房产
        $idstr = '';
        foreach ($estateDatas as $val) {
            if (isset($val['id']) && $val['id'] > 0) {//更新
                $idstr .= $idstr == '' ? $val['id'] : ',' . $val['id'];
                $val['update_time'] = $this->time;
                if ($estate->isUpdate(true)->update($val) === false)
                    return '房产信息更新失败';
            } else {
                unset($val['id']);
                $val['estate_usage'] = 'DB';
                $val['order_sn'] = $this->orderSn;
                $val['create_time'] = $this->time;
                if (($id = $estate->insertGetId($val)) <= 0) {
                    return '房产信息修改失败';
                }
                $idstr .= $idstr == '' ? $id : ',' . $id;
            }
        }
        if ($idstr !== '' && $estate->where("order_sn='{$this->orderSn}' and id not in ({$idstr}) and estate_usage='DB'")->update(['status' => -1, 'delete_time' => $this->time]) === false) {//更新其他状态
            return '原房产信息更新失败';
        }
        return 1;
    }

    // @author 林桂均

    /**
     * 添加按揭信息
     *
     * @return array|int|string
     * @throws \Exception
     */
    private function addMortgage($mortgageDatas) {
        foreach ($mortgageDatas as $key => $val) {
            $mortgageDatas[$key]['create_time'] = $mortgageDatas[$key]['update_time'] = $this->time;
            $mortgageDatas[$key]['order_sn'] = $this->orderSn;
            $mortgageDatas[$key]['create_uid'] = $this->userInfo['id'];
        }
        $OrderMortgage = new OrderMortgage;
        if ($OrderMortgage->saveAll($mortgageDatas) > 0) {
            unset($mortgageDatas);
            return 1;
        }
        unset($mortgageDatas);
        return '按揭信息添加失败';
    }

    /**
     * 更新按揭信息
     * @param $mortgageDatas
     * @return int|string
     * @throws \Exception
     */
    private function updateMortgage($mortgageDatas) {
        $OrderMortgage = new OrderMortgage();
        //查询房产
        $idstr = '';
        if ($mortgageDatas) {
            foreach ($mortgageDatas as $val) {
                $val['create_uid'] = $this->userInfo['id'];
                $val['update_time'] = $this->time;
                if (isset($val['id']) && $val['id'] > 0) {//更新
                    $idstr .= $idstr == '' ? $val['id'] : ',' . $val['id'];
                    if ($OrderMortgage->isUpdate(true)->update($val) === false)
                        return '按揭信息更新失败';
                } else {
                    unset($val['id']);
                    $val['create_time'] = $this->time;
                    $val['order_sn'] = $this->orderSn;
                    if (($id = $OrderMortgage->insertGetId($val)) <= 0) {
                        return '按揭信息写入失败';
                    }
                    $idstr .= $idstr == '' ? $id : ',' . $id;
                }
            }

            if ($idstr !== '' && $OrderMortgage->where("order_sn='{$this->orderSn}' and id not in ({$idstr})")->update(['status' => -1, 'delete_time' => $this->time]) === false) {//更新其他状态
                return '原始按揭信息更新失败';
            }
        } else {
            if ($OrderMortgage->where("order_sn='{$this->orderSn}'")->update(['status' => -1, 'delete_time' => $this->time]) === false) {//更新其他状态
                return '原始按揭信息更新失败';
            }
        }
        return 1;
    }

    /**
     * 添加首期款信息
     * @return array|int|string
     */
    private function addDp($dpData) {
        $dpData['create_time'] = $dpData['update_time'] = $this->time;
        $dpData['order_sn'] = $this->orderSn;
        $dpData['create_uid'] = $this->userInfo['id'];
        $Dp = new OrderDp;
        if ($Dp->save($dpData) > 0) {
            $this->OrderDpId = $Dp->id;
            unset($dpData);
            return 1;
        }
        unset($dpData);
        return '首期款信息添加失败';
    }

    /**
     * 更新首期款信息
     * @return array|int|string
     */
    private function updateDp($dpData) {

        $dpData['update_time'] = $this->time;
        $dpData['create_uid'] = $this->userInfo['id'];
        if ((new OrderDp())->save($dpData, ['order_sn' => $this->orderSn, 'status' => 1]) !== false) {
            unset($dpData);
            return 1;
        }
        unset($dpData);
        return '首期款信息更新失败';
    }

    /**
     * 添加监管信息
     * @return array|int|string
     * 2018.9.5 ZJQ
     */
    private function addBankDp($dpBankData) {
        foreach ($dpBankData as &$value) {
            $value['order_dp_id'] = $this->OrderDpId;
            $value['order_sn'] = $this->orderSn;
        }
        $Dpbank = new OrderDpBank();
        if ($Dpbank->saveAll($dpBankData) > 0) {
            unset($dpBankData);
            return 1;
        }
        unset($dpBankData);
        return '监管信息添加失败';
    }

    /**
     * 更新监管信息
     * @return array|int|string
     * 2018.9.5 ZJQ
     */
    private function updateBankDp($dpBankData) {
        $OrderDpBank = new OrderDpBank();
        $idstr = '';
        if ($dpBankData) {
            foreach ($dpBankData as $val) {
                $val['update_time'] = $this->time;
                $val['order_sn'] = $this->orderSn;
                if (isset($val['id']) && $val['id'] > 0) {//更新
                    $idstr .= $idstr == '' ? $val['id'] : ',' . $val['id'];
                    if ($OrderDpBank->isUpdate(true)->update($val) === false)
                        return '监管信息更新失败';
                } else {
                    unset($val['id']);
                    $val['create_time'] = $this->time;
                    if (($id = $OrderDpBank->insertGetId($val)) <= 0) {
                        return '监管信息写入失败';
                    }
                    $idstr .= $idstr == '' ? $id : ',' . $id;
                }
            }
            if ($idstr !== '') {//更新其他状态
                if ($OrderDpBank->where("order_sn='{$this->orderSn}' and id not in ({$idstr})")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                    return '原监管信息更新失败';
            }
        } else {
            if ($OrderDpBank->where("order_sn='{$this->orderSn}'")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                return '原监管信息更新失败';
        }
        return 1;
    }

    /**
     * 添加回款方式信息
     * @return array|int|string
     * 2018.9.5 ZJQ
     */
    private function addReturnMoney($ReturnMoney) {
        foreach ($ReturnMoney as &$value) {
            $value['order_sn'] = $this->orderSn;
        }
        $OrderR = new OrderGuaranteeReturn();
        if ($OrderR->saveAll($ReturnMoney) > 0) {
            unset($ReturnMoney);
            return 1;
        }
        unset($ReturnMoney);
        return '回款方式添加失败';
    }

    /**
     * 更新回款方式信息
     * @return array|int|string
     * 2018.9.5 ZJQ
     */
    private function updateReturnMoney($ReturnMoney) {
        $OrderGuaranteeReturn = new OrderGuaranteeReturn();
        $idstr = '';
        if ($ReturnMoney) {
            foreach ($ReturnMoney as $val) {
                $val['update_time'] = $this->time;
                $val['order_sn'] = $this->orderSn;
                if (isset($val['id']) && $val['id'] > 0) {//更新
                    $idstr .= $idstr == '' ? $val['id'] : ',' . $val['id'];
                    if ($OrderGuaranteeReturn->isUpdate(true)->update($val) === false)
                        return '回款方式更新失败';
                } else {
                    unset($val['id']);
                    $val['create_time'] = $this->time;
                    if (($id = $OrderGuaranteeReturn->insertGetId($val)) <= 0) {
                        return '回款方式写入失败';
                    }
                    $idstr .= $idstr == '' ? $id : ',' . $id;
                }
            }
            if ($idstr !== '') {//更新其他状态
                if ($OrderGuaranteeReturn->where("order_sn='{$this->orderSn}' and id not in ({$idstr})")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                    return '原回款方式更新失败';
            }
        } else {
            if ($OrderGuaranteeReturn->where("order_sn='{$this->orderSn}'")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                return '原回款方式更新失败';
        }
        return 1;
    }

    /* 添加垫资费信息 */

    private function addAdvance($advanceInfo) {
        foreach ($advanceInfo as $key => $val) {
            $advanceInfo[$key]['order_sn'] = $this->orderSn;
            $advanceInfo[$key]['create_uid'] = $this->userInfo['id'];
            $advanceInfo[$key]['create_time'] = $advanceInfo[$key]['update_time'] = $this->time;
        }
        $OrderAdvanceMoney = new OrderAdvanceMoney;
        if ($OrderAdvanceMoney->saveAll($advanceInfo) > 0) {
            unset($advanceInfo);
            return 1;
        }
        unset($advanceInfo);
        return '垫资费信息添加失败';
    }

    /* 更新垫资费信息 */

    private function updateAdvance($advanceInfo) {

        $OrderAdvanceMoney = new OrderAdvanceMoney();
        $idstr = '';
        if ($advanceInfo) {
            foreach ($advanceInfo as $val) {
                $val['update_time'] = $this->time;
                $val['create_uid'] = $this->userInfo['id'];
                if (isset($val['id']) && $val['id'] > 0) {//更新
                    $idstr .= $idstr == '' ? $val['id'] : ',' . $val['id'];
                    if ($OrderAdvanceMoney->isUpdate(true)->update($val) === false)
                        return '垫资费信息更新失败';
                } else {
                    unset($val['id']);
                    $val['create_time'] = $this->time;
                    $val['order_sn'] = $this->orderSn;
                    if (($id = $OrderAdvanceMoney->insertGetId($val)) <= 0) {
                        return '垫资费信息写入失败';
                    }
                    $idstr .= $idstr == '' ? $id : ',' . $id;
                }
            }
            if ($idstr !== '') {//更新其他状态
                if ($OrderAdvanceMoney->where("order_sn='{$this->orderSn}' and id not in ({$idstr})")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                    return '原垫资费信息更新失败';
            }
        } else {
            if ($OrderAdvanceMoney->where("order_sn='{$this->orderSn}'")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                return '原垫资费信息更新失败';
        }
        return 1;
    }

    /**
     * 添加渠道信息
     * @param $channelInfo
     * @return int|string
     * @throws \Exception
     */
    private function addChannel($channelInfo) {
        $channelData = array_map(function ($v) {
            $v['order_sn'] = $this->orderSn;
            $v['create_time'] = $v['update_time'] = $this->time;
            return $v;
        }, $channelInfo);
        $orderFundChannel = new OrderFundChannel;
        if ($orderFundChannel->saveAll($channelData) > 0) {
            unset($channelData);
            return 1;
        }
        unset($channelData);
        return '渠道信息添加失败';
    }

    /**
     * 更新渠道信息
     * @param $channelInfo
     * @return int|string
     * @throws \Exception
     */
    private function updateChannel($channelInfo) {
        $orderFundChannel = new OrderFundChannel();
        $idstr = '';
        if ($channelInfo) {
            foreach ($channelInfo as $val) {
                $val['update_time'] = $this->time;

                if (isset($val['id']) && $val['id'] > 0) {//更新
                    $idstr .= $idstr == '' ? $val['id'] : ',' . $val['id'];
                    if ($orderFundChannel->isUpdate(true)->update($val) === false)
                        return '渠道信息更新失败';
                } else {
                    unset($val['id']);
                    $val['create_time'] = $this->time;
                    $val['order_sn'] = $this->orderSn;
                    if (($id = $orderFundChannel->insertGetId($val)) <= 0) {
                        return '渠道信息写入失败';
                    }
                    $idstr .= $idstr == '' ? $id : ',' . $id;
                }
            }
            if ($idstr !== '') {//更新其他状态
                if ($orderFundChannel->where("order_sn='{$this->orderSn}' and id not in ({$idstr})")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                    return '原渠道信息更新失败';
            }
        } else {
            if ($orderFundChannel->where("order_sn='{$this->orderSn}'")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                return '原渠道信息更新失败';
        }
        return 1;
    }

    //添加客户信息
    private function addOrderCustomer($sellerInfos, $financingManagerId) {
        foreach ($sellerInfos as &$sellerInfo) {
            $sellerInfo['create_time'] = $sellerInfo['update_time'] = $this->time;
            $sellerInfo['order_sn'] = $this->orderSn;
            $sellerInfo['financing_manager_id'] = $financingManagerId;
            $customer = new Customer;
            if (($id = $customer->insertGetId($sellerInfo)) > 0) {
                $cert['customer_id'] = $id;
                $cert['create_time'] = $this->time;
                $cert['certtype'] = $sellerInfo['certtype'];
                $cert['certcode'] = $sellerInfo['certcode'];
                $res = Db::name('customer_cert')->insert($cert);
                if ($res === false)
                    return '客户证件信息添加失败';
            } else {
                return '客户信息添加失败';
            }
        }
        unset($sellerInfos);
        return 1;
    }

    //添加客户信息
    private function updateOrderCustomer($sellerInfos, $financingManagerId) {
        $customer = new Customer();
        $idstr = '';
        if ($sellerInfos) {
            foreach ($sellerInfos as $val) {
                $val['update_time'] = $this->time;
                $val['financing_manager_id'] = $financingManagerId;
                if (isset($val['id']) && $val['id'] > 0) {//更新
                    $idstr .= $idstr == '' ? $val['id'] : ',' . $val['id'];
                    if ($customer->isUpdate(true)->update($val) === false)
                        return '客户信息更新失败';
                    if (Db::name('customer_cert')->where(['customer_id' => $val['id']])->update(['certtype' => $val['certtype'], 'certcode' => $val['certcode']]) === false)
                        return '证件信息更新失败';
                } else {
                    unset($val['id']);
                    $val['create_time'] = $this->time;
                    $val['order_sn'] = $this->orderSn;
                    if (($id = $customer->insertGetId($val)) > 0) {
                        $cert['customer_id'] = $id;
                        $cert['create_time'] = $this->time;
                        $cert['certtype'] = $val['certtype'];
                        $cert['certcode'] = $val['certcode'];
                        $res = Db::name('customer_cert')->insert($cert);
                        if ($res === false)
                            return '客户证件信息添加失败';
                    } else {
                        return '客户信息写入失败';
                    }
                    $idstr .= $idstr == '' ? $id : ',' . $id;
                }
            }
            if ($idstr !== '') {//更新其他状态
                if ($customer->where("order_sn='{$this->orderSn}' and id not in ({$idstr})")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                    return '原客户信息更新失败';
            }
        } else {
            if ($customer->where("order_sn='{$this->orderSn}'")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                return '原客户信息更新失败';
        }
        return 1;
    }

    //添加担保赎楼信息
    private function addGuarantee($guaranteeData) {
        //赎楼类业务页面有预计出账总额 非赎楼类预计出账总额等于垫资总计
        if ($this->type == 'JYDB' || $this->type == 'JYXJ' || $this->type == 'TMXJ' || $this->type == 'GMDZ') {
            if (!isset($guaranteeData['out_account_total'])) {
                return '预计出账总额参数有误';
            }
            if ($this->type == 'JYDB') {
                if ($guaranteeData['out_account_total'] > $guaranteeData['money']) {
                    return '预计出账总额不能大于担保金额';
                }
            } else {
                if ($guaranteeData['out_account_total'] != $guaranteeData['money']) {
                    return '预计出账总额不等于垫资总计';
                }
            }
        } else {
            $guaranteeData['out_account_total'] = $guaranteeData['money'];
        }
        $guaranteeData['order_sn'] = $this->orderSn;
        $guaranteeData['create_time'] = $guaranteeData['update_time'] = $this->time;
        $orderGuarantee = new OrderGuarantee;
        if ($orderGuarantee->save($guaranteeData) > 0) {
            unset($guaranteeData);
            return 1;
        }
        unset($guaranteeData);
        return '赎楼信息添加失败';
    }

    //更新担保赎楼信息
    private function updateGuarantee($guaranteeData) {
        //赎楼类业务页面有预计出账总额 非赎楼类预计出账总额等于垫资总计
        if ($this->type == 'JYDB' || $this->type == 'JYXJ' || $this->type == 'TMXJ' || $this->type == 'GMDZ') {
            if (!isset($guaranteeData['out_account_total'])) {
                return '预计出账总额参数有误';
            }
            if ($this->type == 'JYDB') {
                if ($guaranteeData['out_account_total'] > $guaranteeData['money']) {
                    return '预计出账总额不能大于担保金额';
                }
            } else {
                if ($guaranteeData['out_account_total'] != $guaranteeData['money']) {
                    return '预计出账总额不等于垫资总计';
                }
            }
        } else {
            $guaranteeData['out_account_total'] = $guaranteeData['money'];
        }
        $guaranteeData['update_time'] = $this->time;
        $orderGuarantee = new OrderGuarantee;
        if ($orderGuarantee->save($guaranteeData, ['order_sn' => $this->orderSn, 'status' => 1]) > 0) {
            unset($guaranteeData);
            return 1;
        }
        unset($guaranteeData);
        return '赎楼信息更新失败';
    }

    //添加担保赎楼银行信息
    private function addGuaranteeBank($guaranteeBank) {
        if ($guaranteeBank) {
            foreach ($guaranteeBank as $k => &$val) {
                $bankuse = [];
                $bankuse = $val['bankuse'];
                unset($val['bankuse']);
                unset($val['id']);
                $val['order_sn'] = $this->orderSn;
                $val['create_time'] = time();
                $val['update_time'] = time();
                $OrderGuaranteeBank = new OrderGuaranteeBank;
                if ($OrderGuaranteeBank->save($val) < 1) {
                    unset($guaranteeBank);
                    return '银行卡信息存储失败';
                } else {
                    $newarr = [];
                    if (is_array($bankuse) && !empty($bankuse)) {
                        foreach ($bankuse as $key => $value) {
                            $newarr[$key]['order_guarantee_bank_id'] = $OrderGuaranteeBank->id;
                            $newarr[$key]['type'] = $value;
                        }
                        $OrderGuaranteeBanktype = new OrderGuaranteeBankType();
                        if (!$OrderGuaranteeBanktype->saveAll($newarr)) {
                            unset($guaranteeBank);
                            return '银行卡信息存储失败！';
                        }
                    } else {
                        unset($guaranteeBank);
                        return '银行信息参数有误';
                    }
                }
            }
            unset($guaranteeBank);
            return 1;
        } else {
            return 1;
        }
    }

    //更新担保赎楼银行信息
    private function updateGuaranteeBank($guaranteeBank) {
        $OrderGuaranteeBank = new OrderGuaranteeBank();
        if ($guaranteeBank) {
            $ids = DB::name('order_guarantee_bank')->where(['order_sn' => $this->orderSn, 'status' => 1])->column('id'); //获取原有所有状态正常的银行卡
            Db::startTrans();
            $upids = [];
            foreach ($guaranteeBank as $val) {
                if (isset($val['id']) && $val['id'] > 0) {//更新 
                    if (!Db::name('order_guarantee_bank_type')->where(['order_guarantee_bank_id' => $val['id']])->delete()) {
                        Db::rollback();
                        unset($guaranteeBank);
                        return '原银行信息更新失败';
                    }
                    $newarr = [];
                    if (is_array($val['bankuse']) && !empty($val['bankuse'])) {
                        foreach ($val['bankuse'] as $key => $value) {
                            $newarr[$key]['order_guarantee_bank_id'] = $val['id'];
                            $newarr[$key]['type'] = $value;
                        }
                        $OrderGuaranteeBanktype = new OrderGuaranteeBankType();
                        if (!$OrderGuaranteeBanktype->saveAll($newarr)) {
                            Db::rollback();
                            unset($guaranteeBank);
                            return '银行卡信息存储失败！';
                        }
                    } else {
                        Db::rollback();
                        unset($guaranteeBank);
                        return '银行信息参数有误';
                    }
                    unset($val['bankuse']);
                    Db::name('order_guarantee_bank')->where(['id' => $val['id']])->update($val); //更新原银行卡信息
                    $upids[] = $val['id']; //所有更新的卡
                } else {
                    $res = $this->addGuaranteeBank([$val], $this->orderSn);
                    if ($res != 1) {
                        return $res;
                    };
                }
            }
            $diffids = array_diff($ids, $upids);
            if (!empty($diffids)) {
                $where['order_sn'] = $this->orderSn;
                $where['id'] = array('in', $diffids); //获取没有传来的的卡，软删除
                if ($OrderGuaranteeBank->where($where)->update(['status' => -1, 'delete_time' => $this->time]) === false) {//更新其他状态
                    Db::rollback();
                    return '原银行信息更新失败';
                }
            }
            DB::commit();
            return 1;
        } else {
            if ($OrderGuaranteeBank->where("order_sn='{$this->orderSn}'")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                return '原银行信息更新失败';
        }
        return 1;
    }

    //添加附件
    private function addAttachment() {
        $attach = $this->request->post('attach/a');

        if ($attach) {
            $attachArr = [];
            foreach ($attach as $key => $att) {

                $attachArr[$key]['attachment_id'] = $att['attachment_id'];
                $attachArr[$key]['order_sn'] = $this->orderSn;
                $attachArr[$key]['create_time'] = $this->time;
            }
            if (Db::name('order_attachment')->insertAll($attachArr) > 0) {
                unset($attachArr);
                return 1;
            }
            unset($attachArr);
            return '附件添加失败';
        } else {
            return 1;
        }
    }

    //添加附件
    private function updateAttachment() {
        $attach = $this->request->post('attach/a');
        $attachment = Db::name('order_attachment');
        $idstr = '';
        if (isset($attach[0]) && !empty($attach[0])) {
            foreach ($attach as $val) {
                if (isset($val['id']) && $val['id'] > 0) {//更新
                    $idstr .= $idstr == '' ? $val['id'] : ',' . $val['id'];
                } else {
                    unset($val['id']);
                    if (!isset($val['attachment_id']) || $val['attachment_id'] <= 0)
                        return '附件参数有误';
                    $val['create_time'] = $this->time;
                    $val['order_sn'] = $this->orderSn;
                    if (($id = $attachment->insertGetId($val)) <= 0) {
                        return '附件编辑失败';
                    }
                    $idstr .= $idstr == '' ? $id : ',' . $id;
                }
            }
            if ($idstr !== '' && $attachment->where("order_sn='{$this->orderSn}' and id not in ({$idstr})")->update(['status' => -1, 'delete_time' => $this->time]) === false) {//更新其他状态
                return '原附件信息更新失败';
            }
        } else {
            //没有附件
            if ($attachment->where("order_sn='{$this->orderSn}'")->update(['status' => -1, 'delete_time' => $this->time]) === false)
                return '原附件信息更新失败';
        }
        return 1;
    }

    /**
     * 获取财务号
     */
    private function financeSn() {
        $sequen = Db::name('system_sequence')->lock(true)->where(['type' => 'CWXH'])->find();
        if ($sequen) {
            if (Db::name('system_sequence')->where(['id' => $sequen['id']])->setInc('sequence', 1) === 1)
                return $sequen['sequence'] ++;
        }
        return false;
    }

    /**
     * 根据订单类型校验
     * @return array|mixed|string|true
     */
    private function orderCheck() {
        switch ($this->type) {
            case 'JYDB':
                return (new OrderCheck)->checkJYDB();
                break;
            case 'JYXJ':
                return (new OrderCheck)->checkJYXJ();
                break;
            case 'TMXJ':
                return (new OrderCheck)->checkTMXJ();
                break;
            case 'PDXJ':
                return (new OrderCheck)->checkPDXJ();
                break;
            case 'GMDZ':
                return (new OrderCheck)->checkGMDZ();
                break;
            case 'SQDZ':
                return (new OrderCheck)->checkSQDZ();
                break;
            case 'DQJK':
                return (new OrderCheck)->checkDQJK();
                break;
            default:
                return '订单类型无效';
        }
    }

    /**
     * @author: bordon
     * 撤单功能
     * 1）撤单成功， 订单状态变为：已撤单， 订单不可再编辑
     * 2）功能入口，订单详情页右上角，增加： 撤单  按钮
     * 3）业务助理： 在“待审查员审批”状态前， 可以直接撤单； 在“待审查员审批”状态及后续状态，撤单按钮不可见，要撤单单独走撤单工作流
     * 4）超级特权人账号：可以随时撤单， 但在跟单员申请出账后，或到了“待取红本”状态，则不可再撤单，并提示：订单当前不支持撤单
     */

    /**
     * @api {post} admin/Orders/recallOrder 撤单 [admin/Orders/recallOrder]
     * @apiVersion 1.0.0
     * @apiName recallOrder
     * @apiGroup Order
     * @apiSampleRequest admin/Orders/recallOrder
     *
     * @apiParam {string} order_sn    订单编号   说明：订单状态<1004 或者是 特权人，并且有按钮权限则显示撤单按钮
     *
     */
    public function recallOrder() {
        $order_sn = $this->request->post('order_sn', '', 'trim');
        if (!$order_sn) {
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数错误');
        }
        if (!check_auth([$this->auth_group['privileged_people'], $this->auth_group['business_assistant']], $this->userInfo['group'])) {
            return $this->buildFailed(ReturnCode::INVALID, '没有权限');
        }
        $order = Order::get(['order_sn' => $order_sn, 'status' => 1]);
        if (!$order) {
            return $this->buildFailed(ReturnCode::INVALID, '请勿异常操作');
        }
        // 业务助理： 在“待审查员审批”状态前， 可以直接撤单
        if ($order->stage < 1004 && check_auth($this->auth_group['business_assistant'], $this->userInfo['group'])) {
            $result = $this->Recall($order);
        }
        // 申请出账
        if (OrderRansomOut::where('order_sn', $order->order_sn)->where('account_status', 'neq', 4)->count() > 0) {
            return $this->buildFailed(ReturnCode::INVALID, '订单已申请出账，不支持撤单');
        }
        //超级特权人账号：可以随时撤单， 但在跟单员申请出账后，或到了“待取红本”状态，则不可再撤单，并提示：订单当前不支持撤单
        if ($order->stage < 1015 && check_auth($this->auth_group['privileged_people'], $this->userInfo['group'])) {
            $result = $this->Recall($order);
        }
        if (isset($result)) {
            if ($result) {
                return $this->buildSuccess();
            } else {
                return $this->buildFailed(ReturnCode::INVALID, '操作失败');
            }
        } else {
            return $this->buildFailed(ReturnCode::INVALID, '不允许撤回订单，请先申请撤单工作流');
        }
    }

    /*     * 修改订单状态为撤回
     * @param Order $order
     * @return false|int
     * @author: bordon
     */

    private function Recall(Order $order) {
        $stageStr = (new Dictionary)->getValnameByCode('ORDER_JYDB_STATUS', $order->stage);
        if ($this->checkPrivilegeAuth()) {
            $tip = '特权人撤单';
        } else {
            $tip = '业务助理撤单';
        }
        OrderComponents::addOrderLog($this->userInfo, $order->order_sn, $stageStr, $stageStr, $stageStr, $tip, '', $order->stage);
        $order->stage = 1023;
        $order->status = 2;
        return $order->save();
    }

    /*     * 特权人权限
     * @return bool
     * @author: bordon
     */

    private function checkPrivilegeAuth() {
        return check_auth($this->auth_group['privileged_people'], $this->userInfo['group']);
    }

    /**
     * @api {post} admin/Orders/exportOrderList 导出综合查询列表[admin/Orders/exportOrderList]
     * @apiVersion 1.0.0
     * @apiName exportOrderList
     * @apiGroup Orders
     * @apiSampleRequest admin/Orders/exportOrderList
     * @apiParam {string}  startTime   订单开始时间
     * @apiParam {string}  endTime   订单结束时间
     * @apiParam {string}  search   查询名称
     * @apiParam {string}  managerId   理财经理
     * @apiParam {string}  estateCity   所属城市
     * @apiParam {string}  estateDistrict   所属城区
     * @apiParam {string}  stage   订单状态
     * @apiParam {int}  subordinates   是否含下属1含下属

     * @apiParam type 订单类型
     * @apiSuccess {int} order_sn 订单编号
     * @apiSuccess {int} type 订单类型
     * @apiSuccess {int} typeStr 订单类型文本
     * @apiSuccess {int} create_time 报单时间
     * @apiSuccess {int} stage 订单状态
     * @apiSuccess {int} stageStr 订单状态描述
     * @apiSuccess {int} estate_region 房产地区
     * @apiSuccess {int} estate_name 房产名称
     * @apiSuccess {int} estate_owner 业主产权人
     * @apiSuccess {int} estateInfo 房产信息
     * @apiSuccess {int} name 理财经理姓名
     * @apiSuccess {int} financing_manager_id 理财经理id
     */
    public function exportOrderList() {
        $startTime = $this->request->post('startTime', '', 'strtotime');
        $endTime = $this->request->post('endTime', '', 'strtotime');
        $search = $this->request->post('search', '', 'trim');
        $managerId = $this->request->post('managerId', 0, 'int');
        $estateCity = $this->request->post('estateCity', '');
        $estateDistrict = $this->request->post('estateDistrict', '');
        $stage = $this->request->post('stage', 0, 'int');
        $subordinates = $this->request->post('subordinates', 0, 'int');
        $type = $this->request->post('type', '');
        $where = [];
        if ($startTime > $endTime) {
            $mtime = $startTime;
            $startTime = $endTime;
            $endTime = $mtime;
        }
        if ($managerId != '0') {
            if ($subordinates == '0') {
                $where['x.financing_manager_id'] = $managerId;
            } else {
                $managerStr = SystemUser::getOrderPowerStr($managerId);
                if ($managerStr != 'super')
                    $where['x.financing_manager_id'] = ['in', $managerStr];
            }
        }
        !empty($type) && $where['x.type'] = $type;
        if ($startTime && $endTime) {
            $startTime !== $endTime ? $where['x.create_time'] = ['between', [$startTime, $endTime + 86400]] : $where['x.create_time'] = ['between', [$startTime, $startTime + 86400]];
        } elseif ($startTime) {
            $where['x.create_time'] = ['egt', $startTime];
        } elseif ($endTime) {
            $where['x.create_time'] = ['elt', $endTime];
        }
        $search && $where['x.order_sn|y.estate_name'] = ['like', "%{$search}%"];
        $estateCity && $where['y.estate_ecity'] = $estateCity;
        $estateDistrict && $where['y.estate_district'] = $estateDistrict;
        $stage && $where['x.stage'] = $stage;
        //获取查询的用户数据
        $resultInfo = Order::allOrderList2($where);
        //return json($resultInfo);
        if ($resultInfo === false)
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, '订单读取失败!');
        $result = Order::getOrderInfo2($resultInfo);
        $dictonaryType = ['ORDER_JYDB_STATUS', 'ORDER_TYPE'];
        $dictonaryTypeArr = dictionary_reset(Dictionary::dictionaryMultiType($dictonaryType), 1);
        $newTypeArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE')); //业务类型查询
        $newTypeArr2 = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_TYPE')); //赎楼银行类型查询
        $newTypeArr3 = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_YWLY')); //业务来源类型查询
        if (!empty($result)) {
            //变量
            $num = 1;
            $list = array();
            $mortgagelist = array();
            $where2 = [];
            foreach ($result as $key => $val) {
                $list[$key]['nod'] = $num; //序号
                $num++;
                $list[$key]['order_sn'] = "\t" . $val['order_sn'] . "\t"; //订单号
                $list[$key]['finance_sn'] = "\t" . $val['finance_sn'] . "\t"; //财务单号
                $list[$key]['type'] = $newTypeArr[$val['type']] ? $newTypeArr[$val['type']] : ''; //业务类型
                //订单状态
                $list[$key]['stageStr'] = isset($dictonaryTypeArr['ORDER_JYDB_STATUS'][$val['stage']]) ? $dictonaryTypeArr['ORDER_JYDB_STATUS'][$val['stage']] : '';
                $list[$key]['estate_name'] = implode(';', Db::name('estate')->where(['order_sn' => $val['order_sn']])->column('estate_name')); //房产名称
                $list[$key]['estate_region'] = implode(';', Db::name('estate')->where(['order_sn' => $val['order_sn']])->column('estate_region')); //所属地区
                $list[$key]['estate_certnum'] = "\t" . implode(';', Db::name('estate')->where(['order_sn' => $val['order_sn']])->column('estate_certnum')) . "\t"; //房产证
                $list[$key]['cname'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_guarantee' => 1, 'status' => 1])->column('cname')) . "\t"; //担保申请人
                $list[$key]['certcode'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_guarantee' => 1, 'status' => 1])->column('certcode')) . "\t"; //担保申请人身份证
                //卖方
                $list[$key]['bname'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_seller' => 2, 'status' => 1, 'is_comborrower' => 0])->column('cname')) . "\t";
                //卖方身份证
                $list[$key]['bcertcode'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_seller' => 2, 'status' => 1, 'is_comborrower' => 0])->column('certcode')) . "\t";
                //卖方共同借款人
                $list[$key]['ccomborrower'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_seller' => 2, 'is_comborrower' => 1, 'status' => 1])->column('cname')) . "\t";
                //卖方共同借款人身份证
                $list[$key]['ccertcode'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_seller' => 2, 'is_comborrower' => 1, 'status' => 1])->column('certcode')) . "\t";
                //买方
                $list[$key]['dcomborrower'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_seller' => 1, 'is_comborrower' => 0, 'status' => 1])->column('cname')) . "\t";
                //买方身份证   
                $list[$key]['dcertcode'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_seller' => 1, 'is_comborrower' => 0, 'status' => 1])->column('certcode')) . "\t";
                //买方共同借款人
                $list[$key]['aname'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_seller' => 1, 'status' => 1, 'is_comborrower' => 1])->column('cname')) . "\t";
                //买方共同借款人身份证
                $list[$key]['acertcode'] = "\t" . implode(';', Db::name('customer')->where(['order_sn' => $val['order_sn'], 'is_seller' => 1, 'status' => 1, 'is_comborrower' => 1])->column('certcode')) . "\t";

                $list[$key]['evaluation_price'] = $val['evaluation_price']; //估计价
                $list[$key]['money'] = $val['money'];                      //担保金额
                $list[$key]['guarantee_fee'] = $val['guarantee_fee']; //预收担保费
                $list[$key]['info_fee'] = $val['info_fee']; //预计信息费
                $list[$key]['fee'] = $val['fee']; //手续费
                //查询出所有的赎楼银行               
                $mortgage = Db::name('order_mortgage')->field('organization,mortgage_type')->where(['order_sn' => $val['order_sn'], 'status' => 1, 'type' => 'ORIGINAL'])->select();
                $val['mortgage_sum'] = '';
                foreach ($mortgage as $k => $v) {
                    $val['mortgage_sum'] .= $v['organization'] . '(' . $newTypeArr2[$v['mortgage_type']] . ')' . ';';
                }
                $list[$key]['mortgage_sum'] = $val['mortgage_sum']; //赎楼银行
                $list[$key]['ac_guarantee_fee'] = $val['ac_guarantee_fee']; //实收担保费
                $list[$key]['ac_fee'] = $val['ac_fee']; //实收手续费
                $list[$key]['ac_overdue_money'] = $val['ac_overdue_money']; //逾期费
                $list[$key]['ac_exhibition_fee'] = $val['ac_exhibition_fee']; //展期费
                $list[$key]['ac_transfer_fee'] = $val['ac_transfer_fee']; //过账手续费
                $list[$key]['hang_achievement'] = $val['hang_achievement']; //挂账业绩
                $list[$key]['order_finish_achievement'] = $val['order_finish_achievement']; //结单业绩
                $list[$key]['create_time'] = $val['create_time']; //报单时间
                $list[$key]['notarization'] = $val['notarization']; //公证时间
                $list[$key]['order_finish_date'] = $val['order_finish_date']; //结单时间
                $list[$key]['is_normal'] = $val['is_normal'] == 0 ? '正常' : '异常单'; //是否正常
                $list[$key]['risk_rating'] = $val['risk_rating']; //风险等级
                $list[$key]['review_rating'] = $val['review_rating']; //审查评级                
                $inspector = Db::name('system_user')->field('name')->where(['id' => $val['inspector_id']])->find();
                $list[$key]['inspector'] = $inspector['name']; //审查员
                //审查主管（经理）查询
                $where2['wp.order_sn'] = $val['order_sn'];
                $where2['wp.process_id'] = ['in', '15,16'];
                $where2['wf.type'] = 'JYDB_RISK';
                $where2['wp.status'] = 9;
                $where2['wp.is_back'] = 0;
                $where2['wp.is_deleted'] = 1;
                $whwhere2ere['wf.status'] = 1;
                $inspectorInfo = Db::name('workflow_proc')->alias('wp')
                        ->join('workflow_flow wf', 'wp.flow_id = wf.id')
                        ->where($where2)
                        ->field('wp.user_name,wp.process_id')
                        ->find();
                $list[$key]['inspectorA'] = $inspectorInfo['process_id'] == 15 ? $inspectorInfo['user_name'] : ''; //审查主管
                $list[$key]['inspectorB'] = $inspectorInfo['process_id'] == 16 ? $inspectorInfo['user_name'] : ''; //审查经理
                $financingManager = Db::name('system_user')->field('name,mobile,num,deptname')->where(['id' => $val['financing_manager_id']])->find();
                $list[$key]['financing_manager_name'] = $financingManager['name']; //理财经理
                $list[$key]['financing_manager_mobile'] = "\t" . $financingManager['mobile'] . "\t"; //理财经理电话
                $list[$key]['financing_manager_num'] = "\t" . $financingManager['num'] . "\t"; //理财经理工号
                $list[$key]['financing_manager_deptname'] = $financingManager['deptname']; //所属部门
                $orderStaff = Db::name('order_staff')->field('name,uid,role_type')->where(['order_sn' => $val['order_sn'], 'status' => 1])->select();
                $dm = Db::name('order_staff')->field('name,uid')->where(['order_sn' => $val['order_sn'], 'status' => 1, 'role_type' => 'DM'])->find();
                $list[$key]['dmname'] = $dm ? $dm['name'] : ''; //部门经理
                $list[$key]['dmnum'] = $dm ? implode(' ', Db::name('system_user')->field('num')->where(['id' => $dm['uid']])->column('num')) : ''; //部门经理工号
                $am = Db::name('order_staff')->field('name,uid')->where(['order_sn' => $val['order_sn'], 'status' => 1, 'role_type' => 'AM'])->find();
                $list[$key]['amname'] = $am ? $am['name'] : ''; //区域经理
                $list[$key]['amnum'] = $am ? implode(' ', Db::name('system_user')->field('num')->where(['id' => $am['uid']])->column('num')) : ''; //区域经理工号
                $ci = Db::name('order_staff')->field('name,uid')->where(['order_sn' => $val['order_sn'], 'status' => 1, 'role_type' => 'CI'])->find();
                $list[$key]['ciname'] = $ci ? $ci['name'] : ''; //区域总经理
                $list[$key]['cinum'] = $ci ? implode(' ', Db::name('system_user')->field('num')->where(['id' => $ci['uid']])->column('num')) : ''; //区域总经理工号
                $list[$key]['order_source'] = $val['order_source'] != '' ? $newTypeArr3[$val['order_source']] : ''; //业务来源
                $list[$key]['order_source_info'] = $val['source_info']; //业务来源机构
            }
        }
        try {
            $spreadsheet = new Spreadsheet();
            $resInfo = $list;
            $head = ['0' => '序号', '1' => '业务单号', '2' => '财务编号', '3' => '业务类型', '4' => '订单状态',
                '5' => '房产名称', '6' => '所属城区', '7' => '房产证', '8' => '担保申请人', '9' => '担保申请人身份证', '10' => '卖方姓名', '11' => '卖方身份证', '12' => '卖方共同借款人姓名', '13' => '卖方共同借款人身份证', '14' => '买方姓名', '15' => '买方身份证', '16' => '买方共同借款人姓名',
                '17' => '买方共同借款人身份证', '18' => '成交价（评估价）/元', '19' => '担保金额/元', '20' => '预收担保费/元', '21' => '预计信息费/元', '22' => '手续费/元', '23' => '赎楼银行', '24' => '实收担保费/元', '25' => '实收手续费/元', '26' => '逾期费/元', '27' => '展期费/元', '28' => '过账手续费/元', '29' => '挂账业绩', '30' => '结单业绩', '31' => '报单日期', '32' => '公证日期', '33' => '结单日期', '34' => '是否正常单', '35' => '风险等级', '36' => '审查评级', '37' => '审查员', '38' => '审查主管', '39' => '审查经理', '40' => '理财经理', '41' => '理财经理电话', '42' => '理财经理工号', '43' => '所属部门', '44' => '部门经理', '45' => '部门经理工号', '46' => '区域经理', '47' => '区域经理工号', '48' => '区域总经理', '49' => '区域总经理工号', '50' => '业务来源', '51' => '业务来源机构'];
            array_unshift($resInfo, $head);
            //$fileName = iconv("UTF-8", "GB2312//IGNORE", '综合查询列表' . date('Y-m-dHis'));
            $fileName = '' . date('Y-m-dHis'); //OrderList
            //$fileName = '综合查询列表'.date('Y-m-d').mt_rand(1111,9999);

            $spreadsheet->getActiveSheet()->fromArray($resInfo);
            $spreadsheet->getActiveSheet()->getStyle('A1:AZ1')->getFont()->setBold(true)->setName('Arial')->setSize(12);
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(18);
            $worksheet = $spreadsheet->getActiveSheet();
            $styleArray = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $worksheet->getStyle('A1:AZ1')->applyFromArray($styleArray);
            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $Path = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'download' . DS . date('Ymd');
            if (!file_exists($Path)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($Path, 0700, true);
            }
            $pathName = $Path . DS . $fileName . '.Xlsx';
            $objWriter->save($pathName);
            $retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS . iconv("GB2312", "UTF-8", $fileName) . '.Xlsx';
            return $this->buildSuccess(['url' => $retuurl]);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '导出失败!' . $e->getMessage());
        }
    }

}
