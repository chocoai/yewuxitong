<?php

/* 赎楼控制器 */

namespace app\admin\controller;

use think\Db;
use app\model\OrderRansomOut;
use app\model\OrderRansomDispatch;
use app\model\Order;
use app\model\SystemUser;
use app\model\Dictionary;
use app\model\Cheque;
use app\model\Ransomer;
use app\model\OrderGuaranteeBank;
use app\model\Attachment;
use app\model\BankCard;
use app\util\OrderComponents;
use app\util\ReturnCode;
use Workflow\Workflow;
use app\model\WorkflowFlow;
use app\model\Message;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Foreclosure extends Base {

    private $orderransomout;
    private $attachment;
    private $bankcard;
    private $cheque;
    private $dictionary;
    private $ransomer;
    private $orderransomdispatch;
    private $order;
    private $systemuser;
    private $orderguaranteebank;
    private $message;

    public function _initialize() {
        parent::_initialize();
        $this->orderransomout = new OrderRansomOut();
        $this->attachment = new Attachment();
        $this->orderransomdispatch = new OrderRansomDispatch();
        $this->dictionary = new Dictionary();
        $this->cheque = new Cheque();
        $this->ransomer = new Ransomer();
        $this->order = new Order();
        $this->bankcard = new BankCard();
        $this->systemuser = new SystemUser();
        $this->orderguaranteebank = new OrderGuaranteeBank();
        $this->message = new Message();
    }

    /**
     * @api {get} admin/Foreclosure/ransomList 赎楼列表[admin/Foreclosure/ransomList]
     * @apiVersion 1.0.0
     * @apiName ransomList
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/ransomList
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type     订单类型
     * @apiParam {int} ransom_status     赎楼状态
     * @apiParam {int} ransom_type     赎楼类型（1商业贷款 2公积金贷款 3家装/消费贷）
     * @apiParam {int} keywords     关键词
     * @apiParam {int} page    页码
     * @apiParam {int} size    条数
     *
     * @apiSuccess {string} finance_sn    财务序号
     * @apiSuccess {string} order_sn    业务单号
     * @apiSuccess {string} estate_name    房产名称
     * @apiSuccess {string} estate_owner    业主姓名
     * @apiSuccess {string} type_text    订单类型
     * @apiSuccess {string} ransom_status_text    赎楼状态
     * @apiSuccess {string} ransom_type_text    赎楼类型
     * @apiSuccess {string} ransomer    赎楼员
     * @apiSuccess {string} ransom_bank    赎楼银行
     * @apiSuccess {string} create_time    派单时间
     * @apiSuccess {string} financing_manager    理财经理
     * @apiSuccess {int} count    总条数
     */
    public function ransomList() {
        $limit = $this->request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $page = $this->request->get('page', 1);
        $pageSize = $limit ? $limit : config('paginate')['list_rows'];

        $create_uid = $this->request->get('create_uid', '');
        $subordinates = $this->request->get('subordinates', 0);
        $type = $this->request->get('type', '');
        $ransom_status = $this->request->get('ransom_status', '');
        $ransom_type = $this->request->get('ransom_type', '');
        $keywords = $this->request->get('keywords', '', 'trim');
        //查询条件组装
        $where = [];
        if ($create_uid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($create_uid);
            } else {
                $userStr = $create_uid;
            }
            $where['o.financing_manager_id'] = ['in', $userStr];
        }
        $uid = $this->userInfo['id'];
        if (empty($uid))
            return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
        //数据控制  赎楼主管|派单员额度|派单员现金 以及经理以上职级的人能看到所有跟进派单  其他赎楼员只能看到自己的单 2018.9.4
        $group = $this->userInfo['group'];
        $auth_group = $this->auth_group;
        if (!check_auth($auth_group['foreclosure_director'], $group) && !check_auth($auth_group['fund_dispatch_staff'], $group) && !check_auth($auth_group['quota_dispatch_staff'], $group)) {
            $userStr = SystemUser::getOrderPowerStr($uid);
            if ($userStr != 'super') {
                $where['x.ransome_id'] = ['in', $userStr];
            }
        }
        $type && $where['o.type'] = $type;
        $ransom_status && $where['x.ransom_status'] = $ransom_status;
        $ransom_type && $where['x.ransom_type'] = $ransom_type;
        $keywords && $where['x.order_sn|e.estate_owner|e.estate_name'] = ['like', "%{$keywords}%"];
        $where['x.is_dispatch'] = array('neq', 2);
        $field = "x.id,x.order_sn,x.is_dispatch,x.ransom_bank,x.ransom_status,x.ransom_type,x.ransomer,x.create_time,o.type,o.finance_sn,o.financing_manager_id,e.estate_name,e.estate_owner";
        $creditList = $this->orderransomdispatch->alias('x')
                        ->join('__ORDER__ o', 'o.order_sn=x.order_sn')
                        ->join('__ESTATE__ e', 'e.order_sn=x.order_sn', 'left')
                        ->where($where)->field($field)
                        ->order('x.create_time', 'DESC')
                        ->group('x.id')
                        ->paginate(array('list_rows' => $pageSize, 'page' => $page))->toArray();
        if (!empty($creditList['data'])) {
            foreach ($creditList['data'] as &$value) {
                $value['ransom_status_text'] = $this->dictionary->getValnameByCode('ORDER_JYDB_FINC_STATUS', $value['ransom_status']); //赎楼状态
                $value['ransom_type_text'] = $this->orderransomdispatch->getRansomtype($value['ransom_type']); //赎楼类型
                $value['type_text'] = $this->order->getType($value['type']); //订单类型
                $value['create_time'] = date('Y-m-d', strtotime($value['create_time'])); //派单时间
                $value['financing_manager'] = $this->systemuser->where('id', $value['financing_manager_id'])->value('name'); //理财经理
            }
        }
        return $this->buildSuccess(['list' => $creditList['data'], 'count' => $creditList['total']]);
    }

    /**
     * @api {get} admin/Foreclosure/ransomOutaccountExport 赎楼出账列表导出[admin/Foreclosure/ransomOutaccountExport]
     * @apiVersion 1.0.0
     * @apiName ransomOutaccountExport
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/ransomOutaccountExport
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type     订单类型
     * @apiParam {int} ransom_status     赎楼状态
     * @apiParam {int} ransom_type     赎楼类型（1商业贷款 2公积金贷款 3家装/消费贷）
     * @apiParam {int} keywords     关键词
     */
    public function ransomOutaccountExport() {
        $create_uid = $this->request->get('create_uid', '');
        $subordinates = $this->request->get('subordinates', 0);
        $type = $this->request->get('type', '');
        $ransom_status = $this->request->get('ransom_status', '');
        $ransom_type = $this->request->get('ransom_type', '');
        $keywords = $this->request->get('keywords', '', 'trim');
        //查询条件组装
        $where = [];
        if ($create_uid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($create_uid);
            } else {
                $userStr = $create_uid;
            }
            $where['o.financing_manager_id'] = ['in', $userStr];
        }
        $uid = $this->userInfo['id'];
        if (empty($uid))
            return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
        //数据控制  赎楼主管以及经理以上职级的人能看到所有跟进派单  其他赎楼员只能看到自己的单
        $group = $this->userInfo['group'];
        $auth_group = $this->auth_group;
        if (!check_auth($auth_group['foreclosure_director'], $group)) {
            $userStr = SystemUser::getOrderPowerStr($uid);
            if ($userStr != 'super') {
                $where['x.ransome_id'] = ['in', $userStr];
            }
        }
        $type && $where['o.type'] = $type;
        $ransom_status && $where['x.ransom_status'] = $ransom_status;
        $ransom_type && $where['x.ransom_type'] = $ransom_type;
        $keywords && $where['x.order_sn|o.finance_sn|e.estate_name'] = ['like', "%{$keywords}%"];
        $where['x.is_dispatch'] = array('neq', 2);
        $field = "x.id,o.type,x.ransom_end_time,x.order_sn,x.ransom_bank,x.ransom_status,x.ransom_type,x.ransomer,e.estate_name,g.money";
        $creditList = $this->orderransomdispatch->alias('x')
                ->join('__ORDER__ o', 'o.order_sn=x.order_sn')
                ->join('__ORDER_GUARANTEE__ g', 'g.order_sn=x.order_sn')
                ->join('__ESTATE__ e', 'e.order_sn=x.order_sn', 'left')
                ->where($where)->field($field)
                ->order('x.create_time', 'DESC')
                ->group('x.id')
                ->select();
        $exportData = [];
        if (!empty($creditList)) {
            foreach ($creditList as $key => $value) {
                $exportData[$key]['num'] = $key + 1;
                $exportData[$key]['order_sn'] = $value['order_sn'];
                $exportData[$key]['estate_name'] = $value['estate_name'];
                $exportData[$key]['ransom_status_text'] = $this->dictionary->getValnameByCode('ORDER_JYDB_FINC_STATUS', $value['ransom_status']); //赎楼状态
                $exportData[$key]['money'] = $value['money'];
                $exportData[$key]['ransom_type_text'] = $this->orderransomdispatch->getRansomtype($value['ransom_type'], $value['type']); //赎楼类型
                $exportData[$key]['ransom_bank'] = $value['ransom_bank'];
                $exportData[$key]['apply_money'] = $this->orderransomout->where(['ransom_dispatch_id' => $value['id'], 'account_status' => ['in', '1,2,3,5']])->sum('money');
                if ($value['type'] == 'JYDB' || $value['type'] == 'JYXJ' || $value['type'] == 'TMXJ' || $value['type'] == 'GMDZ') {
                    $exportData[$key]['ac_money'] = $this->orderransomout->where(['ransom_dispatch_id' => $value['id'], 'account_status' => 5])->sum('cut_money');
                    $exportData[$key]['last_money'] = $exportData[$key]['apply_money'] - $exportData[$key]['ac_money'];
                } else {
                    $exportData[$key]['ac_money'] = 0;
                    $exportData[$key]['last_money'] = 0;
                }
                $exportData[$key]['ransom_end_time'] = empty($value['ransom_end_time']) ? '' : date('Y-m-d', $value['ransom_end_time']);
                $exportData[$key]['ransomer'] = $value['ransomer'];
            }
        }
        try {
            $spreadsheet = new Spreadsheet();
            $resInfo = $exportData;
            $head = ['0' => '序号', '1' => '业务单号', '2' => '房产名称', '3' => '当前状态', '4' => '担保金额/元',
                '5' => '赎楼类型    ', '6' => '赎楼银行', '7' => '申请出账金额/元', '8' => '实际赎楼金额/元',
                '9' => '尾款金额/元', '10' => '完成日期', '11' => '跟单员'];
            array_unshift($resInfo, $head);

            //$fileName = iconv("UTF-8", "GB2312//IGNORE", '赎楼提成列表' . date('Y-m-d') . mt_rand(1111, 9999));
            $fileName = date('Y-m-dHis');
            $spreadsheet->getActiveSheet()->fromArray($resInfo);
            $spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true)->setName('Arial')->setSize(12);
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
            $worksheet = $spreadsheet->getActiveSheet();
            $styleArray = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $worksheet->getStyle('A1:P1')->applyFromArray($styleArray);
            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $Path = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'download' . DS . date('Ymd');
            if (!file_exists($Path)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($Path, 0700);
            }
            $pathName = $Path . DS . $fileName . '.Xlsx';
            $objWriter->save($pathName);
            $retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS . iconv("GB2312", "UTF-8", $fileName) . '.Xlsx';
            return $this->buildSuccess(['url' => $retuurl]);
        } catch (\Exception $e) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '导出失败!' . $e->getMessage());
        }
    }

    /**
     * @api {get} admin/Foreclosure/ransomDetail 赎楼详情页[admin/Foreclosure/ransomDetail]
     * @apiVersion 1.0.0
     * @apiName ransomDetail
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/ransomDetail
     *
     * @apiParam {int} id    赎楼派单id
     *
     *  @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * "data": {
      "orderinfo": {
      "estate_name": [
      "东雨田村二期雨山阁13B"
      ],
      "estate_owner": "叶腾飞、滕天磊",
      "type_text": "非交易现金",
      "type": "TMXJ",
      "finance_sn": "100000038",
      "order_sn": "TMXJ2018070006",
      "ransom_status_text": "已完成",
      "ransom_status": 207,
      "money": "132416.00",
      "default_interest": "136.00",
      "self_financing": "356.00",
      "channel_money": "26482.00",
      "company_money": "105934.00",
      "can_money": 132908,
      "out_money": 1200,
      "use_money": 131708
      },
      "dispatch": {
      "ransom_type_text": "公积金贷款",
      "ransom_status_text": "已完成",
      "ransomer": "聂梦",
      "ransomer_id": 392,
      "ransom_bank": "建设银行-深圳东湖支行",
      "arrears": "10000.00"
      },
      "debitinfolog": {
      "info": {
      "cut_money": 1000
      },
      "totlearr": [
      {
      "id": 95,
      "money": "1200.00",
      "cut_status": 1,
      "item_text": "银行罚息",
      "cut_money": "1000.00",
      "last_money": 200,
      "way_text": "现金",
      "is_prestore_text": "否",
      "account_type_text": "跟单员账户",
      "ransomer": "聂梦",
      "create_time": "2018-07-26 15:11",
      "account_status": 5,
      "account_status_text": "银行已扣款",
      "outok_time": "2018-07-26 15:11"
      }
      ]
      },
      "receipt_img": [],
      "checkinfo": [],
      "preliminary_question": [
      {
      "describe": "无",
      "status": 1
      }
      ],
      "needing_attention": [
      {
      "process_name": "待部门经理审批",
      "item": null
      },
      {
      "process_name": "待审查助理审批",
      "item": null
      },
      {
      "process_name": "待审查员审批",
      "item": null
      },
      {
      "process_name": "待审查主管审批",
      "item": null
      },
      {
      "process_name": "待资料入架",
      "item": null
      }
      ],
      "reimbursement_info": [
      {
      "id": 163,
      "bankaccount": "章泽雨 ",
      "accounttype": 3,
      "bankcard": "611023197708149013",
      "openbank": "工商银行",
      "verify_card_status": 0,
      "type": false,
      "type_text": "",
      "verify_card_status_text": "待核卡",
      "accounttype_str": "买方"
      },
      {
      "id": 164,
      "bankaccount": "滕天磊 ",
      "accounttype": 2,
      "bankcard": "611023197807252673",
      "openbank": "工商银行",
      "verify_card_status": 3,
      "type": [],
      "type_text": "",
      "verify_card_status_text": "已完成",
      "accounttype_str": "卖方共同借款人"
      },
      {
      "id": 165,
      "bankaccount": "邹明哲 ",
      "accounttype": 2,
      "bankcard": "611023197008222638",
      "openbank": "建设银行",
      "verify_card_status": 3,
      "type": [],
      "type_text": "",
      "verify_card_status_text": "已完成",
      "accounttype_str": "卖方共同借款人"
      },
      {
      "id": 166,
      "bankaccount": "方高俊 ",
      "accounttype": 1,
      "bankcard": "611023197907164611",
      "openbank": "建设银行",
      "verify_card_status": 3,
      "type": [],
      "type_text": "",
      "verify_card_status_text": "已完成",
      "accounttype_str": "卖方"
      }
      ]
      }
     */
    public function ransomDetail() {
        $id = $this->request->get('id', '');
        if ($id) {
            $userInfo['id'] = $this->userInfo['id'];
            if (empty($userInfo['id']))
                return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
            $data = $this->orderransomdispatch->where('id', $id)->field('is_verify,ransom_status,order_sn')->find();
            if (empty($data)) {
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '派单信息有误！');
            }
            //订单详情
            $returnInfo['orderinfo'] = OrderComponents::showDebitorderInfo($data['order_sn'], $data['ransom_status'], 1);
            //赎楼派单信息
            $returnInfo['dispatch'] = OrderComponents::redemptionDispatch($id);
            //出账申请记录
            $returnInfo['debitinfolog'] = OrderComponents::showDebitInfolog($data['order_sn'], $id);
            //回执信息
            $returnInfo['receipt_img'] = OrderComponents::showReceiptimg($id);
            //支票信息
            $returnInfo['checkinfo'] = $this->cheque->getCheckinfo($userInfo['id']);
            //风控初审问题汇总
            $returnInfo['preliminary_question'] = OrderComponents::showPreliminary($data['order_sn']);
            //风控提醒注意事项
            $returnInfo['needing_attention'] = OrderComponents::showNeedAtten($data['order_sn']);
            //银行账户信息
            $resInfo = OrderComponents::showGuaranteeBank($data['order_sn'], 'id,bankaccount,accounttype,bankcard,openbank,verify_card_status');
            $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('JYDB_ACCOUNT_TYPE'));
            if ($resInfo) {
                foreach ($resInfo as &$val) {
                    $val['accounttype_str'] = !empty($val['accounttype']) ? $newStageArr[$val['accounttype']] : '';
                }
            }
            $returnInfo['reimbursement_info'] = $resInfo;
            $returnInfo['is_verify'] = $data['is_verify']; //是否核销
            return $this->buildSuccess($returnInfo);
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/Foreclosure/getOrderreceipt 获取收款账户信息[admin/Foreclosure/getOrderreceipt]
     * @apiVersion 1.0.0
     * @apiName getOrderreceipt
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/getOrderreceipt
     *
     * @apiParam {int} order_sn    订单号
     * @apiParam {int} accounttype    账户类型（1卖方账户，2卖方共同借款人账户,3.买方账户,4买方共同借款人,5其他,6公司个人账户,7第三方账户,8公司账户，9赎楼员账户）
     * @apiParam {int} ransomer_id    赎楼员id（当选择赎楼员账户时才需要）
     *
     * @apiSuccess {string} bank    银行
     * @apiSuccess {string} bank_account   开户人
     * @apiSuccess {string} bank_card    银行卡号
     * @apiSuccess {string} accounttype_text    账户类型（买卖方预留账户的时候才有：1卖方 2卖方共同借款人）
     */
    public function getOrderreceipt() {
        $accounttype = $this->request->get('accounttype', '');
        if ($accounttype) {
            $bankinfo = [];
            if ($accounttype == 9) {
                $ransomer_id = $this->request->get('ransomer_id', '');
                $bankinfo = $this->bankcard->where(['type' => 2, 'table_id' => $ransomer_id, 'status' => 1])->field('bank,bank_account,bank_card')->select();
            } else {
                $order_sn = $this->request->get('order_sn', '');
                $order_type = Db::name('order')->where(['order_sn' => $order_sn])->value('type');
                if ($order_type == 'JYXJ' || $order_type == 'TMXJ' || $order_type == 'GMDZ' || $order_type == 'JYDB') {
                    $where['t.type'] = array('in', 1);
                } elseif ($order_type == 'PDXJ') {
                    $where['t.type'] = array('in', 6);
                } elseif ($order_type == 'DQJK') {
                    $where['t.type'] = array('in', 4);
                } elseif ($order_type == 'SQDZ') {
                    $where['t.type'] = array('in', 5);
                }
                $where = array_merge($where, ['x.order_sn' => $order_sn, 'x.accounttype' => $accounttype, 'x.status' => 1]);
                $bankinfos = $this->orderguaranteebank->alias('x')
                        ->join('__ORDER_GUARANTEE_BANK_TYPE__ t', 't.order_guarantee_bank_id=x.id', 'left')
                        ->where($where)
                        ->field('x.accounttype,x.bankcard,x.bankaccount,x.openbank')
                        ->select();
                if (!empty($bankinfos)) {
                    foreach ($bankinfos as $key => $value) {
                        $bankinfo[$key]['accounttype_text'] = $this->dictionary->getValnameByCode('JYDB_ACCOUNT_TYPE', $value['accounttype']); //账户类型
                        $bankinfo[$key]['bank_account'] = $value['bankaccount']; //开户人
                        $bankinfo[$key]['bank'] = $value['openbank']; //银行
                        $bankinfo[$key]['bank_card'] = $value['bankcard']; //银行卡号
                    }
                }
            }
            return $this->buildSuccess($bankinfo);
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '未选择账户类型');
        }
    }

    /**
     * @param $type 订单类型   ['JYDB','FINANCIAL']
     * @param $order_sn 订单编号
     * @param $dispatch_id  派单表id
     */
    public function getDispatchProcId($type, $order_sn, $dispatch_id) {
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
        $proc = Db::name('WorkflowProc')->where($info)->where([
                    'status' => 0,
                    'is_back' => 0,
                    'is_deleted' => 1
                ])->field('id,entry_id,flow_id')->find();
        !$info && $this->buildFailed(ReturnCode::DB_READ_ERROR, '数据异常');
        return $proc;
    }

    /**
     * @api {post} admin/Foreclosure/backOrder 退回派单[admin/Foreclosure/backOrder]
     * @apiVersion 1.0.0
     * @apiName backOrder
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/backOrder
     *
     * @apiParam {int} id    赎楼派单表id
     * @apiParam {string} operate_reason    退单原因
     *
     */
    public function backOrder() {
        $data = $this->request->Post('', null, 'trim');
        if ($data) {
            if (!$this->orderransomout->checkIsbackOrchange($data['id'])) {//判断是否可以退回
                return $this->buildFailed(ReturnCode::UNKNOWN, '有未结束的出账申请记录，不可退回');
            }
            $orderinfo = $this->orderransomdispatch->where('id', $data['id'])->field('order_sn,ransomer,ransom_type')->find();
            $orderinfo['ransom_type'] = $this->orderransomdispatch->getRansomtype($orderinfo['ransom_type']);
            Db::startTrans();
            try {
                /* 驳回审批流开始 */
                //根据订单号用户ID 后端获取 流程步骤表主键id
                $type = Db::name('order')->where(['order_sn' => $orderinfo['order_sn']])->value('type');
                $resInfo = $this->getDispatchProcId($type . '_FINANCIAL', $orderinfo['order_sn'], $data['id']);
                $flow_id = $resInfo['flow_id'];
                $sbacks_proc_id = self::getBackProcId($orderinfo['order_sn'], $flow_id, $resInfo['entry_id']);
                $config = [
                    'user_id' => $this->userInfo['id'], // 用户id
                    'user_name' => $this->userInfo['name'], // 用户姓名
                    'proc_id' => $resInfo['id'], // 流程步骤表主键id
                    'content' => $data['operate_reason'], // 审批意见
                    'back_proc_id' => $sbacks_proc_id, // 退回节点id
                    'order_sn' => $orderinfo['order_sn']
                ];
                $workflow = new Workflow($config);
                $workflow->unpass();
                //更改该订单为退回派单
                Db::name('order_ransom_dispatch')->where(['id' => $data['id']])->update(['is_dispatch' => 2, 'ransome_id' => NUll, 'ransomer' => NULL, 'update_time' => time()]);
                /* 驳回审批流结束 */

                //加订单操作记录
                $userInfo['id'] = $this->userInfo['id'];
                if (empty($userInfo['id']))
                    return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
                $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                $userInfo['deptid'] = $user['deptid'];
                $userInfo['deptname'] = $user['deptname'];
                $operate_reason = $data['operate_reason'];
                $str = !empty($orderinfo['ransom_type']) ? "(" . $orderinfo['ransom_type'] . ")" : '';
                $operate_det = '退回赎楼派单：' . $user['name'] . '将' . $orderinfo['ransomer'] . $str . "的赎楼派单退回";
                $operate_table = 'order_ransom_dispatch';
                $operate_table_id = $data['id'];
                if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '退回赎楼派单', '待赎楼员申请出账', $operate_det, $operate_reason, 1014, $operate_table, $operate_table_id)) {
                    Db::commit();
                    return $this->buildSuccess();
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');

                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '请输入退回原因');
        }
    }

    //获取退回节点id
    private function getBackProcId($order_sn, $flow_id, $entry_id) {
        $where['wp.wf_status'] = '201';
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
     * @api {get} admin/Foreclosure/getRomsomer 模糊获取赎楼员[admin/Foreclosure/getRomsomer]
     * @apiVersion 1.0.0
     * @apiName getRomsomer
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/getRomsomer
     *
     * @apiParam {string}    name 赎楼员姓名
     * @apiParam {string} orderSn
     */
    public function getRomsomer() {
        $name = $this->request->get('name', '');
        $orderSn = $this->request->get('orderSn', '');
        if (empty($orderSn))
            return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数无效!');
        /* 查询是否需要赎楼 */
        $is_foreclosure = Db::name('order_guarantee')->where(['order_sn' => $orderSn])->value('is_foreclosure');
        $companyId = Db::name('order')->where(['order_sn' => $orderSn])->value('companyid'); //公司ID
        $data = $is_foreclosure == 1 ? SystemUser::getRansomer(1, $this->auth_group['redemption_staff'], $companyId, $name, 10) : SystemUser::getRansomer(0, 0, $companyId, $name, 10);
        return $this->buildSuccess($data);
    }

    /**
     * @api {post} admin/Foreclosure/changeRomsomer 改派赎楼员[admin/Foreclosure/changeRomsomer]
     * @apiVersion 1.0.0
     * @apiName changeRomsomer
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/changeRomsomer
     *
     * @apiParam {int} id    赎楼派单表id
     * @apiParam {int}    ransome_id 赎楼员id
     * @apiParam {string}    ransomer 赎楼员姓名
     *
     */
    public function changeRomsomer() {
        $data = $this->request->Post('', null, 'trim');
        if ($data) {
            if (!$this->orderransomout->checkIsbackOrchange($data['id'])) {//判断是否可以改派
                return $this->buildFailed(ReturnCode::UNKNOWN, '有未结束的出账申请记录，不可改派');
            }
            $updata = ['ransome_id' => $data['ransome_id'], 'ransomer' => $data['ransomer'], 'update_time' => time()];
            $orderinfo = $this->orderransomdispatch->where('id', $data['id'])->field('order_sn,ransomer,ransom_type')->find();
            $orderinfo['ransom_type'] = $this->orderransomdispatch->getRansomtype($orderinfo['ransom_type']);
            $userInfo['id'] = $this->userInfo['id'];
            if (empty($userInfo['id']))
                return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
            Db::startTrans();
            try {
                if ($this->orderransomdispatch->where('id', $data['id'])->update($updata) > 0) {
                    //加APP消息推送记录  2018.8.27
                    if (!$this->message->AddmessageRecord($data['ransome_id'], 2, 1, $data['id'], $orderinfo['order_sn'], 206, '派单消息', '收到一条新的派单，订单号为' . $orderinfo['order_sn'] . '，请点击查看', 1, 1, 0, 0, '', 'PC改派派单', 'order_ransom_dispatch')) {
                        Db::rollback();
                        return $this->buildFailed(ReturnCode::UPDATE_FAILED, '消息推送记录新增失败！');
                    }
                    //加订单操作记录
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $str = !empty($orderinfo['ransom_type']) ? "(" . $orderinfo['ransom_type'] . ")" : '';
                    $operate_det = '改派赎楼员：' . $user['name'] . '将' . $orderinfo['ransomer'] . $str . "的赎楼派单改派给" . $data['ransomer'];
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $data['id'];
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '改派赎楼员', '待改派赎楼员', $operate_det, $operate_reason = '', 1014, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/Foreclosure/completeRomsom 完成赎楼[admin/Foreclosure/completeRomsom]
     * @apiVersion 1.0.0
     * @apiName completeRomsom
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/completeRomsom
     *
     * @apiParam {int} id    赎楼派单表id
     *
     */
    public function completeRomsom() {
        $id = $this->request->get('id', '');
        if ($id) {
            $orderinfo = $this->orderransomdispatch->where('id', $id)->field('order_sn,ransomer,ransom_type')->find();
            $order_type = Db::name('order')->where(['order_sn' => $orderinfo['order_sn']])->value('type');
            if ($this->orderransomout->where(['order_sn' => $orderinfo['order_sn'], 'ransom_dispatch_id' => $id])->count() < 1) {
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '当前操作至少需要一笔出账申请记录');
            }
            $msg = '';
            if ($order_type == 'JYXJ' || $order_type == 'TMXJ' || $order_type == 'GMDZ' || $order_type == 'JYDB') {
                $orderinfo['ransom_type'] = $this->orderransomdispatch->getRansomtype($orderinfo['ransom_type'], $order_type);
                $msg = '部分出账未确认扣款，暂不支持完成赎楼操作';
            } elseif ($order_type == 'PDXJ' || $order_type == 'DQJK' || $order_type == 'SQDZ') {
                $orderinfo['ransom_type'] = (new Order())->getType($order_type); //订单类型
                $msg = '当前出账状态不支持确认扣款操作';
            }
            if (!$this->orderransomout->checkiscomplete($id)) {
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, $msg);
            }
            $updata = ['ransom_status' => 207, 'ransom_end_time' => time()];
            Db::startTrans();
            try {
                if ($this->orderransomdispatch->where('id', $id)->update($updata) > 0) {
                    //判断是否主订单已经完成赎楼
                    $stage = $this->orderransomdispatch->checkIsransom($orderinfo['order_sn']);
                    //加订单操作记录
                    $userInfo['id'] = $this->userInfo['id'];
                    if (empty($userInfo['id']))
                        return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $operate_det = $stage != 1014 ? '完成赎楼：' . $user['name'] . "(" . $orderinfo['ransom_type'] . ")完成了整个订单的全部赎楼" : '完成赎楼：' . $user['name'] . "(" . $orderinfo['ransom_type'] . ")完成了当前派单的赎楼";
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $id;
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], show_status_name($stage, 'ORDER_JYDB_STATUS'), '当前派单完成赎楼', '待赎楼员完成赎楼', $operate_det, $operate_reason = '', $stage, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼完成信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {post} admin/Foreclosure/determineMoney 确定扣款[admin/Foreclosure/determineMoney]
     * @apiVersion 1.0.0
     * @apiName determineMoney
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/determineMoney
     *
     * @apiParam {int} id    出账id
     * @apiParam {int} money    确认扣款金额
     *
     */
    public function determineMoney() {
        $data = $this->request->Post('', null, 'trim');
        if ($data) {
            $orderinfo = $this->orderransomout->where('id', $data['id'])->field('order_sn,account_status,ransom_dispatch_id,money,item')->find();
            if ($data['money'] <= 0) {
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '确认扣款金额必须大于0！');
            }
            if ($data['money'] > $orderinfo['money']) {
                return $this->buildFailed(ReturnCode::PARAM_INVALID, '确认扣款金额不能大于出账金额！');
            } elseif ($orderinfo['account_status'] != 3) {
                return $this->buildFailed(ReturnCode::PARAM_INVALID, '当前出账状态不支持确认扣款操作！');
            }
            $updata = ['cut_status' => 1, 'cut_money' => $data['money'], 'update_time' => time(), 'account_status' => 5];
            $orderinfo['ransom_type'] = $this->orderransomout->getItem($orderinfo['item']);
            Db::startTrans();
            try {
                if ($this->orderransomout->where('id', $data['id'])->update($updata) > 0) {
                    $disInfo = $this->orderransomdispatch->where('id', $orderinfo['ransom_dispatch_id'])->field('ransomer,cut_money_total')->find()->toArray();
                    $this->orderransomdispatch->where('id', $orderinfo['ransom_dispatch_id'])->update(['cut_money_total' => $disInfo['cut_money_total'] + $data['money']]);
                    //加订单操作记录
                    $userInfo['id'] = $this->userInfo['id'];
                    if (empty($userInfo['id']))
                        return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $lastmoney = sprintf("%.2f", $orderinfo['money'] - $data['money']);
                    $operate_det = '确认扣款：' . $user['name'] . "(" . $orderinfo['ransom_type'] . ")确认了一笔扣款（申请金额：" . $orderinfo['money'] . "元,扣款金额:" . $data['money'] . "元,剩余尾款：" . $lastmoney . "元）";
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $orderinfo['ransom_dispatch_id'];
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '确认扣款', '财务已出账', $operate_det, $operate_reason = '', 1014, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '确认扣款信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/Foreclosure/reEditmoney 重新编辑扣款金额[admin/Foreclosure/reEditmoney]
     * @apiVersion 1.0.0
     * @apiName reEditmoney
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/reEditmoney
     *
     * @apiParam {int} id    出账id
     * @apiParam {int} money    确认扣款金额
     */
    public function reEditmoney() {
        $data = $this->request->Post('', null, 'trim');
        if ($data) {
            $orderinfo = $this->orderransomout->where('id', $data['id'])->field('order_sn,cut_money,account_status,ransom_dispatch_id,money,item')->find();
            if ($data['money'] <= 0) {
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '确认扣款金额必须大于0！');
            }
            if ($data['money'] > $orderinfo['money']) {
                return $this->buildFailed(ReturnCode::PARAM_INVALID, '确认扣款金额不能大于出账金额！');
            } elseif ($orderinfo['account_status'] != 5) {
                return $this->buildFailed(ReturnCode::PARAM_INVALID, '当前出账状态不支持编辑扣款金额操作！');
            }
            $updata = ['cut_money' => $data['money'], 'update_time' => time()];
            $orderinfo['ransom_type'] = $this->orderransomout->getItem($orderinfo['item']);
            Db::startTrans();
            try {
                if ($this->orderransomout->where('id', $data['id'])->update($updata) > 0) {
                    $disInfo = $this->orderransomdispatch->where('id', $orderinfo['ransom_dispatch_id'])->field('ransomer,cut_money_total')->find()->toArray();
                    $totleadd = sprintf("%.2f", $data['money'] - $orderinfo['cut_money']); //原扣款和编辑后的扣款差值 累加到派单出账总额里面
                    $this->orderransomdispatch->where('id', $orderinfo['ransom_dispatch_id'])->update(['cut_money_total' => $disInfo['cut_money_total'] + $totleadd]);
                    //加订单操作记录
                    $userInfo['id'] = $this->userInfo['id'];
                    if (empty($userInfo['id']))
                        return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $lastmoney = sprintf("%.2f", $orderinfo['money'] - $data['money']);
                    $operate_det = '编辑扣款：' . $user['name'] . "(" . $orderinfo['ransom_type'] . ")编辑了一笔扣款（申请金额：" . $orderinfo['money'] . "元,扣款金额:" . $data['money'] . "元,剩余尾款：" . $lastmoney . "元）";
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $orderinfo['ransom_dispatch_id'];
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '编辑扣款', '银行已扣款', $operate_det, $operate_reason = '', 1014, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '确认扣款信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {post} admin/Foreclosure/determineMoney 上传回执[admin/Foreclosure/uploadReceipt]
     * @apiVersion 1.0.0
     * @apiName uploadReceipt
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/uploadReceipt
     *
     * @apiParam {int} id    赎楼出账表id
     * @apiParam {array} receipt_img    回执图片id
     *
     */
    public function uploadReceipt() {
        $data['receipt_img'] = $this->request->post('receipt_img/a', '');
        $data['id'] = $this->request->post('id', '');
        if ($data) {
            $orderinfo = $this->orderransomdispatch->where('id', $data['id'])->field('receipt_img,order_sn,ransomer,ransom_type,is_verify')->find();
            if ($orderinfo['is_verify'] == 1) {
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '该派单已被财务核销，无法再上传回执');
            }
            $receipt_img = implode(',', $data['receipt_img']);
            if (!empty($orderinfo['receipt_img'])) {
                $receipt_img = $orderinfo['receipt_img'] . "," . $receipt_img;
            }
            $updata = ['update_time' => time(), 'receipt_img' => $receipt_img];
            $orderinfo['ransom_type'] = $this->orderransomdispatch->getRansomtype($orderinfo['ransom_type']);
            Db::startTrans();
            try {
                if ($this->orderransomdispatch->where('id', $data['id'])->update($updata)) {
                    //加订单操作记录
                    $userInfo['id'] = $this->userInfo['id'];
                    if (empty($userInfo['id']))
                        return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
                    $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                    $userInfo['deptid'] = $user['deptid'];
                    $userInfo['deptname'] = $user['deptname'];
                    $str = !empty($orderinfo['ransom_type']) ? "(" . $orderinfo['ransom_type'] . ")" : '';
                    $operate_det = '上传回执：' . $user['name'] . $str . count($data['receipt_img']) . "张回执单";
                    $operate_table = 'order_ransom_dispatch';
                    $operate_table_id = $data['id'];
                    if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1015, 'ORDER_JYDB_STATUS'), '上传回执', '已完成赎楼', $operate_det, $operate_reason = '', 1015, $operate_table, $operate_table_id)) {
                        Db::commit();
                        return $this->buildSuccess();
                    }
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '上传回执信息更新失败！');
            } catch (Exception $exc) {
                return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
            }
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {get} admin/Foreclosure/lookdetail 查看详情[admin/Foreclosure/lookdetail]
     * @apiVersion 1.0.0
     * @apiName lookdetail
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/lookdetail
     *
     * @apiParam {int} id    赎楼出账
     *
     * @apiSuccess {string} money_text    出账金额
     * @apiSuccess {string} way_text    出账方式
     * @apiSuccess {string} is_prestore_text    是否预存(现金)
     * @apiSuccess {string} prestore_day    预存天数(现金)
     * @apiSuccess {string} account_type    账户类型（现金）
     * @apiSuccess {string} receipt_text    收款账户（现金）
     * @apiSuccess {string} cheque_num   支票号码（支票）
     * @apiSuccess {string} bank    支票银行（支票）
     * @apiSuccess {string} create_time    申请时间
     * @apiSuccess {string} account_status_text    出账状态
     * @apiSuccess {string} outok_time    出账时间
     * @apiSuccess {string} debit_text    出账账户
     */
    public function lookdetail() {
        $id = $this->request->get('id', '');
        if ($id) {
            //订单信息
            $field = 'receipt_bank_card,prestore_day,receipt_bank,receipt_bank_account,cheque_num,bank,money,item,way,is_prestore,account_type,out_bank_card,out_bank,out_bank_account,account_status,create_time,outok_time,ransom_dispatch_id';
            $data = $this->orderransomout->where('id', $id)->field($field)->find()->toArray();
            if (empty($data)) {
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '出账信息有误！');
            }
            $item = !empty($item) ? "(" . $this->orderransomout->getItem($data['item']) . ")" : '';
            $info['money_text'] = $data['money'] . "元" . $item;
            $info['way_text'] = $data['way'] == 1 ? '现金出账' : '支票出账';
            $info['way'] = $data['way'];
            if ($data['way'] == 1) {
                $info['is_prestore_text'] = $data['is_prestore'] == 1 ? '是' : '否';
                $info['prestore_day'] = $data['is_prestore'] == 1 ? $data['prestore_day'] : '-'; //预存天数
                $info['account_type'] = $this->orderransomout->getAccounttype($data['account_type']); //出账项目
                $info['receipt_text'] = $data['way'] == 1 ? $data['receipt_bank_account'] . $data['receipt_bank_card'] . "(" . $data['receipt_bank'] . ")" : $data['bank']; //入账账户
            } else {
                $info['cheque_num'] = $data['cheque_num'];
                $info['bank'] = $data['bank'];
            }
            $info['create_time'] = date('Y-m-d H:i', strtotime($data['create_time'])); //申请时间
            $info['outok_time'] = !empty($data['outok_time']) ? date('Y-m-d H:i', $data['outok_time']) : null; //出账时间
            $info['account_status_text'] = $this->orderransomout->getAccountstatus($data['account_status']); //出账状态
            $info['debit_text'] = $data['account_status'] > 1 ? $data['out_bank_account'] . $data['out_bank_card'] . "(" . $data['out_bank'] . ")" : null; //出账账户
            return $this->buildSuccess($info);
        } else {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数有误');
        }
    }

    /**
     * @api {post} admin/Foreclosure/applyAccount 申请赎楼出账[admin/Foreclosure/applyAccount]
     * @apiVersion 1.0.0
     * @apiName applyAccount
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/applyAccount
     *
     * @apiParam {int} id    赎楼派单表id
     * @apiParam {int}  item  出账类型（1.当前账目类型 2.银行罚息）
     * @apiParam {int}  money  出账金额
     * @apiParam {int}   way  出账方式(1现金 2支票)
     * @apiParam {int}   is_prestore  是否预存（现金）
     * @apiParam {int}   prestore_day  预存天数（现金预存单）
     * @apiParam {int}   account_type 账户类型（1.跟单员账户，2卖方账户，3卖方共同借款人账户,4.买方账户，5公司个人账户）
     * @apiParam {int}   out_bank_card  收款卡号（现金）
     * @apiParam {int}   out_bank  收款银行（现金）
     * @apiParam {int}   out_bank_account  收款账户（现金）
     * @apiParam {int}   bank  支票银行（支票）
     * @apiParam {int}   cheque_num  支票号码（支票）
     * @apiParam {int}   cid  支票id（支票）
     *
     */
    public function applyAccount() {
        $data = $this->request->Post('', null, 'trim');
        $msg = $this->validate($data, 'ApplyAccount');
        if ($msg !== true)
            return $this->buildFailed(ReturnCode::PARAM_INVALID, $msg);
        $userInfo['id'] = $this->userInfo['id'];
        if (empty($userInfo['id']))
            return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
        $orderinfo = $this->orderransomdispatch->where('id', $data['id'])->field('ransom_type,order_sn,ransomer')->find();
        if (empty($orderinfo)) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '赎楼派单信息有误！');
        }
        $outinfo = $this->orderransomout->getOrdermoney($orderinfo['order_sn']);
        if ($data['money'] <= 0) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '出账金额必须大于0！');
        }
        if ($data['money'] > $outinfo['use_money']) {
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '出账金额不能大于可用余额！');
        }
        $ransom_type = $data['item'] == 2 ? 4 : $orderinfo['ransom_type']; //确定是银行罚息还是其他账目类型
        $adddata = ['money' => $data['money'], 'way' => $data['way'], 'create_time' => time(), 'update_time' => time(), 'create_uid' => $userInfo['id'], 'account_status' => 1, 'order_sn' => $orderinfo['order_sn'], 'ransom_dispatch_id' => $data['id'], 'item' => $ransom_type];
        if ($data['way'] == 1) {
            $adddatas = ['is_prestore' => $data['is_prestore'], 'prestore_day' => $data['prestore_day'], 'account_type' => $data['account_type'], 'receipt_bank_card' => $data['out_bank_card'], 'receipt_bank' => $data['out_bank'], 'receipt_bank_account' => $data['out_bank_account']];
        } else {
            $adddatas = ['bank' => $data['bank'], 'cheque_num' => $data['cheque_num']];
        }
        $adddata = array_merge($adddata, $adddatas);
        Db::startTrans();
        try {
            if ($this->orderransomout->save($adddata)) {
                //如果使用了支票，就处理这个支票
                $data['way'] == 2 && $this->cheque->dealwithCheque($orderinfo['order_sn'], $userInfo['id'], $data['cid']);
                $this->orderransomdispatch->where('id', $data['id'])->setInc('money_total', $data['money']);
                //加订单操作记录
                $user = $this->systemuser->where('id', $userInfo['id'])->field('deptid,deptname,name')->find();
                $userInfo['deptid'] = $user['deptid'];
                $userInfo['deptname'] = $user['deptname'];
                $ransom_type_text = $this->orderransomout->getItem($ransom_type);
                $operate_det = '赎楼申请出账：' . $user['name'] . "申请一笔" . $data['money'] . "元的" . $ransom_type_text . "赎楼出账";
                $operate_table = 'order_ransom_dispatch';
                $operate_table_id = $data['id'];
                if (OrderComponents::addOrderLog($userInfo, $order_sn = $orderinfo['order_sn'], $stage = show_status_name(1014, 'ORDER_JYDB_STATUS'), '赎楼申请出账', '待赎楼申请出账', $operate_det, $operate_reason = '', 1014, $operate_table, $operate_table_id)) {
                    Db::commit();
                    return $this->buildSuccess();
                }
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '赎楼派单操作记录新增失败！');
            }
            Db::rollback();
            return $this->buildFailed(ReturnCode::UPDATE_FAILED, '申请出账失败！');
        } catch (Exception $exc) {
            return $this->buildFailed(ReturnCode::EXCEPTION, '系统繁忙，请稍后重试!' . $exc->getMessage());
        }
    }

    /**
     * @api {get} admin/Foreclosure/ransomAllOutaccountExport 赎楼出账列表导出[admin/Foreclosure/ransomAllOutaccountExport]
     * @apiVersion 1.0.0
     * @apiName ransomAllOutaccountExport
     * @apiGroup Foreclosure
     * @apiSampleRequest admin/Foreclosure/ransomAllOutaccountExport
     *
     * @apiParam {int} create_uid    人员id
     * @apiParam {int} subordinates    1含下属 0不含下属
     * @apiParam {int} type     订单类型
     * @apiParam {int} ransom_status     赎楼状态
     * @apiParam {int} ransom_type     赎楼类型（1商业贷款 2公积金贷款 3家装/消费贷）
     * @apiParam {int} keywords     关键词
     */
    public function ransomAllOutaccountExport() {
        $create_uid = $this->request->get('create_uid', '');
        $subordinates = $this->request->get('subordinates', 0);
        $type = $this->request->get('type', '');
        $ransom_status = $this->request->get('ransom_status', '');
        $ransom_type = $this->request->get('ransom_type', '');
        $keywords = $this->request->get('keywords', '', 'trim');
        //查询条件组装
        $where = [];
        if ($create_uid) {
            if ($subordinates == 1) {
                $userStr = SystemUser::getOrderPowerStr($create_uid);
            } else {
                $userStr = $create_uid;
            }
            $where['o.financing_manager_id'] = ['in', $userStr];
        }
        $uid = $this->userInfo['id'];
        if (empty($uid))
            return $this->buildFailed(ReturnCode::UNKNOWN, '登录过期，请重新登录');
        //数据控制  赎楼主管以及经理以上职级的人能看到所有跟进派单  其他赎楼员只能看到自己的单
        $group = $this->userInfo['group'];
        $auth_group = $this->auth_group;
        if (!check_auth($auth_group['foreclosure_director'], $group)) {
            $userStr = SystemUser::getOrderPowerStr($uid);
            if ($userStr != 'super') {
                $where['x.ransome_id'] = ['in', $userStr];
            }
        }
        $type && $where['o.type'] = $type;
        $ransom_status && $where['x.ransom_status'] = $ransom_status;
        $ransom_type && $where['x.ransom_type'] = $ransom_type;
        $keywords && $where['x.order_sn|o.finance_sn|e.estate_name'] = ['like', "%{$keywords}%"];
        $where['x.is_dispatch'] = array('neq', 2);
        $field = "x.id,o.type,x.ransom_end_time,x.order_sn,x.ransom_bank,x.ransom_status,x.ransom_type,x.ransomer,e.estate_name,g.money,e.estate_certnum,g.out_account_total";
        $creditList = $this->orderransomdispatch->alias('x')
                ->join('__ORDER__ o', 'o.order_sn=x.order_sn')
                ->join('__ORDER_GUARANTEE__ g', 'g.order_sn=x.order_sn')
                ->join('__ESTATE__ e', 'e.order_sn=x.order_sn', 'left')
                ->where($where)->field($field)
                ->order('x.create_time', 'DESC')
                ->group('x.id')
                ->select();
        $exportData = [];
        if (!empty($creditList)) {
            foreach ($creditList as $key => $value) {
                $exportData[$key]['num'] = $key + 1;
                $exportData[$key]['order_sn'] = $value['order_sn'];
                $exportData[$key]['estate_name'] = $value['estate_name'];
                $exportData[$key]['estate_certnum'] = "\t" . $value['estate_certnum'] . "\t";
                $exportData[$key]['seller'] = "\t" . implode(',', Db::name('customer')->where(['order_sn' => $value['order_sn'], 'is_seller' => 2, 'status' => 1])->column('cname')) . "\t";
                $exportData[$key]['buyer'] = "\t" . implode(',', Db::name('customer')->where(['order_sn' => $value['order_sn'], 'is_seller' => 1, 'status' => 1])->column('cname')) . "\t";
                $exportData[$key]['ransom_status_text'] = $this->dictionary->getValnameByCode('ORDER_JYDB_FINC_STATUS', $value['ransom_status']); //赎楼状态
                $exportData[$key]['ransom_type_text'] = $this->orderransomdispatch->getRansomtype($value['ransom_type'], $value['type']); //赎楼类型
                $exportData[$key]['ransom_bank'] = $value['ransom_bank'];
                $exportData[$key]['out_account_total'] = $value['out_account_total'];
                $exportData[$key]['apply_money'] = $this->orderransomout->where(['ransom_dispatch_id' => $value['id'], 'account_status' => ['in', '1,2,3,5']])->sum('money');
                $exportData[$key]['sq'] = ''; //空白表头
                $exportData[$key]['kj'] = '';
                $exportData[$key]['cn'] = '';
                $exportData[$key]['bz'] = '';
            }
        }
        try {
            $spreadsheet = new Spreadsheet();
            $resInfo = $exportData;
            $head = ['0' => '序号', '1' => '业务单号', '2' => '房产名称', '3' => '房产证号', '4' => '卖方', '5' => '买方    ', '6' => '当前状态', '7' => '赎楼类型', '8' => '赎楼银行',
                '9' => '预计出账金额/元', '10' => '已申请出账金额/元', '11' => '申请出账金额/元', '12' => '会计', '13' => '出纳', '14' => '备注'];
            array_unshift($resInfo, $head);
            //$fileName = iconv("UTF-8", "GB2312//IGNORE", '赎楼出账列表' . date('Y-m-dHis')); 
            $fileName = date('Y-m-dHis');
            //$fileName = '征信查询'.date('Y-m-d').mt_rand(1111,9999);
            $spreadsheet->getActiveSheet()->fromArray($resInfo);
            $spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true)->setName('Arial')->setSize(12);
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
            $worksheet = $spreadsheet->getActiveSheet();
            $styleArray = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $worksheet->getStyle('A1:P1')->applyFromArray($styleArray);
            $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $Path = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'download' . DS . date('Ymd');
            if (!file_exists($Path)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($Path, 0700);
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
