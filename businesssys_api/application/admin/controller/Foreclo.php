<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/21
 * Time: 10:14
 * 资料入架财务审核
 */

namespace app\admin\controller;

use app\util\ReturnCode;
use app\model\Order;
use app\model\SystemUser;
use app\model\OrderRansomDispatch;
use app\model\Dictionary;
use app\util\OrderComponents;
use think\Db;
use Workflow\Workflow;
use app\model\OrderLog;
use app\model\WorkflowFlow;
use app\model\Message;

class Foreclo extends Base
{
    private $dictionary;
    private $orderransomdispatch;
    private $order;
    private $systemuser;

    public function _initialize()
    {
        parent::_initialize();
        $this->orderransomdispatch = new OrderRansomDispatch();
        $this->dictionary = new Dictionary();
        $this->order = new Order();
        $this->systemuser = new SystemUser();
    }

    /**
     * @api {post} admin/Foreclo/dataList 资料入架待入架列表[admin/Foreclo/dataList ]
     * @apiVersion 1.0.0
     * @apiName dataList
     * @apiGroup Foreclo
     * @apiSampleRequest admin/Foreclo/dataList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  is_combined_loan   是否组合贷款（0否 1是）
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
     * "code": 1,
     * "msg": "操作成功",
     * "data": {
     * "total": 19,             总条数
     * "per_page": "2",         每页显示的条数
     * "current_page": 1,       当前页
     * "last_page": 10,         总页数
     * "data": [
     * {
     * "order_sn": "JYDB2018050137123456",    业务单号
     * "type": "JYDB",                        订单类型
     * "create_time": "2018-05-09 17:04:06",  报单时间
     * "name": "夏丽平",                        理财经理
     * "estate_name": "国际新城一栋",           房产名称
     * "estate_owner": "张三,李四",             业主姓名
     * "is_combined_loan": 1,                   是否组合贷 1是 0否
     * "order_status": "待注销过户",             订单状态
     * "estate_ecity_name": "深圳市",            城市
     * "estate_district_name": "罗湖区",         城区
     * "proc_id"                                 处理明细表主键id
     * "organization": [                        赎楼银行
     * {
     * "organization": "银行"
     * },
     * {
     * "organization": "银行"
     * },
     * {
     * "organization": "银行"
     * }
     * ]
     * },
     * {
     * "order_sn": "JYDB2018050159",
     * "type": "JYDB",
     * "create_time": "2018-05-12 10:15:45",
     * "name": "夏丽平",
     * "estate_name": "国际新城一栋",
     * "estate_owner": "张三,李四",
     * "is_combined_loan": null,
     * "order_status": "待指派赎楼员",
     * "estate_ecity_name": "深圳市",
     * "estate_district_name": "罗湖区",
     * "organization": []
     * }
     * ]
     * }
     * }
     */

    public function dataList()
    {
        $res = $this->dataWhere(1);
        try {
            return $this->buildSuccess(Order::dataList($res['map'],$res['page'],$res['pageSize']));
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /**
     * @api {post} admin/Foreclo/hasBeenList 资料入架已入架列表[admin/Foreclo/hasBeenList ]
     * @apiVersion 1.0.0
     * @apiName hasBeenList
     * @apiGroup Foreclo
     * @apiSampleRequest admin/Foreclo/hasBeenList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type    订单类型
     * @apiParam {int}  is_combined_loan   是否组合贷款（0否 1是）
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
     * "code": 1,
     * "msg": "操作成功",
     * "data": {
     * "total": 19,             总条数
     * "per_page": "2",         每页显示的条数
     * "current_page": 1,       当前页
     * "last_page": 10,         总页数
     * "data": [
     * {
     * "order_sn": "JYDB2018050137123456",    业务单号
     * "type": "JYDB",                        订单类型
     * "create_time": "2018-05-09 17:04:06",  报单时间
     * "name": "夏丽平",                        理财经理
     * "estate_name": "国际新城一栋",           房产名称
     * "estate_owner": "张三,李四",             业主姓名
     * "is_combined_loan": 1,                   是否组合贷 1是 0否
     * "order_status": "待注销过户",             订单状态
     * "estate_ecity_name": "深圳市",            城市
     * "estate_district_name": "罗湖区",         城区
     * "proc_id"                                 处理明细表主键id
     * "organization": [                        赎楼银行
     * {
     * "organization": "银行"
     * },
     * {
     * "organization": "银行"
     * },
     * {
     * "organization": "银行"
     * }
     * ]
     * },
     * {
     * "order_sn": "JYDB2018050159",
     * "type": "JYDB",
     * "create_time": "2018-05-12 10:15:45",
     * "name": "夏丽平",
     * "estate_name": "国际新城一栋",
     * "estate_owner": "张三,李四",
     * "is_combined_loan": null,
     * "order_status": "待指派赎楼员",
     * "estate_ecity_name": "深圳市",
     * "estate_district_name": "罗湖区",
     * "organization": []
     * }
     * ]
     * }
     * }
     */

    public function hasBeenList()
    {
        $res = $this->dataWhere(2);
        try {
            return $this->buildSuccess(Order::dataList($res['map'],$res['page'],$res['pageSize']));
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /*
     * @author 赵光帅
     * 组装审批列表的条件
     * @Param {int}  $typeList   1 待入架列表条件  2 已入架列表条件
     * */
    protected function dataWhere($typeList){
        $createUid = input('create_uid') ?: 0;
        $subordinates = input('subordinates') ?: 0;
        $type = input('type');
        $is_combined_loan = input('is_combined_loan') ?: 0;
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
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
            $map['x.financing_manager_id'] = ['in', $userStr];
        }
        $type && $map['x.type'] = $type;
        $is_combined_loan && $map['n.is_combined_loan'] = $is_combined_loan;
        $searchText && $map['y.estate_name|x.order_sn|y.estate_owner'] = ['like', "%{$searchText}%"];
        $map['x.delete_time'] = NULL;
        $map['x.status'] = 1;
        //$map['w.status'] = 0;
        $map['w.is_deleted'] = 1;
        $map['w.is_back'] = 0;
        $map['wf.flow_type']= 'risk';
        if($typeList === 1){   //待入架列表
            $map['w.user_id'] = $userId;
            $map['w.status'] = 0;
        }else{                //已入架列表
            $userIdArr = $this->getUserArray();
            $map['w.user_id'] = ['in', $userIdArr];
            $map['w.status'] = 9;
        }
        return ['map' => $map, 'page' => $page, 'pageSize' => $pageSize];
    }

    /*
     * 查看该用户可以查询那些资料已入架的数据
     * @return array
     * */

    private function getUserArray(){
        $userId = $this->userInfo['id'];
        $userStr = SystemUser::getOrderPowerStr($userId, $this->userInfo['ranking'], $this->userInfo['deptid']);
        if(in_array('foreclosure_director', get_user_sing($userId)) || in_array('foreclosure_manager', get_user_sing($userId)) || $userStr == 'super'){ //赎楼主管 赎楼经理 超管
            //查询出所有的资料入架员
            $ziliaoId = Db::name('system_auth_group')->where(['sign' => 'data_entry_staff', 'status' => 1])->value('id');
            $uidStr = Db::name('system_auth_group_access')->where("instr(`groupid`,'{$ziliaoId}')")->value("GROUP_CONCAT(uid)");
            return $uidStr;
        }else{
            return $userId;
        }

    }

    /**
     * @api {post} admin/Foreclo/finauditList 财务待审核列表[admin/Foreclo/finauditList ]
     * @apiVersion 1.0.0
     * @apiName finauditList
     * @apiGroup Foreclo
     * @apiSampleRequest admin/Foreclo/finauditList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {string} type            订单类型
     * @apiParam {int} ransom_type    赎楼类型 1公积金贷款 2商业贷款 3装修贷/消费贷
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} ransom_status    当前状态
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
     * "code": 1,
     * "msg": "操作成功",
     * "data": {
     * "total": 19,             总条数
     * "per_page": "2",         每页显示的条数
     * "current_page": 1,       当前页
     * "last_page": 10,         总页数
     * "data": [
     * {
     * "id": 39,                        赎楼派单表主键id
     * "order_sn": "JYDB2018050285",    业务单号
     * "ransom_bank": "农业银行",        赎楼银行
     * "ransom_status": 202,
     * "ransom_type": 1,
     * "ransomer": "李四",               赎楼员
     * "create_time": "2018-05-22",      派单日期
     * "type": "FJYDB",                  订单类型
     * "finance_sn": "100000104",        财务序号
     * "financing_manager_id": 17,
     * "estate_name": "名称1阁栋名称1010",      房产名称
     * "estate_owner": "张三,测试第二次",       业主姓名
     * "ransom_status_text": "待赎楼经理审批",   当前状态
     * "ransom_type_text": "公积金贷款",         赎楼类型
     * "type_text": "非交易担保",                订单类型
     * "financing_manager": "杨亚丽"             理财经理
     * },
     * {
     * "id": 38,
     * "order_sn": "JYDB2018050285",
     * "ransom_bank": "中国银行",
     * "ransom_status": 14,
     * "ransom_type": 1,
     * "ransomer": "张三",
     * "create_time": "2018-05-22",
     * "type": "FJYDB",
     * "finance_sn": "100000104",
     * "financing_manager_id": 17,
     * "estate_name": "名称1阁栋名称1010",
     * "estate_owner": "张三,测试第二次",
     * "ransom_status_text": "待赎楼经理审批",
     * "ransom_type_text": "公积金贷款",
     * "type_text": "非交易担保",
     * "financing_manager": "杨亚丽"
     * "proc_id": 192
     * }
     * ]
     * }
     * }
     */

    public function finauditList()
    {
        $res = $this->finauditWhere(1);
        return $this->buildSuccess($res);
    }

    /**
     * @api {post} admin/Foreclo/haveOnList 财务已审核列表[admin/Foreclo/haveOnList ]
     * @apiVersion 1.0.0
     * @apiName haveOnList
     * @apiGroup Foreclo
     * @apiSampleRequest admin/Foreclo/haveOnList
     *
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {string} type            订单类型
     * @apiParam {int} ransom_type    赎楼类型 1公积金贷款 2商业贷款 3装修贷/消费贷
     * @apiParam {int} search_text    关键字搜索
     * @apiParam {int} ransom_status    当前状态
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     */

    public function haveOnList()
    {
        $res = $this->finauditWhere(2);
        return $this->buildSuccess($res);
    }


    /*
     * @author 赵光帅
     * 组装审批列表的条件
     * @Param {int}  $typeList   1 待审批列表  2 已审批列表 3 所有审批
     * */
    protected function finauditWhere($typeList){
        $createUid = input('create_uid') ?: 0;
        $subordinates = input('subordinates') ?: 0;
        $type = input('type');
        $ransom_type = input('ransom_type');
        $ransom_status = input('ransom_status');
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : 10;
        $userId = $this->userInfo['id'];
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
        $ransom_type && $map['x.ransom_type'] = $ransom_type;
        $type && $map['o.type'] = $type;
        $ransom_status && $map['x.ransom_status'] = $ransom_status;
        $searchText && $map['e.estate_name|o.order_sn|o.finance_sn|e.estate_owner'] = ['like', "%{$searchText}%"];
        $field = "x.id,d.id proc_id,x.order_sn,x.ransom_bank,x.ransom_status,x.ransom_type,x.ransomer,x.create_time,o.type,o.finance_sn,o.financing_manager_id,e.estate_name,e.estate_owner";
        if($typeList === 1){
            $order = 'd.id asc';
            $map['d.status'] = 0;
        }else{
            $order = 'd.finish_time desc';
            $map['d.status'] = 9;
        }

        $map['d.is_deleted'] = 1;
        $map['d.user_id'] = $userId;
        $map['d.is_back'] = 0;
        $map['x.ransom_status'] = ['<>', 201];
        $map['wf.flow_type'] = 'finance';
        $map['o.status'] = 1;
        $creditList = Db::name('workflow_proc')->alias('d')
            ->join('workflow_entry we', 'we.id = d.entry_id')
            ->join('workflow_flow wf', 'wf.id = d.flow_id')
            ->join('order_ransom_dispatch x', 'x.id = we.mid')
            ->join('estate e', 'e.order_sn = d.order_sn', 'left')
            ->join('order o', 'o.order_sn = d.order_sn')
            ->where($map)
            ->order($order)
            ->group('x.id')
            ->field($field)
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))->toArray();
            foreach ($creditList['data'] as $key => $value) {
                $creditList['data'][$key]['ransom_status_text'] = $this->dictionary->getValnameByCode('ORDER_JYDB_FINC_STATUS', $value['ransom_status']); //赎楼状态
                $creditList['data'][$key]['ransom_type_text'] = $this->orderransomdispatch->getRansomtype($value['ransom_type']); //赎楼类型
                $creditList['data'][$key]['type_text'] = $this->order->getType($value['type']); //订单类型
                $creditList['data'][$key]['create_time'] = date('Y-m-d', $value['create_time']); //派单时间
                $creditList['data'][$key]['financing_manager'] = $this->systemuser->where('id', $value['financing_manager_id'])->value('name'); //理财经理
            }

        return $creditList;
    }

    /**
     * @api {post} admin/Foreclo/caiwuInfo 财务审核详情页[admin/Foreclo/caiwuInfo]
     * @apiVersion 1.0.0
     * @apiName caiwuInfo
     * @apiGroup Foreclo
     * @apiSampleRequest admin/Foreclo/caiwuInfo
     *
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  id   赎楼派单表主键id
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
    "is_guarantee_str": 0                 担保申请人 是   否
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
    }
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

    public function caiwuInfo()
    {
        $orderSn = input('order_sn');
        $id = input('id');
        if (empty($orderSn) || empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        try {
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
            $returnInfo['fund_channel'] = OrderComponents::fundChannel($orderSn, 'fund_channel_name,money,actual_account_money,is_loan_finish,trust_contract_num,loan_day');

            //出账申请记录
            $returnInfo['debitinfolog'] = OrderComponents::showDebitInfolog($orderSn);
            //赎楼回执
            $returnInfo['receipt_img'] = OrderComponents::showReceiptimg($id);

            //赎楼状态
            $returnInfo['redeem_info'] = OrderComponents::showRedeem($id);
            //查询出各种状态
            $returnInfo['status_info'] = OrderComponents::showStstusInfo($orderSn);
            return $this->buildSuccess($returnInfo);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }

    /**
     * @param $type 订单类型   ['JYDB','FINANCIAL']
     * @param $order_sn 订单编号
     * @param $dispatch_id  派单表id
     */
    public function getDispatchProcId($type, $order_sn, $dispatch_id,$is_approval)
    {
        $where = [
            'wf.type' => $type,
            'wf.status' => 1,
            'wf.is_publish' => 1,
            'we.order_sn' => $order_sn,
            'we.mid' => $dispatch_id
        ];
        $info = Db::name('WorkflowFlow')->alias('wf')
            ->join('__WORKFLOW_ENTRY__ we', 'we.flow_id = wf.id')
            ->where($where)->field('we.id as entry_id,we.flow_id')
            ->find();
        !$info && $this->buildFailed(ReturnCode::DB_READ_ERROR, '数据异常');
        if($is_approval==1){
            $info['user_id'] = $this->userInfo['id'];
        }
        $proc = Db::name('WorkflowProc')->where($info)->where([
            'status' => 0,
            'is_back' => 0,
            'is_deleted' => 1
        ])->field('id,entry_id,flow_id')->find();
        !$proc && $this->buildFailed(ReturnCode::DB_READ_ERROR, '数据异常');
        return $proc;
    }

    /**
     * @api {post} admin/Foreclo/submitFinancial 财务审核提交审批[admin/Foreclo/submitFinancial]
     * @apiVersion 1.0.0
     * @apiName submitFinancial
     * @apiGroup Foreclo
     * @apiSampleRequest admin/Foreclo/submitFinancial
     *
     * @apiParam {string}  order_sn   订单编号
     * @apiParam {int}  is_approval   审批结果 1通过 2驳回
     * @apiParam {string}  content   驳回原因
     * @apiParam {int}  dispatch_id      赎楼派单表主键id
     * @apiParam {int}  ransom_status      子订单状态对应的code
     */

    public function submitFinancial()
    {
        $orderSn = input('order_sn');
        $is_approval = input('is_approval');
        $content = input('content');
        $dispatch_id = input('dispatch_id');
        $ransom_status = input('ransom_status');
        //验证器验证参数
        $valiDate = validate('SubmitFinc');
        $data = ['order_sn' => $orderSn, 'is_approval' => $is_approval, 'dispatch_id' => $dispatch_id, 'ransom_status' => $ransom_status];
        if (!$valiDate->check($data)) {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
        }
        //根据订单号用户ID 后端获取 流程步骤表主键id
        $type = Db::name('order')->where(['order_sn' => $orderSn])->value('type');
        $resInfo = $this->getDispatchProcId($type . '_FINANCIAL', $orderSn, $dispatch_id,$is_approval);
        //获取flow_id
        if ($is_approval == 2) {  //驳回获取驳回节点
            $flow_id = $resInfo['flow_id'];
            $sbacks_proc_id = self::getBackProcId($ransom_status, $orderSn, $flow_id, $resInfo['entry_id']);
        } else {  //通过为空
            $sbacks_proc_id = '';
        }
        $config = [
            'user_id' => $this->userInfo['id'], // 用户id
            'user_name' => $this->userInfo['name'], // 用户姓名
            'proc_id' => $resInfo['id'],  // 流程步骤表主键id
            'content' => $content,  // 审批意见
            'back_proc_id' => $sbacks_proc_id,  // 退回节点id
            'order_sn' => $orderSn
        ];
        $operate = show_status_name($ransom_status, 'ORDER_JYDB_FINC_STATUS');//当前订单的审批节点名称
        $workflow = new Workflow($config);
        // 启动事务
        Db::startTrans();
        try {
            if ($is_approval == 1) {
                // 审批通过
                $workflow->pass();
                $logInfo = self::getOrderLogInfo($is_approval, $dispatch_id);
                $operate_reason = '';
            } else {
                //驳回原因不能为空
                if (empty($content)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '驳回原因不能为空!');
                // 审批拒绝
                $workflow->unpass();
                if ($ransom_status == 202) { //待赎楼经理审批驳回
                    //更改该订单为退回派单
                    Db::name('order_ransom_dispatch')->where(['id' => $dispatch_id])->update(['is_dispatch' => 2, 'update_time' => time(),'ransome_id' => '','ransomer' => '']);
                }
                $logInfo = self::getOrderLogInfo($is_approval, $dispatch_id);
                $operate_reason = $content;
            }
            /*添加订单操作记录*/
            if($ransom_status == 202){
                $operate_node = "赎楼审核";
            }else{
                $operate_node = "财务审核";
            }

            $operate_det = $logInfo['msg'];
            $operate_table = 'order_ransom_dispatch';
            $operate_table_id = $dispatch_id;
            OrderComponents::addOrderLog($this->userInfo, $orderSn, '待赎楼员完成赎楼', $operate_node, $operate, $operate_det, $operate_reason, 1014, $operate_table, $operate_table_id);
            // 提交事务
            Db::commit();
            return $this->buildSuccess('审批成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return $this->buildFailed(ReturnCode::ADD_FAILED, $e->getMessage());
        }

    }

    private function getOrderLogInfo($is_approval, $dispatch_id)
    {
        //根据派单表id查询出更改后的订单状态
        $stageInfo = OrderRansomDispatch::getOne(['id' => $dispatch_id], 'ransom_status,order_sn,ransome_id,id');
        $back_proc_id = $stageInfo['ransom_status']; //审批通过流向的下一个节点id
        $stage = show_status_name($back_proc_id, 'ORDER_JYDB_FINC_STATUS');  //流向的审批节点名称
        if ($is_approval == 1) {
            //审核完成，需要存一条发送短信的记录
            if($back_proc_id == 206 || $back_proc_id == 208){
                $messObj = new Message();
                $messObj->AddmessageRecord($stageInfo['ransome_id'], 2, 2, $stageInfo['id'], $stageInfo['order_sn'], 202, '财务审批', '订单号'.$stageInfo['order_sn'].'已通过财务审批流程，请跟进赎楼', 1, 1, 0, 0, '', 'PC财务审批通过', 'order_ransom_dispatch');
            }

            $msg = $this->userInfo['name'] . ":审批通过";
        } else {
            $msg = $this->userInfo['name'] . ":审批驳回";
        }
        return ['back_proc_id' => $back_proc_id, 'stage' => $stage, 'msg' => $msg];
    }

    private function getBackProcId($order_status, $order_sn, $flow_id, $entry_id)
    {
        if (in_array($order_status, ['203', '204', '205', '206', '207'])) {
//            退回赎楼经理 202
            $where['wp.wf_status'] = '202';
        }
        if (in_array($order_status, ['202'])) {
//            退回待派赎楼员 201
            $where['wp.wf_status'] = '201';
        }
        $where['wc.is_deleted'] = 1;
        $where['wc.is_back'] = 0;
        $where['wc.order_sn'] = $order_sn;
        $where['wc.flow_id'] = $flow_id;
        $where['wc.entry_id'] = $entry_id;
        $res = Db::name('workflow_process')->alias('wp')
            ->join('__WORKFLOW_PROC__ wc', 'wc.process_id=wp.id')
            ->where($where)->value('wc.id');
        return $res;
    }

    /**
     * @api {post} admin/Foreclo/foreProcList 财务赎楼流程列表[admin/Foreclo/foreProcList ]
     * @apiVersion 1.0.0
     * @apiName foreProcList
     * @apiGroup Foreclo
     * @apiSampleRequest admin/Foreclo/foreProcList
     *
     * @apiParam {int}  dispatch_id      赎楼派单表主键id
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
     * "code": 1,
     * "msg": "操作成功",
     * "data": {
     * "total": 19,             总条数
     * "per_page": "2",         每页显示的条数
     * "current_page": 1,       当前页
     * "last_page": 10,         总页数
     * "data": [
     * {
     * "create_time": "2018-05-25 15:55:31",    时间
     * "operate": "待业务报单",                  操作
     * "operate_node": "待业务报单",             操作节点
     * "operate_det": "创建订单",               操作详情
     * "name": "管理员"                         操作人员
     * },
     * {
     * "create_time": "2018-05-25 11:56:07",
     * "operate": "风控审批流",
     * "operate_node": "风控部门提交审批",
     * "operate_det": "刘林4:审批通过,流向=>待审查助理审批",
     * "name": "刘林4"
     * }
     * ]
     * }
     * }
     */
    public function foreProcList()
    {
        $dispatch_id = input('dispatch_id');
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') :15;
        if (empty($dispatch_id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '赎楼派单表主键id不能为空!');
        $map['x.operate_table_id'] = $dispatch_id;
        $map['x.operate_table'] = "order_ransom_dispatch";
        try {
            return $this->buildSuccess(OrderLog::fincList($map, $page, $pageSize));
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!' . $e->getMessage());
        }
    }


}
