<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/6/27
 * Time: 13:38
 */
namespace app\admin\controller;

use app\util\ReturnCode;
use app\util\OrderComponents;
use app\model\Order;
use app\model\SystemUser;
use app\model\OrderFundChannel;
use think\Db;
use app\model\OrderGuarantee;
use app\model\Dictionary;
use app\model\OrderAccountRecord;
use app\model\Customer;
use app\model\FundChannel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class CashBusiness extends Base {
    /**
     * @api {post} admin/CashBusiness/channelsInstructionList 发送指令(渠道)待发送[admin/CashBusiness/channelsInstructionList]
     * @apiVersion 1.0.0
     * @apiName channelsInstructionList
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelsInstructionList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int} fund_channel_id    资金渠道id
     * @apiParam {int}  instruct_status   指令状态（1待申请 2待财务审核 3待发送 4已经发送）
     * @apiParam {int}  is_lend   是否放款（1是 2否）
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
     *       "total": 2,
     *       "per_page": 20,
     *       "current_page": 1,
     *       "last_page": 1,
     *       "data": [
     *           {
     *           "order_sn": "JYXJ2018060063",       订单编号
     *           "finance_sn": "100000337",          财务序号
     *           "type": "交易现金",                 订单类型
     *           "name": "刘颖6",                    理财经理
     *           "estate_name": "名称1阁栋名称1010",        房产名称
     *           "estate_owner": "张三",                    业主姓名
     *           "instruct_status": 1,                      指令状态（1待申请 2待财务审核 3待发送 4已经发送）
     *           "id":123,                           订单资金渠道表id
     *           "is_loan_finish": 0,                       是否放款  0否  1是
     *           "fund_channel_name": "永安",               资金渠道
     *           "money": "1000.00",                        垫资金额
     *           "instruct_sendtime" : "2018-10-10"         指令发送时间
     *           "type_text": "JYXJ"                        订单类型(简写)
     *           },
     *           {
     *           "order_sn": "SQDZ2018060012",
     *           "finance_sn": "100000328",
     *           "type": "首期款垫资",
     *           "name": "刘颖6",
     *           "estate_name": "名称1阁栋名称1010",
     *           "estate_owner": "张三",
     *           "instruct_status": 0,
     *           "is_loan_finish": 0,
     *           "fund_channel_name": "永安",
     *           "money": "1000.00",
     *           "type_text": "SQDZ"
     *           }
     *       ]
     *   }
     */

    public function channelsInstructionList(){
        $res = $this->channelsInstructWhere(1);
        $field = 'o.order_sn,o.finance_sn,o.type,z.name,y.estate_name,y.estate_owner,x.instruct_status,x.id,x.is_loan_finish,x.fund_channel_name,x.money';
        try{
            return $this->buildSuccess(OrderFundChannel::instructionList($res['map'],$field,$res['page'],$res['pageSize']));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /**
     * @api {post} admin/CashBusiness/channelsHasList 发送指令(渠道)已发送[admin/CashBusiness/channelsHasList]
     * @apiVersion 1.0.0
     * @apiName channelsHasList
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelsHasList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int} fund_channel_id    资金渠道id
     * @apiParam {int}  instruct_status   指令状态（1待申请 2待财务审核 3待发送 4已经发送）
     * @apiParam {int}  is_lend   是否放款（1是 2否）
     * @apiParam {int}  start_time   开始时间(2018-08-10)
     * @apiParam {int}  end_time   结束时间(2018-09-10)
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     */

    public function channelsHasList(){
        $res = $this->channelsInstructWhere(2);
        $field = 'o.order_sn,o.finance_sn,o.type,z.name,y.estate_name,y.estate_owner,x.instruct_status,x.id,x.is_loan_finish,x.fund_channel_name,x.money,x.instruct_sendtime';
        try{
            return $this->buildSuccess(OrderFundChannel::instructionList($res['map'],$field,$res['page'],$res['pageSize']));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /*
     * @author 赵光帅
     * 渠道发送指令列表的条件
     * @Param {int}  $typeList   1 未发送列表  2 已发送列表
     * */
    protected function channelsInstructWhere($typeList){
        $createUid = input('create_uid')?:0;
        $subordinates = input('subordinates')?:0;
        $type = input('type');
        $fund_channel_id = input('fund_channel_id');
        $instruct_status = input('instruct_status')?:0;
        $is_lend = input('is_lend')?:0;
        $startTime = input('start_time');
        $endTime = input('end_time');
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : 10;
        $userId = $this->userInfo['id'];
        //$userId = 2;
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['o.financing_manager_id'] = ['in', $userStr];
        }
        $type && $map['o.type'] = $type;
        $fund_channel_id && $map['x.fund_channel_id'] = $fund_channel_id;
        if(!empty($is_lend) && $is_lend == 1){
            $map['x.is_loan_finish'] = 1;
        }elseif (!empty($is_lend) && $is_lend == 2){
            $map['x.is_loan_finish'] = 0;
        }

        if($startTime && $endTime){
            if($startTime > $endTime){
                $startTime = date('Y-m-d',strtotime($startTime)+86399);
                $map['x.instruct_sendtime'] = array(array('egt', $endTime), array('elt', $startTime));
            }else{
                $endTime = date('Y-m-d',strtotime($endTime)+86399);
                $map['x.instruct_sendtime'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        }elseif($startTime){
            $map['x.instruct_sendtime'] = ['egt',$startTime];
        }elseif($endTime){
            $endTime = date('Y-m-d',strtotime($endTime)+86399);
            $map['x.instruct_sendtime'] = ['elt',$endTime];
        }

        $searchText && $map['y.estate_name|o.order_sn|y.estate_owner']=['like', "%{$searchText}%"];
        $map['x.delivery_status'] = 2;
        if($typeList === 1){
            if(empty($instruct_status)){
                $map['x.instruct_status'] = ['in','1,3'];
            }else{
                $map['x.instruct_status'] = $instruct_status;
            }
        }else{
            $map['x.instruct_status'] = 4;
        }

        $map['x.status'] = 1;
        $map['x.is_instruct'] = 1;

        return ['map' => $map, 'page' => $page, 'pageSize' => $pageSize];
    }

    /**
     * @api {post} admin/CashBusiness/getFundChannel 所有的资金渠道信息[admin/CashBusiness/getFundChannel]
     * @apiVersion 1.0.0
     * @apiName getFundChannel
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/getFundChannel
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *
     * [
            {
            "id": 2,             渠道id
            "name": "华安"       渠道名称
            },
            {
            "id": 3,
            "name": "365"
            }
        ]
     *
     */

    public function getFundChannel(){
        $res = FundChannel::getAll(['id' => ['<>',1], 'status' => ['<>',-1]], 'id,name');
        return $this->buildSuccess($res);
    }

    /**
     * @api {post} admin/CashBusiness/channelsInfo 垫资出账表[admin/CashBusiness/channelsInfo]
     * @apiVersion 1.0.0
     * @apiName channelsInfo
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelsInfo
     *
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  id   资金渠道表id
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *  "data": {
    "basic_information": {                      基本信息
    "order_sn": "JYDB2018050137123456",    业务单号
    "type": "JYDB",        业务类型
    "finance_sn": "100000048",      财务序号
    "order_source": 1,
    "source_info": "万科地产",          订单来源
    "order_source_str": "合作中介",     来源机构
    "financing_manager_name": "夏丽平",    理财经理
    "dept_manager_name": "杜欣",           部门经理
    "deptname": "总经办"                   所属部门
    },
    "estate_info": [   房产信息
    {
    "estate_name": "国际新城一栋",                  房产名称
    "estate_region": "深圳市|罗湖区|桂园街道",      所属城区
    "estate_area": 70,                             房产面积
    "estate_certtype": 1,                          产证类型
    "estate_certnum": 11111,                       产证编码
    "house_type": 1                                房产类型 1分户 2分栋
    },
    {
    "estate_name": "国际新城一栋",
    "estate_district": "440303",
    "estate_area": 70,
    "estate_certtype": 1,
    "estate_certnum": 11111,
    "house_type": 1
    }
    ],
    "seller_info": [    买方信息(is_seller = 1 && is_comborrower = 0) 买方共同借款人(is_seller = 1 && is_comborrower = 1)
    {               卖方信息(is_seller = 2 && is_comborrower = 0) 卖方共同借款人(is_seller = 2 && is_comborrower = 1)
    "is_seller": 2,
    "is_comborrower": 0,           共同借款人属性 0借款人 1共同借款人
    "cname": "张三",                 卖方姓名
    "ctype": 1,                      卖方类型 1个人 2企业
    "certtype": 1,                   证件类型
    "certcode": "11111122322",       证件号码
    "mobile": "18825454079",         电话号码
    "is_guarantee": 0                 担保申请人 1是 0否
    "is_seller_str": "买方",
    "is_comborrower_str": "买方共同借款人",     所属角色
    "ctype_str": "个人",                      卖方类型
    "certtype_str": "身份证"                    证件类型
    },
    {
    "cname": "张三",
    "ctype": 1,
    "certtype": 1,
    "certcode": "11111122322",
    "mobile": "18825454079",
    "is_guarantee": 0
    }
    ],
    "sqk_info": {                               首期款信息
        "dp_strike_price": "5900000.00",             成交价格
        "dp_earnest_money": "80000.00",             定金金额
        "dp_supervise_guarantee": null,            担保公司监管
        "dp_supervise_buyer": null,                 买方本人监管
        "dp_supervise_bank": "建设银行",           监管银行
        "dp_supervise_bank_branch": null,
        "dp_supervise_date": "2018-04-24",         监管日期
        "dp_buy_way": "按揭购房",                  购房方式
        "dp_now_mortgage": "5.00"                  现按揭成数
        },
    "mortgage_info": [                               现按揭信息
            {
            "type": "NOW",
            "mortgage_type": 1,
            "money": "900000.00",                     现按揭金额
            "organization_type": "1",
            "organization": "建设银行-深圳振兴支行",     现按揭机构
            "mortgage_type_str": "公积金贷款",           现按揭类型
            "organization_type_str": "银行"         现按揭机构类型
            },
            {
            "type": "NOW",
            "mortgage_type": 2,
            "money": "2050000.00",
            "organization_type": "1",
            "organization": "建设银行-深圳振兴支行",
            "mortgage_type_str": "商业贷款",
            "organization_type_str": "银行"
            }
        ],
    "preliminary_question": [    风控初审问题汇总
    {
    "describe": "呵呵456",     问题描述
    "status": 0               是否解决  0未解决 1已经解决
    },
    {
    "describe": "呵呵帅那个帅789",
    "status": 0
    }
    ],
    "needing_attention": [   风控提醒注意事项
    {
    "process_name": "收到公司的",    来源
    "item": "啥打法是否"             注意事项
    },
    {
    "process_name": "测试",
    "item": "测试注意事项"
    }
    ],
    "reimbursement_info": [   银行账户信息
    {
    "bankaccount": "张三",   银行户名
    "accounttype": 1,        账户类型：1卖方 2卖方共同借款人 3买方 4买方共同借款人 5其它 6公司个人账户
    "bankcard": "111111",    银行卡号
    "openbank": "中国银行"    开户银行
    "type": 2,
    "verify_card_status": 0,
    "type_text": "尾款卡",                  账号用途
    "verify_card_status_text": "已完成",    核卡状态
    "accounttype_str": "卖方"               账号归属
    },
    {
    "bankaccount": "李四",
    "accounttype": 5,
    "bankcard": "111",
    "openbank": "工商银行"
    }
    ],

    "advancemoney_info": [    垫资费计算
    {
    "advance_money": "650000.00",     垫资金额
    "advance_day": 30,               垫资天数
    "advance_rate": 0.5,             垫资费率
    "advance_fee": "97500.0",        垫资费
    "remark": null,                  备注
    "id": 115
    }
    ],

    "cost_account":{     预收费用信息 与 实际费用入账
    "ac_guarantee_fee": "1000.00",   实收担保费
    "ac_fee": "-15.00",              手续费
    "ac_self_financing": "30.00",    自筹金额(实际费用入账)
    "short_loan_interest": "-12.30",   短贷利息
    "return_money": "12.50",           赎楼返还款
    "default_interest": "0.00",        罚息
    "overdue_money": "0.00",           逾期费
    "exhibition_fee": "1000.00",      展期费
    "transfer_fee": "10000.00",       过账手续费
    "other_money": "0.00"             其他
    "notarization": "2018-07-26",     公正日期
    "money": "46465.00",              担保金额(额度类)  垫资金额(现金类)
    "project_money_date": null,       预计用款日
    "guarantee_per": 0.35,            担保成数(垫资成数)
    "guarantee_rate": 4,              担保费率
    "guarantee_fee": "225826007.64",  预收担保费
    "account_per": 41986.52,          出账成数
    "fee": "456456.00",               预收手续费
    "self_financing": "30.00",        自筹金额(预收费用信息)
    "info_fee": "0.00",               预计信息费
    "total_fee": "226282463.64",      预收费用合计
    "return_money_mode": null,
    "return_money_amount": 1425,      回款金额
    "turn_into_date": null,           存入日期
    "turn_back_date": null,           转回日期
    "chuzhangsummoney": 5645650191    预计出账总额
    "return_money_mode_str": "直接回款"   回款方式
    },
    "lend_books": [    银行放款入账
    {
    " loan_money": "56786.00",             放款金额
    "lender_object": "中国银行",           放款银行
    "receivable_account": "中国银行账户",    收款账户
    "into_money_time": "2019-11-03",        到账时间
    "remark": "法国红酒狂欢节",             备注说明
    "operation_name": "杜欣"                入账人员
    },
    {
    " loan_money": "123456.00",
    "lender_object": "中国银行",
    "receivable_account": "中国银行账户",
    "into_money_time": "2019-11-02",
    "remark": "啊是的范德萨",
    "operation_name": "杜欣"
    }
    ],
    "arrears_info": [    欠款及预计出账金额
    {
    "ransom_status_text": "已完成",       赎楼状态
    "ransomer": "朱碧莲",         赎楼员
    "organization": "银行",      欠款机构名称
    "interest_balance": "111111.11",    欠款金额
    "mortgage_type_name": "商业贷款",   欠款类型
    "accumulation_fund": "2.00"         预计出账金额
    },
    {
    "organization": "银行",
    "interest_balance": "111111.11",
    "mortgage_type_name": "公积金贷款",
    "accumulation_fund": "2.00"
    }
    ],
    "fund_channel": [                 资金渠道信息
    {
    "fund_channel_name": "华安",      资金渠道
    "money": "2000000.00",             申请金额
    "actual_account_money": "2000000.00",    实际入账金额
    "is_loan_finish": 1,
    "trust_contract_num": "25421155",         信托合同号
    "loan_day": 5                             借款天数
    }
    "status_info": {        各种需要用到的其他字段
    "guarantee_fee_status": 2,     （担保费）收费状态 1未收齐 2已收齐
    "loan_money_status": 1,         银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理
    "instruct_status": 3,           主表指令状态（1待申请 2待发送 3已发送）
    "is_loan_finish": 1,             银行放款是否完成 0未完成 1已完成
    "loan_money": "4200000.00",      银行放款入账(实收金额总计)  资金渠道信息(渠道实际入账总计)
    "com_loan_money": null,
    "guarantee_fee": 14526          垫资费计算(垫资费总计)
    "is_comborrower_sell": 1       是否卖方有共同借款人 0否 1是
    "chile_instruct_status"  3     子单指令状态（资金渠道表指令状态）
    "is_dispatch": 1,               是否派单 0未派单 1已派单 2退回
    "endowmentsum": 120000          资金渠道信息(垫资总计)
    }
    }
     */

    public function channelsInfo(){
        $orderSn = input('order_sn');
        $channelId = input('id');
        if(empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        try{
            $returnInfo = [];
            //基本信息信息
            $resInfo = OrderComponents::orderJbInfo($orderSn);
            $returnInfo['basic_information'] = $resInfo;
            //房产信息
            $resInfo = OrderComponents::showEstateList($orderSn,'estate_name,estate_region,estate_area,estate_certtype,estate_certnum,house_type','DB');
            $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
            if($resInfo){
                foreach($resInfo as &$val){
                    $val['estate_certtype_str'] = $newStageArr[$val['estate_certtype']] ? $newStageArr[$val['estate_certtype']]:'';
                    $val['house_type_str'] = $val['house_type'] == 1?"分户":"分栋";
                }
            }
            $returnInfo['estate_info'] = $resInfo;
            //卖方信息(买方信息)
            $resInfo = OrderComponents::showCustomerInfo($orderSn, 'is_seller,is_comborrower,cname,ctype,certtype,certcode,mobile,is_guarantee');
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('CERTTYPE'));
            if ($resInfo) {
                foreach ($resInfo as &$val) {
                    $val['certtype_str'] = $newStageArr[$val['certtype']] ? $newStageArr[$val['certtype']] : '';
                    $val['is_guarantee_str'] = $val['is_guarantee'] == 1 ? '是' : '否';
                }
            }
            $returnInfo['seller_info'] = $resInfo;

            //首期款信息
            $resInfo = OrderComponents::orderDp($orderSn, 'dp_strike_price,dp_earnest_money,dp_supervise_guarantee,dp_supervise_buyer,dp_buy_way,dp_now_mortgage');
            if (isset($resInfo)) {
                $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('PURCHASE_WAY'));
                if (!empty($resInfo['dp_buy_way'])) {
                    $resInfo['dp_buy_way'] = $newStageArr[$resInfo['dp_buy_way']] ? $newStageArr[$resInfo['dp_buy_way']] : '';
                } else {
                    $resInfo['dp_buy_way'] = '';
                }
            }
            $returnInfo['sqk_info'] = $resInfo;
            //回款方式信息
            $returnInfo['returnMoney'] = OrderComponents::orderReturnMoney($orderSn);
            //监管信息
            $returnInfo['dbBankInfo'] = OrderComponents::orderBankDp($orderSn, 'id,dp_supervise_date,dp_money,dp_organization_type,dp_supervise_bank,dp_supervise_bank_branch,dp_organization');
            //现按揭信息
            $resInfo = OrderComponents::showMortgage($orderSn, 'type,mortgage_type,money,organization_type,organization','NOW');
            $newMortgageArr = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_TYPE'));
            $newAgencyArr = dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_AGENCY_TYPE '));
            if (!empty($resInfo)) {
                foreach ($resInfo as $k => $v){
                    $resInfo[$k]['mortgage_type_str'] = $newMortgageArr[$v['mortgage_type']] ? $newMortgageArr[$v['mortgage_type']] : '';
                    $resInfo[$k]['organization_type_str'] = $newAgencyArr[$v['organization_type']] ? $newAgencyArr[$v['organization_type']] : '';
                }
            }
            $returnInfo['mortgage_info'] = $resInfo;

            //实际出账收款账户
            //$returnInfo['collection_info'] = OrderComponents::showCollectionInfo($orderSn);
            //风控初审问题汇总
            $returnInfo['preliminary_question'] = OrderComponents::showPreliminary($orderSn);
            //风控提醒注意事项
            $returnInfo['needing_attention'] = OrderComponents::showNeedAtten($orderSn);
            //银行账户信息
            $resInfo = OrderComponents::showGuaranteeBank($orderSn, 'id,bankaccount,accounttype,bankcard,openbank,verify_card_status');
            $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('JYDB_ACCOUNT_TYPE'));
            if($resInfo){
                foreach($resInfo as &$val){
                    if(!empty($val['accounttype'])){
                        $val['accounttype_str'] = $newStageArr[$val['accounttype']] ? $newStageArr[$val['accounttype']]:'';
                    }
                }
            }
            $returnInfo['reimbursement_info'] = $resInfo;
            //垫资费计算
            $returnInfo['advancemoney_info'] = OrderComponents::advanceMoney($orderSn);

            //预收费用 实际费用入账
            $returnInfo['cost_account'] = OrderComponents::showChargeList($orderSn);
            //银行放款入账
            $returnInfo['lend_books'] = OrderComponents::showBankList($orderSn);
            //欠款及预计出账金额 => 原按揭信息
            $returnInfo['arrears_info'] = OrderComponents::showArrearsInfo($orderSn,'out_account,mortgage_type,organization,interest_balance','ORIGINAL');

            //资金渠道信息
            $returnInfo['fund_channel'] = OrderComponents::fundChannel($orderSn,'fund_channel_name,money,actual_account_money,is_loan_finish,trust_contract_num,loan_day');
            //查询出各种状态
            $returnInfo['status_info'] = OrderComponents::showStstusInfo($orderSn,$channelId);

            return $this->buildSuccess($returnInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /**
     * @api {post} admin/CashBusiness/channelsSend 指令发送(渠道)[admin/CashBusiness/channelsSend]
     * @apiVersion 1.0.0
     * @apiName channelsSend
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelsSend
     *
     *
     * @apiParam {int}  id   订单资金渠道表id
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  type   1申请发送 2确认发送 3撤回发送
     */

    public function channelsSend(){
        $channelId = input('id');
        $type = input('type');
        $orderSn = input('order_sn');
        if(empty($channelId) || empty($type) || empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        try{
            //渠道发送指令判断过账卡是否已经核卡完成
            $isPostingCard = $this->isPostingCard($orderSn);
            if(!empty($isPostingCard) && isset($isPostingCard))
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '过账卡未核卡,不能申请发送指令!');

            //渠道指令状态
            $guaranteeInfo = OrderFundChannel::where('id',$channelId)->field('instruct_status')->find();

            if($type == 1){
                //判断担保费是否已经收齐，未收齐则不能申请发送
                if($guaranteeInfo['instruct_status'] != 1) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '指令状态为待申请，才能申请发送!');
                $resinfo = OrderFundChannel::where('id',$channelId)->update(['instruct_status' => 2]);
                if(empty($resinfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '申请发送,更改指令状态失败!');
                $msg = "申请发送成功";
            }elseif ($type == 2){
                if($guaranteeInfo['instruct_status'] != 3) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '指令状态为待发送，才能确认发送!');
                $resinfo = OrderFundChannel::where('id',$channelId)->update(['instruct_status' => 4, 'instruct_sendtime' => date('Y-m-d',time())]);
                if(empty($resinfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '确认发送更改指令状态失败!');
                //判断是否所有的渠道指令状态都发送成功
                $instructStatusArr = OrderFundChannel::where('order_sn',$orderSn)->where('fund_channel_id','<>',1)->column('instruct_status');
                $uniqueStatus = array_unique($instructStatusArr);
                if(count($uniqueStatus) == 1){ //更改主指令状态为已发送
                    OrderGuarantee::where('order_sn',$orderSn)->update(['instruct_status' => 3]);
                }
                $msg = "确认发送成功";
            }elseif ($type == 3){
                if($guaranteeInfo['instruct_status'] != 4) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '指令状态为已发送，才能撤回发送!');
                OrderFundChannel::where('id',$channelId)->update(['instruct_status' => 3]);
                //更改主指令状态为待发送
                OrderGuarantee::where('order_sn',$orderSn)->update(['instruct_status' => 2]);
                $msg = "撤回发送成功";
            }else{
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '参数type必须为1或者2或者3');
            }
            /*添加订单操作记录*/
            //根据订单号查询出订单状态
            $stageInfo = Order::getOne(['order_sn' => $orderSn],'stage');
            if(strlen($stageInfo['stage']) == 4){
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_STATUS');
            }else{
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_FINC_STATUS');
            }
            $operate_node = "渠道发送指令";
            $operate_det = $this->userInfo['name'].$msg;
            $operate_reason = '';
            $stage_code = $stageInfo['stage'];
            $operate_table = 'order';
            OrderComponents::addOrderLog($this->userInfo,$orderSn, $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
            return $this->buildSuccess($msg);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '发送失败!'.$e->getMessage());
        }
    }

    /*
     *  判断过账卡是否核卡完成
     *  @Param {string} $order_sn 订单编号
     *  @return {int}   null 过账卡核卡完成  否则过账卡核卡未完成
     */
    protected function isPostingCard($order_sn){
        return Db::name('order_guarantee_bank')->alias('a')
            ->join('order_guarantee_bank_type b', 'a.id = b.order_guarantee_bank_id','left')
            ->where(['a.order_sn' => $order_sn, 'a.verify_card_status' => ['<>' ,'3'], 'b.type' => 3])
            ->value('a.id');
    }


    /**
     * @api {post} admin/CashBusiness/channelsAuditList 渠道放款审核列表[admin/CashBusiness/channelsAuditList]
     * @apiVersion 1.0.0
     * @apiName channelsAuditList
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelsAuditList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int} fund_channel_id    资金渠道id
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
     *       "total": 2,
     *       "per_page": 20,
     *       "current_page": 1,
     *       "last_page": 1,
     *       "data": [
     *           {
     *           "order_sn": "JYXJ2018060063",       订单编号
     *           "finance_sn": "100000337",          财务序号
     *           "type": "交易现金",                 订单类型
     *           "name": "刘颖6",                    理财经理
     *           "estate_name": "名称1阁栋名称1010",        房产名称
     *           "estate_owner": "张三",                    业主姓名
     *           "instruct_status": 2,                      审核状态(固定为待财务审核)
     *           "id":123,                           订单资金渠道表id
     *           "fund_channel_name": "永安",               资金渠道
     *           "money": "1000.00",                        垫资金额
     *           "type_text": "JYXJ"                        订单类型(简写)
     *           },
     *           {
     *           "order_sn": "SQDZ2018060012",
     *           "finance_sn": "100000328",
     *           "type": "首期款垫资",
     *           "name": "刘颖6",
     *           "estate_name": "名称1阁栋名称1010",
     *           "estate_owner": "张三",
     *           "instruct_status": 2,
     *           "fund_channel_name": "永安",
     *           "money": "1000.00",
     *           "type_text": "SQDZ"
     *           }
     *       ]
     *   }
     */

    public function channelsAuditList(){
        $createUid = input('create_uid')?:0;
        $subordinates = input('subordinates')?:0;
        $type = input('type');
        $fund_channel_id = input('fund_channel_id');
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : 10;
        $userId = $this->userInfo['id'];
        //$userId = 2;
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['o.financing_manager_id'] = ['in', $userStr];
        }
        $type && $map['o.type'] = $type;
        $fund_channel_id && $map['x.fund_channel_id'] = $fund_channel_id;
        $searchText && $map['y.estate_name|o.order_sn|o.finance_sn|y.estate_owner']=['like', "%{$searchText}%"];
        $map['x.delivery_status'] = 2;
        $map['x.instruct_status'] = 2;
        //$map['x.instruct_status'] = ['in','2,3,4'];
        $map['x.status'] = 1;
        $field = 'o.order_sn,o.finance_sn,o.type,z.name,y.estate_name,y.estate_owner,x.instruct_status,x.id,x.fund_channel_name,x.money';
        try{
            return $this->buildSuccess(OrderFundChannel::instructionList($map,$field,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }


    /**
     * @api {post} admin/CashBusiness/channelsSubAudit 渠道放款审核提交审核[admin/CashBusiness/channelsSubAudit]
     * @apiVersion 1.0.0
     * @apiName channelsSubAudit
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelsSubAudit
     *
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  id   订单资金渠道表id
     * @apiParam {int}  type   1审核通过 2驳回
     * @apiParam {string}  item   驳回原因
     */

    public function channelsSubAudit(){
        $channelId = input('id');
        $type = input('type');
        $item = input('item');
        $orderSn = input('order_sn');
        if(empty($channelId) || empty($type) || empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        if($type == 2 && empty($item)) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '驳回原因不能为空!');
        $guaranteeInfo = OrderFundChannel::where('id',$channelId)->field('instruct_status')->find();
        if($guaranteeInfo['instruct_status'] != 2) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '指令状态为待财务审核，才能审批!');
        try{
            if($type == 1){ //审批通过
                $resinfo = OrderFundChannel::where('id',$channelId)->update(['instruct_status' => 3]);
                if(empty($resinfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '审批通过更改状态失败!');
                $msg = "审核通过";
            }else{  //驳回
                $resinfo = OrderFundChannel::where('id',$channelId)->update(['instruct_status' => 1]);
                if(empty($resinfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '驳回更改状态失败!');
                $msg = "驳回";
            }

            /*添加订单操作记录*/
            //根据订单号查询出订单状态
            $stageInfo = Order::getOne(['order_sn' => $orderSn],'stage');
            if(strlen($stageInfo['stage']) == 4){
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_STATUS');
            }else{
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_FINC_STATUS');
            }
            $operate_node = "渠道放款审核";
            $operate_det = $this->userInfo['name'].$msg;
            $operate_reason = $item;
            $stage_code = $stageInfo['stage'];
            $operate_table = 'order';
            OrderComponents::addOrderLog($this->userInfo,$orderSn, $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
            return $this->buildSuccess($msg);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '审批失败!');
        }
    }


    /**
     * @api {post} admin/CashBusiness/channelLendList 渠道放款待入账列表[admin/CashBusiness/channelLendList]
     * @apiVersion 1.0.0
     * @apiName channelLendList
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelLendList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  loan_money_status   银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
     *       "total": 2,                            总条数
     *       "per_page": 20,                        每页显示的条数
     *       "current_page": 1,                     当前页
     *       "last_page": 1,                        总页数
     *       "data": [
     *           {
                "order_sn": "GMDZ2018060007",        业务单号
                "finance_sn": "100000333",           财务序号
                "type": "更名赎楼垫资",              订单类型
                "name": "刘颖6",                     理财经理
                "estate_name": "名称1阁栋名称1010",    房产名称
                "estate_owner": "张三",                 业主姓名
                "id",                                   订单资金渠道表id
                "fund_channel_name": "365",             放款渠道
                "loan_money_time": "2018-02-01",        入账时间
                "money": "1000.00",                     申请金额
                "actual_account_money": null,           入账金额
                "loan_money_status": 2,                 入账状态 1待入账 2待复核 3已复核 4驳回待处理
                "trust_contract_num": "25421155",       信托合同号(推单号)
                "type_text": "GMDZ"                     订单类型(简称)
                },
                {
                "order_sn": "GMDZ2018060005",
                "finance_sn": "100000321",
                "type": "更名赎楼垫资",
                "name": "刘颖6",
                "estate_name": "名称1阁栋名称1010",
                "estate_owner": "张三",
                "fund_channel_name": "永安",
                "loan_money_time": null,
                "money": "1000.00",
                "actual_account_money": null,
                "loan_money_status": 1,
                "type_text": "GMDZ"
                }
     *       ]
     *   }
     */

    public function channelLendList(){
        $res = $this->channelLendWhere(1);
        try{
            return $this->buildSuccess(OrderFundChannel::bankList($res['map'],$res['page'],$res['pageSize']));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /**
     * @api {post} admin/CashBusiness/channelHasList 渠道放款已入账列表[admin/CashBusiness/channelHasList]
     * @apiVersion 1.0.0
     * @apiName channelHasList
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelHasList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  loan_money_status   银行放款入账状态 1待入账 2待复核 3已复核 4驳回待处理
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     */

    public function channelHasList(){
        $res = $this->channelLendWhere(2);
        try{
            return $this->buildSuccess(OrderFundChannel::bankList($res['map'],$res['page'],$res['pageSize']));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /*
     * @author 赵光帅
     * 组装渠道放款列表的条件
     * @Param {int}  $typeList   1 渠道放款待入账列表条件  2 渠道放款已入账列表条件
     * */
    protected function channelLendWhere($typeList){
        $createUid = input('create_uid')?:0;
        $subordinates = input('subordinates')?:0;
        $type = input('type');
        $startTime = input('start_time');
        $endTime = input('end_time');
        $loan_money_status = input('loan_money_status')?:0;
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['n.financing_manager_id'] = ['in', $userStr];
        }
        if($startTime && $endTime){
            if($startTime > $endTime){
                $startTime = date('Y-m-d',strtotime($startTime)+86399);
                $map['x.loan_money_time'] = array(array('egt', $endTime), array('elt', $startTime));
            }else{
                $endTime = date('Y-m-d',strtotime($endTime)+86399);
                $map['x.loan_money_time'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        }elseif($startTime){
            $map['x.loan_money_time'] = ['egt',$startTime];
        }elseif($endTime){
            $endTime = date('Y-m-d',strtotime($endTime)+86399);
            $map['x.loan_money_time'] = ['elt',$endTime];
        }
        $type && $map['n.type'] = $type;
        $searchText && $map['y.estate_name|n.order_sn|n.finance_sn|y.estate_owner']=['like', "%{$searchText}%"];
        $map['n.delete_time'] = NULL;
        $map['n.status'] = 1;
        $map['x.instruct_status'] = 4;
        if($typeList == 1){
            $map['x.loan_money_status'] = ['in','1,2,4'];
            $loan_money_status && $map['x.loan_money_status'] = $loan_money_status;
        }else{
            $map['x.loan_money_status'] = 3;
        }

        return ['map' => $map, 'page' => $page, 'pageSize' => $pageSize];
    }

    /**
     * @api {post} admin/CashBusiness/isQdFinish 渠道放款入账是否已收齐[admin/CashBusiness/isQdFinish]
     * @apiVersion 1.0.0
     * @apiName isQdFinish
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/isQdFinish
     *
     * @apiParam {int}  id   订单资金渠道表id
     * @apiParam {int}  type   是否收齐 1已收齐 2未收齐
     *
     */
    public function isQdFinish(){
        $channelId = input('id');
        $type = input('type');
        if(empty($type)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '是否收齐类型不能为空!');
        if(empty($channelId)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '渠道id不能为空!');
        try{
            $channelInfo = OrderFundChannel::get(['id' => $channelId]);
            if(empty($channelInfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '不存在该资金渠道信息!');

            if($type == 1){
                if($channelInfo->is_loan_finish == 1) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '渠道放款已收齐,不能再进行收齐!');
                if($channelInfo->actual_account_money < $channelInfo->push_order_money) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '实收金额总计小于推单金额,不可确认完成!');
                $channelInfo->is_loan_finish = 1;
                $channelInfo->loan_money_status = 2;
                $msg = "已收齐成功!";
                $content = "渠道放款已完成";
            }else{
                if(empty($channelInfo->is_loan_finish)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '渠道放款已经是未收齐,不能再操作未收齐!');
                if($channelInfo->loan_money_status == 3) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '渠道放款已复核成功,不能再操作未收齐!');
                $channelInfo->is_loan_finish = 0;
                $channelInfo->loan_money_status = 1;
                $msg = "取消收齐成功!";
                $content = "渠道放款未完成";
            }
            $channelInfo->save();

            /*添加订单操作记录*/
            $orderInfo = Order::getOne(['order_sn' => $channelInfo['order_sn']],'stage');
            $stage = $operate = show_status_name($orderInfo['stage'],'ORDER_JYDB_STATUS');
            $operate_node = "确认渠道放款是否完成";
            $operate_det = $this->userInfo['name'].',更改'.$content;
            $operate_reason = '';
            $stage_code = $orderInfo['stage'];
            $operate_table = '';
            OrderComponents::addOrderLog($this->userInfo,$channelInfo['order_sn'], $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
            return $this->buildSuccess($msg);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '操作失败');
        }
    }

    /**
     * @api {post} admin/CashBusiness/addChannelWater 添加加渠道放款入账[admin/CashBusiness/addChannelWater]
     * @apiVersion 1.0.0
     * @apiName addChannelWater
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/addChannelWater
     *
     * @apiParam {int}  id   订单资金渠道表id
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  finance_sn   财务序号
     * @apiParam {float}   loan_money  渠道放款金额
     * @apiParam {string}  lender_object   资金渠道
     * @apiParam {int}  bank_card_id   收款账户id
     * @apiParam {string}  receivable_account 收款账户
     * @apiParam {string}  into_money_time   到账时间(2018-01-09)
     * @apiParam {string}  remark   备注
     *
     */

    public function addChannelWater(){
        $channelId = $waterInfo['order_fund_channel_id'] = input('id');
        $waterInfo['order_sn'] = input('order_sn');
        $waterInfo['finance_sn'] = input('finance_sn');
        $waterInfo['loan_money'] = input('loan_money');
        $waterInfo['total_money'] = input('loan_money');
        $waterInfo['bank_card_id'] = input('bank_card_id');
        $waterInfo['lender_object'] = input('lender_object');
        $waterInfo['receivable_account'] = input('receivable_account');
        $waterInfo['into_money_time'] = input('into_money_time');
        $waterInfo['remark'] = input('remark');
        //$is_loan_finish = input('is_loan_finish')?:0;
        //验证器验证参数
        $valiDate = validate('BankLending');
        if(!$valiDate->check($waterInfo)){
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
        }
        // 启动事务
        Db::startTrans();
        try{
            $channelInfo = OrderFundChannel::get(['id' => $channelId]);
            if($channelInfo->loan_money_status == 3) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '渠道放款已复核成功,不能再进行入账!');
            //判断实际入账资金有没有大于垫资资金
            $abMoney = $channelInfo['actual_account_money'] + $waterInfo['loan_money'];
            if($abMoney > $channelInfo['push_order_money']) return $this->buildFailed(ReturnCode::UPDATE_FAILED, '该渠道实际入账资金总和大于推单金额，请确定后重新输入!');
            $waterInfo['create_time'] = time();
            $waterInfo['create_uid'] = $this->userInfo['id'];
            //添加银行放款入账流水
            OrderAccountRecord::create($waterInfo);
            //更改订单资金渠道表
            $channelInfo->actual_account_money = $channelInfo['actual_account_money'] + $waterInfo['loan_money'];
            $channelInfo->update_time = time();
            //$channelInfo->is_loan_finish = $is_loan_finish;
            //if($is_loan_finish == 1) $channelInfo->loan_money_status = 2;
            $channelInfo->save();
            /*添加订单操作记录*/
            //根据订单号查询出订单状态
            $stageInfo = Order::getOne(['order_sn' => $waterInfo['order_sn']],'stage,type');
            if(strlen($stageInfo['stage']) == 4){
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_STATUS');
            }else{
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_FINC_STATUS');
            }
            $operate_node = "渠道放款入账";
            $operate_det = $this->userInfo['name']."添加流水";
            $operate_reason = '';
            $stage_code = $stageInfo['stage'];
            $operate_table = 'order_fund_channel';
            OrderComponents::addOrderLog($this->userInfo,$waterInfo['order_sn'], $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
            // 提交事务
            Db::commit();
            return $this->buildSuccess('渠道放款入账流水添加成功');
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '渠道放款入账流水添加失败'.$e->getMessage());
        }
    }


    /**
     * @api {post} admin/CashBusiness/showChannelLendDetail 渠道放款入账详情[admin/CashBusiness/showChannelLendDetail]
     * @apiVersion 1.0.0
     * @apiName showChannelLendDetail
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/showChannelLendDetail
     *
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  id   订单资金渠道表id
     * @apiParam {string}  fund_channel_name   放款渠道名称(365,永安)
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
     *       "code": 1,
     *       "msg": "操作成功",
     *        data": {
     *           "orderinfo": {
     *                  "order_sn": "GMDZ2018060007",        订单编号
                        "type": "更名赎楼垫资",               订单类型
                        "name": "刘颖6",                       理财经理
                        "deptname": "运营支持部",               所在部门
                        "finance_sn": "100000333",              财务序号
                        "money": "1000.00",                     垫资金额
                        "actual_account_money": "339.00",       实收金额总计
                        "is_loan_finish": 1,                    渠道放款是否完成 0未完成 1已完成
                        "loan_money_status": 2,               入账状态 1待入账 2待复核 3已复核 4驳回待处理
                        "fund_channel_name": "365",           资金渠道
                        "delivery_status": 2,                 送审状态（-1不需要送审 0待送审 1待渠道审核 2审核通过）
                        "trust_contract_num": null,           信托合同号
                        "push_order_money": null,             推单金额
                        "loan_day": null,                     借款天数
                        "type_text": "GMDZ"                   订单类型(简称)
     *             },
     *         "BankLendInfo": [
         *               {
                        "loan_money": "113.00",              放款金额
                        "lender_object": "365",              放款渠道
                        "receivable_account": "中国银行",    收款账户
                        "into_money_time": "2018-10-11",     到账时间
                        "remark": "测试",                    备注说明
                        "operation_name": "杜欣"             入账人员
                        },
                        {
                        "loan_money": "113.00",
                        "lender_object": "365",
                        "receivable_account": "中国银行",
                        "into_money_time": "2018-10-11",
                        "remark": "测试",
                        "operation_name": "杜欣"
                        },
     *            ]
              "payment": [   收款账户
                        {
                        "bank_card_id": 12,
                        "bank": "广发银行"
                        },
                        {
                        "bank_card_id": 13,
                        "bank": "上海银行"
                        }
                    ]
              "postinginfo": [     过账账户信息
                    {
                    "bankaccount": "张飞",       银行户名
                    "accounttype": 1,
                    "bankcard": "52265615465",      银行卡号
                    "openbank": "农业银行",         开户银行
                    "accounttype_str": "卖方"      账户类型
                    }
                ]
     *        }
     *   }
     *
     *
     */

    public function showChannelLendDetail(){
        $orderSn = input('order_sn');
        $channelId = input('id');
        $fundchannelname = input('fund_channel_name');
        if(empty($orderSn) || empty($channelId) || empty($fundchannelname)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        try{
            $returnInfo = [];
            $orderInfo = OrderFundChannel::channelDetail($orderSn,$channelId);
            $field = 'loan_money,lender_object,receivable_account,into_money_time,remark,create_uid';
            $booksWaterInfo = OrderAccountRecord::getAll(['order_sn' => $orderSn,'lender_object' => $fundchannelname],$field,'create_time desc');
            foreach ($booksWaterInfo as $k => $v){
                $booksWaterInfo[$k]['operation_name'] = SystemUser::where(['id' => $v['create_uid']])->value('name');
                unset($booksWaterInfo[$k]['create_uid']);
            }
            $returnInfo['orderinfo'] = $orderInfo; //订单信息
            $returnInfo['BankLendInfo'] = $booksWaterInfo;//渠道放款流水明细
            //$resInfo = OrderComponents::showGuaranteeBank($orderSn,'bankaccount,accounttype,bankcard,openbank,verify_card_status','3'); // 过账账号信息
            $resInfo = Db::name('order_guarantee_bank')->alias('a')
                ->join('order_guarantee_bank_type b','a.id = b.order_guarantee_bank_id')
                ->where(['a.order_sn' => $orderSn, 'b.type' => 3])
                ->field('bankaccount,accounttype,bankcard,openbank,verify_card_status')
                ->select();
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('JYDB_ACCOUNT_TYPE'));

            if(!empty($resInfo)){
                foreach ($resInfo as $k => $v){
                    $resInfo[$k]['accounttype_str'] = $newStageArr[$v['accounttype']]?$newStageArr[$v['accounttype']]:'';
                }
            }

            $returnInfo['postinginfo'] = $resInfo;
            return $this->buildSuccess($returnInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }

    }


    /**
     * @api {post} admin/CashBusiness/channelReview 渠道放款入账复核[admin/CashBusiness/channelReview]
     * @apiVersion 1.0.0
     * @apiName channelReview
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/channelReview
     *
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  id   订单资金渠道表id
     * @apiParam {int}  type   按钮区分  1 确认复核 2驳回
     *
     *
     */

    public function channelReview(){
        $orderSn = input('order_sn');
        $channelId = input('id');
        $type = input('type');
        if(empty($orderSn) || empty($type) || empty($channelId)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        // 启动事务
        Db::startTrans();
        try{
            //复核之前先判断是否满足复核的条件
            $resInfo = OrderFundChannel::getOne(['id' => $channelId],'is_loan_finish,loan_money_status');
            if($resInfo['is_loan_finish'] != 1) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '只有渠道放款已经完成才能进行该操作!');
            if($type == 1){
                if($resInfo['loan_money_status'] != 2) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '只有渠道放款入账状态为待复核，才能确认复核!');
                //更改该渠道放款入账状态
                $chanInfo = OrderFundChannel::where('id',$channelId)->update(['loan_money_status' => 3,'loan_money_time' => date('Y-m-d',time()),'update_time' => time()]);
                if(empty($chanInfo)){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '更改渠道放款入账状态失败!');
                }
                //判断是否所有的渠道放款入账都复核成功
                $map['order_sn'] = $orderSn;
                $map['fund_channel_id'] = ['<>',1];
                //$map['id'] = ['<>',$channelId];
                $map['status'] = 1;
                $instructStatusArr = OrderFundChannel::where($map)->column('loan_money_status');
                $uniqueStatus = array_unique($instructStatusArr);
                if(count($uniqueStatus) == 1 && $uniqueStatus['0'] == 3){ //说明所有渠道放款入账都复核成功，更改赎楼信息表指令信息
                    $mapChan['fund_channel_id'] = ['<>',1];
                    $mapChan['status'] = 1;
                    $mapChan['order_sn'] = $orderSn;
                    $channelInfo = Db::name('order_fund_channel')->where($mapChan)->field('sum(money) money,sum(actual_account_money) actual_account_money')->find();
                    //更改赎楼信息表相关字段
                    $guaranteeInfo = OrderGuarantee::get(['order_sn' => $orderSn]);
                    /*if($channelInfo['actual_account_money'] < $channelInfo['money']){ //当一个订单的所有渠道实际放款金额总和小于应该渠道放款金额时,公司放款金额需要补上
                        $diffAmount = $channelInfo['money'] - $channelInfo['actual_account_money'];
                        $guaranteeInfo->com_loan_money = $guaranteeInfo['com_loan_money']+$diffAmount;
                    }*/
                    $guaranteeInfo->loan_money = $guaranteeInfo['loan_money'] + $channelInfo['actual_account_money'];
                    $guaranteeInfo->update_time = time();
                    $guaranteeInfo->loan_money_time = date('Y-m-d',time());
                    $guaranteeInfo->is_loan_finish = 1;
                    $guaranteeInfo->loan_money_status = 3;
                    $res = $guaranteeInfo->save();
                    if(empty($res)){
                        // 回滚事务
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '复核更改渠道信息表相关数据失败!');
                    }
                }
                $msg = "复核成功";
            }elseif ($type == 2){
                if($resInfo['loan_money_status'] != 2) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '只有渠道放款入账状态为待复核，才能驳回!');
                //驳回之前还得判断该订单是否已经派完单，排完单就不能驳回了
                if(Db::name('order_ransom_dispatch')->where('order_sn',$orderSn)->value('id')) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '该订单已经派过单，不能进行驳回!');
                //更改主表状态
                OrderGuarantee::where('order_sn',$orderSn)->update(['loan_money_status' => 1,'is_loan_finish' => 0,'update_time' => time()]);
                //更改该渠道放款入账状态
                $chanInfo = OrderFundChannel::where('id',$channelId)->update(['loan_money_status' => 4,'is_loan_finish' => 0,'update_time' => time()]);
                if(empty($chanInfo)){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '驳回失败!');
                }
                $msg = "驳回成功";
            }
            /*添加订单操作记录*/
            //根据订单号查询出订单状态
            $stageInfo = Order::getOne(['order_sn' => $orderSn],'stage');
            if(strlen($stageInfo['stage']) == 4){
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_STATUS');
            }else{
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_FINC_STATUS');
            }
            $operate_node = "渠道放款入账复核";
            $operate_det = $this->userInfo['name'].$msg;
            $operate_reason = '';
            $stage_code = $stageInfo['stage'];
            $operate_table = 'order';
            OrderComponents::addOrderLog($this->userInfo,$orderSn, $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
            // 提交事务
            Db::commit();
            return $this->buildSuccess($msg);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '复核失败!');
        }

    }

    /**
     * @api {post} admin/CashBusiness/finanStateList 财务结单列表[admin/CashBusiness/finanStateList]
     * @apiVersion 1.0.0
     * @apiName finanStateList
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/finanStateList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int}  stage   结单状态 1026 待结单  1021 已结单
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
     *       "total": 2,                            总条数
     *       "per_page": 20,                        每页显示的条数
     *       "current_page": 1,                     当前页
     *       "last_page": 1,                        总页数
     *       "data": [
     *           {
                "order_sn": "GMDZ2018060007",        业务单号
                "finance_sn": "100000333",           财务序号
                "stage": "1021",                     订单状态
                "statement_state": "已结单"           结单状态
                "type": "GMDZ",              订单类型(简称)
                "order_finish_achievement": 8600,   结单业绩
                "hang_achievement": 8600            挂账业绩
                "order_finish_date": null,          结单日期
                "info_fee_date": null,              信息费支付日期
                "return_money_finish_date": null,   回款完成日期
                "remortgage_date": null,            重新抵押日期
                "name": "刘颖6",                     理财经理
                "deptname": "中诚金服",              所属部门
                "estate_name": "名称1阁栋名称1010",    房产名称
                "cname": "张三",                       担保申请人
                "id",                                   订单表id
                "money": "1.00",                        担保金额
                "guarantee_fee": "20000000.02",          预收担保费
                "info_fee": "1.00",                      预收信息费
                "ac_guarantee_fee": null,                实收担保费
                "ac_guarantee_fee_time": null,           收费日期
                "type_text": "更名赎楼垫资"                     订单类型
                "customer_sum": [                        所有的担保申请人
                        "刘恺威",
                        "杨幂"
                    ]
                },
                {
                "id": 1,
                "order_sn": "JYDB2018050371",
                "finance_sn": "100000147",
                "stage": "1021",
                "type": "交易担保",
                "order_finish_achievement": null,
                "order_finish_date": null,
                "info_fee_date": null,
                "return_money_finish_date": null,
                "remortgage_date": null,
                "name": "管理员",
                "deptname": "中诚金服",
                "estate_name": "名称1阁栋名称1010",
                "cname": "张三",
                "money": "1.00",
                "guarantee_fee": "20000000.02",
                "info_fee": "1.00",
                "ac_guarantee_fee": null,
                "ac_guarantee_fee_time": null,
                "type_text": "JYDB",
                "hang_achievement": -1
                }
     *       ]
     *   }
     */

    public function finanStateList(){
        $createUid = input('create_uid')?:0;
        $subordinates = input('subordinates')?:0;
        $type = input('type');
        $startTime = input('start_time');
        $endTime = input('end_time');
        $stage = input('stage')?:0;
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
        $userId = $this->userInfo['id'];
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['x.financing_manager_id'] = ['in', $userStr];
        }
        if($startTime && $endTime){
            if($startTime > $endTime){
                $startTime = date('Y-m-d',strtotime($startTime) + 86399);
                $map['x.order_finish_date'] = array(array('egt', $endTime), array('elt', $startTime));
            }else{
                $endTime = date('Y-m-d',strtotime($endTime) + 86399);
                $map['x.order_finish_date'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        }elseif($startTime){
            $map['x.order_finish_date'] = ['egt',$startTime];
        }elseif($endTime){
            $endTime = date('Y-m-d',strtotime($endTime) + 86399);
            $map['x.order_finish_date'] = ['elt',$endTime];
        }
        $type && $map['x.type'] = $type;
        $stage?$map['x.stage'] = $stage:$map['x.stage'] = ['in','1021,1026'];
        $searchText && $map['y.estate_name|x.order_sn']=['like', "%{$searchText}%"];
        $map['x.delete_time'] = NULL;
        $map['x.status'] = 1;
        $map['c.is_guarantee'] = 1;
        try{
            return $this->buildSuccess(Order::finanStateList($map,$page,$pageSize));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /**
     * @api {post} admin/CashBusiness/getGuarantee 获取所有担保申请人[admin/CashBusiness/getGuarantee]
     * @apiVersion 1.0.0
     * @apiName getGuarantee
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/getGuarantee
     *
     * @apiParam {string}  order_sn   订单编号
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *   {
            "code": 1,
            "msg": "操作成功",
            "data": [
                {
                "cname": "测试第二次"     担保申请人名称
                },
                {
                "cname": "测试第一次"
                }
            ]
       }
     */

    public function getGuarantee(){
        $orderSn = input('order_sn');
        if(empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
        try{
            $resInfo = Customer::getAll(['order_sn' => $orderSn,'status' => 1,'delete_time' => NULL,'is_guarantee' => 1],'cname');
            return $this->buildSuccess($resInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /**
     * @api {post} admin/CashBusiness/submitOrder 提交结单[admin/CashBusiness/submitOrder]
     * @apiVersion 1.0.0
     * @apiName submitOrder
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/submitOrder
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  button_type   1确认结单  2撤回结单
     *
     */

    public function submitOrder(){
        $orderSn = input('order_sn');
        $button_type = input('button_type');
        if(empty($orderSn) || empty($button_type)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        try{
             $orderInfo = Order::getOne(['order_sn' => $orderSn],'type,stage,is_return_money_finish');
             if($button_type == 1){
                if($orderInfo['stage'] != 1026) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '只有待结单状态的订单才能确认结单!');
                 $orderInfo['type'] == 'JYDB'?$content = "短贷没有还清":$content = "回款没有完成";
                 if(empty($orderInfo['is_return_money_finish'])) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, $content.'，确认结单失败!');
                 //该订单表添加结单业绩,修改订单状态
                 $guaranteeInfo = OrderGuarantee::getOne(['order_sn' => $orderSn],'ac_guarantee_fee,ac_exhibition_fee,ac_overdue_money,ac_transfer_fee,info_fee');
                 $dataInfo['hang_achievement'] = $dataInfo['order_finish_achievement'] = $guaranteeInfo['ac_guarantee_fee']+$guaranteeInfo['ac_exhibition_fee']+$guaranteeInfo['ac_overdue_money']+$guaranteeInfo['ac_transfer_fee']-$guaranteeInfo['info_fee'];
                 $dataInfo['stage'] = 1021;
                 $dataInfo['order_finish_date'] = date('Y-m-d',time());
                 $msgs = "确认结单";
             }elseif ($button_type == 2){
                 if($orderInfo['stage'] != 1021) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '只有已结单状态的订单才能撤回结单!');
                 $dataInfo['hang_achievement'] = $dataInfo['order_finish_achievement'] = '';
                 $dataInfo['stage'] = 1026;
                 $dataInfo['order_finish_date'] = NULL;
                 $msgs = "撤回结单";
             }else{
                 return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '参数有误!');
             }
            $dataInfo['update_time'] = time();
            $res = Db::name('order')->where('order_sn',$orderSn)->update($dataInfo);
            $res?$msg = $msgs."成功":$msg = $msgs."失败";

            /*添加订单操作记录*/
            //根据订单号查询出订单状态
            $stageInfo = Order::getOne(['order_sn' => $orderSn],'stage');
            if(strlen($stageInfo['stage']) == 4){
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_STATUS');
            }else{
                $operate = $stage = show_status_name($stageInfo['stage'],'ORDER_JYDB_FINC_STATUS');
            }
            $operate_node = "财务结单";
            $operate_det = $this->userInfo['name'].$msg;
            $operate_reason = '';
            $stage_code = $stageInfo['stage'];
            $operate_table = 'order';
            OrderComponents::addOrderLog($this->userInfo,$orderSn, $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
            if(isset($res)){
                return $this->buildSuccess($msg);
            }else{
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, $msg);
            }
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '结单失败!');
        }
    }

    /**
     * @api {get} admin/CashBusiness/exportHasList 渠道放款已入账导出[admin/CashBusiness/exportHasList]
     * @apiVersion 1.0.0
     * @apiName exportHasList
     * @apiGroup CashBusiness
     * @apiSampleRequest admin/CashBusiness/exportHasList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  start_time   开始时间
     * @apiParam {int}  end_time   结束时间
     * @apiParam {int} search_text    关键字搜索
     */

    public function exportHasList(){
        $createUid = input('create_uid')?:0;
        $subordinates = input('subordinates')?:0;
        $type = input('type');
        $startTime = input('start_time');
        $endTime = input('end_time');
        $searchText = trim(input('search_text'));
        $map = [];
        //用户判断//
        if ($createUid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($createUid);
            } else {
                $userStr = $createUid;
            }
            $map['n.financing_manager_id'] = ['in', $userStr];
        }
        if($startTime && $endTime){
            if($startTime > $endTime){
                $startTime = date('Y-m-d',strtotime($startTime)+86399);
                $map['x.loan_money_time'] = array(array('egt', $endTime), array('elt', $startTime));
            }else{
                $endTime = date('Y-m-d',strtotime($endTime)+86399);
                $map['x.loan_money_time'] = array(array('egt', $startTime), array('elt', $endTime));
            }
        }elseif($startTime){
            $map['x.loan_money_time'] = ['egt',$startTime];
        }elseif($endTime){
            $endTime = date('Y-m-d',strtotime($endTime)+86399);
            $map['x.loan_money_time'] = ['elt',$endTime];
        }
        $type && $map['n.type'] = $type;
        $searchText && $map['y.estate_name|n.order_sn|n.finance_sn']=['like', "%{$searchText}%"];
        //if(empty($map)) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '请选择导出的条件!');

        $map['n.delete_time'] = NULL;
        $map['n.status'] = 1;
        $map['x.instruct_status'] = 4;
        $map['x.loan_money_status'] = 3;
        try{
            $spreadsheet = new Spreadsheet();
            $resInfo = OrderFundChannel::exportHasList($map);
            $head = ['0' => '序号','1' => '财编','2' => '业务单号', '3' => '业务类型','4' => '房产名称' ,
                '5' => '业主姓名', '6' => '买方姓名','7' => '预计用款日','8' => '担保金额/元',
                '9' => '资金渠道','10' => '信托合同编号' , '11' => '合同天数/天',
                '12' => '推单金额/元','13' => '渠道放款入账金额', '14' => '入账时间'];
            array_unshift($resInfo,$head);
            $fileName = date('Y-m-dHis');
            //$fileName = iconv("UTF-8", "GB2312//IGNORE", '渠道放款已入账' . date('Y-m-dHis'));
            $spreadsheet->getActiveSheet()->fromArray($resInfo);
            $spreadsheet->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true)->setName('Arial')->setSize(12);
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
            $worksheet = $spreadsheet->getActiveSheet();
            $styleArray = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $worksheet->getStyle('A1:O1')->applyFromArray($styleArray);
            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $Path = ROOT_PATH . 'public' . DS . 'uploads'.DS.'download'.DS.date('Ymd');
            if(!file_exists($Path))
            {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($Path, 0700);
            }
            $pathName = $Path.DS .$fileName.'.Xlsx';
            $objWriter->save($pathName);
            $retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS . iconv("GB2312", "UTF-8", $fileName) . '.Xlsx';
            return $this->buildSuccess(['url' => $retuurl]);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '导出失败!'.$e->getMessage());
        }
    }





}