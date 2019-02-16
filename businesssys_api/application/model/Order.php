<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/17
 * Time: 18:10
 */

namespace app\model;

use app\util\Tools;
use think\Db;
use app\util\OrderComponents;

class Order extends Base
{

    protected $dateFormat = 'Y-m-d';
//（现金类业务才有回款）回款状态
    public static $returnMoneyStatusMap = [
        1 => '回款待完成',
        2 => '回款完成待复核',
        3 => '回款完成待核算',
        4 => "回款已完成"
    ];

    /**
     * /* @author 林桂均
     * 订单列表查询
     * @param array $where
     * @param $page
     * @return array
     * @throws \think\exception\DbException
     */
    public static function orderList($where, $page, $pageSize)
    {

        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        $where['y.status'] = 1;
        $where['z.status'] = 1;
        $where['x.status'] = ['in','1,2']; //todo 2可以查看已撤单
        return self::alias('x')
            ->field('x.order_sn,x.financing_manager_id,x.type,x.create_time,x.stage,x.status,y.estate_region,estate_name,y.estate_owner,x.is_mortgage_finish,x.is_foreclosure_finish,z.is_loan_finish,z.guarantee_fee_status,x.is_data_entry')
            ->join('estate y', 'x.order_sn=y.order_sn', 'left')
            ->join('order_guarantee z', 'x.order_sn=z.order_sn')
            ->where($where)
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
    }

    /**
     * /* @author 林桂均
     * 订单详情
     * @param $orderSn
     * @return array
     * @throws \think\exception\DbException
     */
    public static function orderDetail($orderSn, $type)
    {

        $orderModel = self::alias('x');
        $field = 'x.order_sn,x.type,x.create_time,x.stage,x.status,x.financing_manager_id,x.create_uid';
        if ($type === 'TMXJ' || $type === 'GMDZ') {
            $field = 'x.order_sn,x.type,x.create_time,x.stage,x.status,x.financing_manager_id,x.create_uid,y.evaluation_price,y.now_mortgage';
            $orderModel->join('order_guarantee y', 'x.order_sn=y.order_sn', 'left');
        }
         //todo 2可以查看已撤单
        $orderInfo = $orderModel->field($field)->where(['x.order_sn' => $orderSn, 'x.status' => ['in','1,2'], 'x.type' => $type])->find();

        if (!$orderInfo)
            return false;
        return $orderInfo;
    }

    /* @author 赵光帅
     * 费用入账列表查询
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function costList($map, $page, $pageSize)
    {
        return self::alias('x')
            ->field('x.order_sn,x.finance_sn,x.type,z.name,estate_name,y.estate_owner,n.ac_guarantee_fee_time,n.guarantee_fee,n.ac_guarantee_fee,n.guarantee_fee_status')
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn', 'LEFT')
            ->join('system_user z', 'x.financing_manager_id=z.id')
            ->where($map)
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
    }

    /* @author 赵光帅
     * 导出费用入账列表查询
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function exportFinanceList($map)
    {
        //根据条件查询出所有符合要求的订单
        $orderSnInfo = Db::name('order')->alias('x')
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn', 'LEFT')
            ->where($map)
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->column('x.order_sn');
        //组装房产的数据
        $estatesInfo = self::assembleEstate($orderSnInfo);
        //获取业主姓名
        $sellerName = self::customerName($orderSnInfo, 1);
        //获取买方姓名
        $buyerName = self::customerName($orderSnInfo, 2);

        $field = 'x.order_sn,x.finance_sn,x.type,
            y.estate_name,y.estate_owner,y.estate_area,z.name,z.deptname,
            n.guarantee_fee,n.fee,n.ac_guarantee_fee,n.ac_fee,n.ac_self_financing
            ,n.ac_short_loan_interest,n.ac_return_money,n.ac_default_interest,n.ac_overdue_money
            ,n.ac_exhibition_fee,n.ac_transfer_fee,n.ac_deposit,n.ac_other_money
            ,x.order_source,x.source_info,n.ac_guarantee_fee_time';
        $resInfo = Db::name('order')->alias('x')
            ->field($field)
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn', 'LEFT')
            ->join('system_user z', 'x.financing_manager_id=z.id')
            ->where($map)
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->select();
        $dictonaryType = ['ORDER_TYPE', 'CUSTOMERSOURCE'];
        $dictonaryTypeArr = dictionary_reset(Dictionary::dictionaryMultiType($dictonaryType), 1);
        if ($resInfo) {
            $num = 1;
            foreach ($resInfo as &$val) {
                //添加序号
                array_unshift($val, $num);
                $num++;
                $val['finance_sn']="\t".$val['finance_sn']."\t";
                $val['type'] = !empty($dictonaryTypeArr['ORDER_TYPE'][$val['type']]) ? $dictonaryTypeArr['ORDER_TYPE'][$val['type']] : '';
                $val['order_source'] = !empty($dictonaryTypeArr['CUSTOMERSOURCE'][$val['order_source']]) ? $dictonaryTypeArr['CUSTOMERSOURCE'][$val['order_source']] : '';
                if ($val['ac_guarantee_fee_time']) {
                    $val['ac_guarantee_fee_time'] = date('Y-m-d H:i:s', $val['ac_guarantee_fee_time']);
                }
                //应收金额
                $val['guarantee_fee'] = $val['guarantee_fee'] + $val['fee'];
                //实收金额
                $val['fee'] = $val['ac_guarantee_fee'] + $val['ac_fee'] + $val['ac_self_financing'] + $val['ac_short_loan_interest']
                    + $val['ac_return_money'] + $val['ac_default_interest'] + $val['ac_overdue_money'] + $val['ac_exhibition_fee'] + $val['ac_transfer_fee']
                    + $val['ac_deposit'] + $val['ac_other_money'];
                //更改房产
                if (isset($estatesInfo[$val['order_sn']]) && !empty($estatesInfo[$val['order_sn']])) {
                    $val['estate_name'] = join('；', $estatesInfo[$val['order_sn']]);
                } else {
                    $val['estate_name'] = '';
                }

                //更改业主姓名
                if (isset($sellerName[$val['order_sn']]) && !empty($sellerName[$val['order_sn']])) {
                    $val['estate_owner'] = "\t".join('；', $sellerName[$val['order_sn']])."\t";
                } else {
                    $val['estate_owner'] = '';
                }

                //更改买方姓名
                if (isset($buyerName[$val['order_sn']]) && !empty($buyerName[$val['order_sn']])) {
                    $val['estate_area'] = "\t".join('；', $buyerName[$val['order_sn']])."\t";
                } else {
                    $val['estate_area'] = '';
                }

            }
        }
        return $resInfo;
    }


    /*
    * 组装卖家买家姓名
    * @param {arr}  $orderSnInfo   订单编号
    * @param {int}  type  1卖家姓名  2买家姓名
    * */
    public static function customerName($orderSnInfo, $type)
    {
        if ($type === 1) {
            $customerInfo = Db::name('customer')->where(['order_sn' => ['in', $orderSnInfo], 'is_seller' => 2, 'status' => 1])->field('order_sn,cname')->select(); //业主姓名
        } else {
            $customerInfo = Db::name('customer')->where(['order_sn' => ['in', $orderSnInfo], 'is_seller' => 1, 'status' => 1])->field('order_sn,cname')->select(); //业主姓名
        }

        $returnArr = [];
        foreach ($orderSnInfo as $k => $v) {
            foreach ($customerInfo as $key => $val) {
                if ($val['order_sn'] == $v) {
                    $returnArr[$v][] = $val['cname'];
                }
            }
        }
        return $returnArr;
    }


    /*
     * 组装房产名称
     * @Param {arr}  $orderSnInfo   订单编号
     * */
    public static function assembleEstate($orderSnInfo)
    {
        //查询出所有的房产名称
        $where['order_Sn'] = ['in', $orderSnInfo];
        $where['estate_usage'] = 'DB';
        $where['delete_time'] = null;
        $where['status'] = 1;
        $estateInfo = Db::name('estate')->where($where)->field('order_sn,estate_name')->select();
        $returnArr = [];
        foreach ($orderSnInfo as $k => $v) {
            foreach ($estateInfo as $key => $val) {
                if ($val['order_sn'] == $v) {
                    $returnArr[$v][] = $val['estate_name'];
                }
            }
        }
        return $returnArr;
    }


    /* @author 赵光帅
     * 订单信息与财务入账流水明细
     * @apiParam {string}  order_sn   订单编号
     */

    public static function booksDetail($order_sn)
    {
        return self::alias('x')
            ->field('x.order_sn,x.type,z.name,z.deptname,x.finance_sn,n.self_financing,n.guarantee_fee,n.fee,n.guarantee_fee_status')
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('system_user z', 'x.financing_manager_id=z.id')
            ->where(['x.order_sn' => $order_sn])
            ->find();
    }

    /* @author 赵光帅
     * 订单信息与银行放款流水明细
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function bankList($map, $page, $pageSize)
    {
        $resInfo = self::alias('x')
            ->field('x.order_sn,x.finance_sn,x.type,z.name,estate_name,y.estate_owner,n.loan_money_time,n.money,n.loan_money,n.loan_money_status')
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn', 'LEFT')
            ->join('system_user z', 'x.financing_manager_id=z.id')
            ->where($map)
            ->order('n.loan_money_time desc')
            ->group('x.order_sn')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        $orderTypeArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        if ($resInfo) {
            foreach ($resInfo['data'] as &$val) {
                $val['type_text'] = $orderTypeArr[$val['type']] ? $orderTypeArr[$val['type']] : '';
            }
        }
        return $resInfo;
    }
    
    /**
     * @author cgenj
     * 导出银行放款入账数据
     * @Param {arr} $map    搜索条件
     */
    public static function exportBankHasList($map) {
        //根据条件查询出所有符合要求的订单
        $orderSnInfo = Db::name('order')->alias('x')
                ->join('order_guarantee n', 'x.order_sn=n.order_sn')
                ->join('estate y', 'x.order_sn=y.order_sn', 'LEFT')
                ->where($map)
                ->order('x.create_time desc')
                ->group('x.order_sn')
                ->column('x.order_sn');
        //组装房产的数据
        $estatesInfo = self::assembleEstate($orderSnInfo);
        //获取业主姓名
        $sellerName = self::customerName($orderSnInfo, 1);
        //获取买方姓名
        $buyerName = self::customerName($orderSnInfo, 2);
        //放款入账金额计算
        $intoMoney = self::intoMoneyCal($orderSnInfo);
        //赎楼银行数据
        $mortgageBank = self::mortgageBank($orderSnInfo);
        $resInfo = Db::name('order')->alias('x')
                        ->field('x.order_sn,x.finance_sn,estate_name,u.name as financing_manager_name,d.name as dept_name,ud.name as dept_manager_name,n.loan_money_time,n.money,n.loan_money,n.loan_money_status,dp.dp_redeem_bank,dp.dp_redeem_bank_branch')
                        ->join('order_guarantee n', 'x.order_sn=n.order_sn')
                        ->join('order_dp dp', 'x.order_sn=dp.order_sn')
                        ->join('estate y', 'x.order_sn=y.order_sn', 'LEFT')
                        ->join('system_user u', 'x.financing_manager_id=u.id')
                        ->join('system_user ud', 'x.dept_manager_id=ud.id')
                        ->join('system_dept d', 'x.financing_dept_id=d.id')
                        ->where($map)
                        ->order('n.loan_money_time desc')
                        ->group('x.order_sn')->select();

        $newResInfo = [];
        if ($resInfo) {
            foreach ($resInfo as $key => $val) {
                $data['NO'] = $key + 1; //序号
                $data['order_sn'] = $val['order_sn'];
                $data['finance_sn'] = $val['finance_sn'];
                //更改房产
                if (isset($estatesInfo[$val['order_sn']]) && !empty($estatesInfo[$val['order_sn']])) {
                    $data['estate_name'] = join('；', $estatesInfo[$val['order_sn']]);
                } else {
                    $data['estate_name'] = '';
                }
                //更改业主姓名
                if (isset($sellerName[$val['order_sn']]) && !empty($sellerName[$val['order_sn']])) {
                    $data['seller_name'] = join('；', $sellerName[$val['order_sn']]);
                } else {
                    $data['seller_name'] = '';
                }
                //更改买方姓名
                if (isset($buyerName[$val['order_sn']]) && !empty($buyerName[$val['order_sn']])) {
                    $data['buyer_name'] = join('；', $buyerName[$val['order_sn']]);
                } else {
                    $data['buyer_name'] = '';
                }
                $data['money'] = $val['money'];
                $data['loan_money'] = $val['loan_money'];
                $data['lend_bank'] = (isset($val['dp_redeem_bank']) ? $val['dp_redeem_bank'] : '') . (isset($val['dp_redeem_bank_branch']) ? $val['dp_redeem_bank_branch'] : '');
                //入账金额
                if (isset($intoMoney[$val['order_sn']]) && !empty($intoMoney[$val['order_sn']])) {
                    $data['into_money'] = $intoMoney[$val['order_sn']];
                } else {
                    $data['into_money'] = '';
                }
                $data['loan_money_time'] = $val['loan_money_time'];
                //赎楼银行
                if (isset($mortgageBank[$val['order_sn']]) && !empty($mortgageBank[$val['order_sn']])) {
                    $data['mortgage_bank'] = join('；', $mortgageBank[$val['order_sn']]);
                } else {
                    $data['mortgage_bank'] = '';
                }
                $data['financing_manager_name'] = $val['financing_manager_name'];
                $data['dept_name'] = $val['dept_name'];
                $data['dept_manager_name'] = $val['dept_manager_name'];
                unset($val['loan_money_status']);
                unset($val['dp_redeem_bank']);
                unset($val['dp_redeem_bank_branch']);
                $newResInfo[] = $data;
            }
        }
        return $newResInfo;
    }

    /**
     * @author cgenj
     * 银行放款入账金额计算
     * @apiParam {string}  order_sn   订单编号
     */
    public static function intoMoneyCal($orderSnInfo) {
        $list = Db::name('order_account_record')->where(['order_sn' => ['in', $orderSnInfo]])->group('order_sn')->column('order_sn,sum(total_money) as into_money');
        return $list;
    }

    /**
     * @author cgenj
     * 赎楼银行
     * @apiParam {string}  order_sn   订单编号
     */
    public static function mortgageBank($orderSnInfo) {
        $where['order_Sn'] = ['in', $orderSnInfo];
        $where['type'] = 'ORIGINAL';
        $where['status'] = 1;
        $mortgageBank = Db::name('order_mortgage')->where($where)->field('order_sn,mortgage_type,organization')->select();
        $returnArr = [];
        foreach ($orderSnInfo as $k => $v) {
            foreach ($mortgageBank as $key => $val) {
                if ($val['order_sn'] == $v) {
                    $bankStr = '';
                    if ($val['mortgage_type'] == 1) {
                        $bankStr = $val['organization'] . '（' . '公积金贷款' . '）';
                    }
                    if ($val['mortgage_type'] == 2) {
                        $bankStr = $val['organization'] . '（' . '商业贷款' . '）';
                    }
                    if ($val['mortgage_type'] == 3) {
                        $bankStr = $val['organization'] . '（' . '装修贷/消费贷' . '）';
                    }
                    $returnArr[$v][] = $bankStr;
                }
            }
        }
        return $returnArr;
    }

    /* @author 赵光帅
     * 银行放款入账流水订单信息
     * @apiParam {string}  order_sn   订单编号
     */

    public static function banksDetail($order_sn)
    {
        $resInfo = self::alias('x')
            ->field('x.order_sn,x.type,z.name,z.deptname,x.finance_sn,n.money,n.loan_money,n.is_loan_finish,n.loan_money_status,n.out_account_total,y.dp_redeem_bank')
            ->join('order_guarantee n', 'x.order_sn = n.order_sn', 'LEFT')
            ->join('system_user z', 'x.financing_manager_id = z.id', 'LEFT')
            ->join('order_dp y', 'x.order_sn = y.order_sn', 'LEFT')
            ->where(['x.order_sn' => $order_sn])
            ->find();
        $resInfo['chuzhang_money'] = $resInfo['out_account_total'];
        $orderTypeArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        if ($resInfo) {
            $resInfo['type'] = $orderTypeArr[$resInfo['type']] ? $orderTypeArr[$resInfo['type']] : '';
        }
        return $resInfo;
    }

    /* @author 赵光帅
     * 发送指令列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function instructionList($map, $page, $pageSize)
    {
        $resInfo = self::alias('x')
            ->field('x.order_sn,x.finance_sn,x.type,z.name,y.estate_name,y.estate_owner,n.instruct_status,n.is_loan_finish,n.money,od.dp_redeem_bank,od.dp_redeem_bank_branch,om.organization')
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn')
            ->join('system_user z', 'x.financing_manager_id=z.id')
            ->join('order_dp od', 'x.order_sn = od.order_sn')
            ->join('order_mortgage om', 'x.order_sn = om.order_sn')
            ->where($map)
            ->order('n.loan_money_time desc')
            ->group('x.order_sn')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        $orderTypeArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        if ($resInfo) {
            foreach ($resInfo['data'] as &$val) {
                $val['type_text'] = $val['type'];
                $val['type'] = $orderTypeArr[$val['type']] ? $orderTypeArr[$val['type']] : '';
                //查询出所有的赎楼银行
                $res = Db::name('order_mortgage')->where(['order_sn' => $val['order_sn'], 'status' => 1, 'type' => 'ORIGINAL'])->field('organization,mortgage_type')->select();
                foreach ($res as $ks => $vs){
                    if($vs['mortgage_type'] == 1){
                        $res[$ks]['mortgage_type_text'] = '公积金贷款';
                    }elseif($vs['mortgage_type'] == 2){
                        $res[$ks]['mortgage_type_text'] = '商业贷款';
                    }else{
                        $res[$ks]['mortgage_type_text'] = '家装/消费贷';
                    }
                }
                $val['mortgage_sum'] = $res;
            }
        }
        return $resInfo;
    }

    /* @author 赵光帅
     * 资料入架列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function dataList($map, $page, $pageSize)
    {
        $resInfo = self::alias('x')
            ->field('x.order_sn,x.type,x.stage,x.create_time,z.name,y.estate_name,y.estate_region,y.estate_owner,n.is_combined_loan,w.id as proc_id')
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn', 'LEFT')
            ->join('system_user z', 'x.financing_manager_id=z.id')
            ->join('workflow_proc w', 'x.order_sn=w.order_sn')
            ->join('workflow_flow wf','w.flow_id = wf.id')
            ->where($map)
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        $orderTypeArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        foreach ($resInfo['data'] as $k => $v) {
            $resInfo['data'][$k]['create_time'] = date('Y-m-d', strtotime($v['create_time']));
            $resInfo['data'][$k]['type_text'] = $v['type'];
            $resInfo['data'][$k]['type'] = $orderTypeArr[$v['type']] ? $orderTypeArr[$v['type']] : '';
            $resInfo['data'][$k]['order_status'] = show_status_name($v['stage'], 'ORDER_JYDB_STATUS');
            unset($resInfo['data'][$k]['stage']);
            $estate_ecity_arr = explode('|', $v['estate_region']);
            if (is_array($estate_ecity_arr)) {
                $resInfo['data'][$k]['estate_ecity_name'] = $estate_ecity_arr[0];
                if (!empty($estate_ecity_arr[1])) {
                    $resInfo['data'][$k]['estate_district_name'] = $estate_ecity_arr[1];
                } else {
                    $resInfo['data'][$k]['estate_district_name'] = '';
                }
            } else {
                $resInfo['data'][$k]['estate_ecity_name'] = '';
                $resInfo['data'][$k]['estate_district_name'] = '';
            }
            unset($resInfo['data'][$k]['estate_region']);
            $organization = OrderComponents::showMortgage($v['order_sn'], 'organization', 'ORIGINAL');
            $resInfo['data'][$k]['organization'] = $organization;
        }
        return $resInfo;
    }

    /* @author 赵光帅
     * 财务结单列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */

    public static function finanStateList($map, $page, $pageSize)
    {
        $resInfo = Db::name('order')->alias('x')
            ->field('x.id,x.order_sn,x.finance_sn,x.stage,x.type,x.order_finish_achievement,x.order_finish_date,x.info_fee_date,x.return_money_finish_date,x.remortgage_date,z.name,z.deptname,y.estate_name,c.cname,n.money,n.guarantee_fee,n.info_fee,n.ac_guarantee_fee,n.ac_guarantee_fee_time,n.ac_exhibition_fee,n.ac_overdue_money,n.ac_transfer_fee')
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn', 'LEFT')
            ->join('customer c', 'x.order_sn=c.order_sn', 'LEFT')
            ->join('system_user z', 'x.financing_manager_id=z.id')
            ->where($map)
            ->order('x.order_finish_date desc')
            ->group('x.order_sn')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        $orderTypeArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        if ($resInfo) {
            foreach ($resInfo['data'] as &$val) {
                $val['type_text'] = $orderTypeArr[$val['type']] ? $orderTypeArr[$val['type']] : '';
                $val['hang_achievement'] = $val['ac_guarantee_fee'] + $val['ac_exhibition_fee'] + $val['ac_overdue_money'] + $val['ac_transfer_fee'] - $val['info_fee'];
                unset($val['ac_exhibition_fee']);
                unset($val['ac_overdue_money']);
                unset($val['ac_transfer_fee']);
                if ($val['stage'] == 1026) {
                    $val['statement_state'] = "待结单";
                } else {
                    $val['statement_state'] = "已结单";
                }
                if ($val['ac_guarantee_fee_time'])
                    $val['ac_guarantee_fee_time'] = date('Y-m-d H:i:s', $val['ac_guarantee_fee_time']);
                //查询出每个订单所有的担保申请人
                $val['customer_sum'] = Db::name('customer')->where(['is_guarantee' => 1, 'order_sn' => $val['order_sn']])->column('cname');
            }
        }
        return $resInfo;
    }

    /**
     * 获取业务类型
     * @return 业务类型
     * @throws \think\db\exception\DataNotFoundException
     * @param $type
     * @author zhongjiaqi 5.18
     */
    public function getType($type = '')
    {
        $types = (new Dictionary())->getValnameByCode('ORDER_TYPE', $type);
        return empty($types) ? '' : $types;
    }

    /**
     * 赎楼正常派单列表
     * @param $where
     * @param $page
     * @param $pageSize
     * @return array
     * @throws \think\exception\DbException
     */
    public static function ransomerList($where = [], $page, $pageSize)
    {
        $where['x.status'] = 1;
        $where['z.is_dispatch'] = 1; //正常派单
        $where['x.is_data_entry'] = 1; //资料已入架
        $where['z.guarantee_fee_status'] = 2; //担保费已收齐
        $where['z.is_loan_finish'] = 1; //银行放款完成或者全部渠道放款完成
        $where['x.stage'] = 1013; //待指派赎楼员
        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        return self::alias('x')
            ->field('x.order_sn,x.financing_manager_id,x.finance_sn,x.type,y.estate_owner,z.is_combined_loan')
            ->join('estate y', 'x.order_sn=y.order_sn', 'left')
            ->join('order_guarantee z', 'x.order_sn=z.order_sn', 'left')
            ->where($where)
            ->where("z.is_need_verify_card=0 or (z.is_need_verify_card=1 and z.is_verify_card=1)")
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
    }

    /**
     * 其他派单列表
     * @param array $where
     * @param $page
     * @param $pageSize
     * @return array
     * @throws \think\exception\DbException
     */
    public static function ortherRansomerList($where = [], $page, $pageSize)
    {
        $where['x.is_data_entry'] = 1; //资料已入架
        $where['z.guarantee_fee_status'] = 2; //担保费已收齐
        $where['z.is_loan_finish'] = 1; //银行放款完成
        $where['z.is_dispatch'] = 2; //其他派单
        $where['x.stage'] = 1013; //待指派赎楼员
        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        return self::alias('x')
            ->field('x.order_sn,x.financing_manager_id,x.finance_sn,x.type,y.estate_owner,z.is_combined_loan')
            ->join('estate y', 'x.order_sn=y.order_sn')
            ->join('order_guarantee z', 'x.order_sn=z.order_sn')
            ->where($where)
            ->where("z.is_need_verify_card=0 or (z.is_need_verify_card=1 and z.is_verify_card=1)")
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
    }

    /**
     * 退回派单列表
     * @param $where
     * @param $page
     * @param $pageSize
     * @return array
     * @throws \think\exception\DbException
     */
    public static function returnRansomerList($where = [], $page, $pageSize)
    {
        $where['a.status'] = 1;
        $where['a.is_dispatch'] = 2;
        $where['x.status'] = 1;
        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        return self::alias('x')
            ->field('x.order_sn,x.financing_manager_id,x.finance_sn,x.type,y.estate_owner,z.is_combined_loan,a.ransom_bank,a.ransomer,a.create_time,a.ransom_type,a.id,a.is_dispatch')
            ->join('estate y', 'x.order_sn=y.order_sn', 'left')
            ->join('order_guarantee z', 'x.order_sn=z.order_sn')
            ->join('order_ransom_dispatch a', 'x.order_sn=a.order_sn')
            ->where($where)
            ->order('x.create_time desc')
            ->group('a.id')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
    }

    /**
     * 权证列表
     */
    public static function orderWarrant($where = [], $page, $pageSize)
    {
        $where['x.status'] = 1;
        $where['y.status'] = 1;
        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        return self::alias('x')
            ->field('x.order_sn,x.type,y.estate_owner,z.is_finish,z.create_time,a.name,a.deptname,z.id')
            ->join('estate y', 'x.order_sn=y.order_sn')
            ->join('order_warrant z', 'x.order_sn=z.order_sn')
            ->join('system_user a ', 'a.id=x.financing_manager_id')
            ->where($where)
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
    }

    /**
     * 权证列表
     */
    public static function orderWarrantRedbook($where = [], $page, $pageSize)
    {
        $where['x.status'] = 1;
        $where['y.status'] = 1;

        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        return self::alias('x')
            ->field('x.order_sn,x.type,y.estate_owner,z.is_finish,z.create_time,a.name,a.deptname,z.id')
            ->join('order_guarantee b', 'x.order_sn=b.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn')
            ->join('order_warrant z', 'x.order_sn=z.order_sn')
            ->join('system_user a ', 'a.id=x.financing_manager_id')
            ->where($where)
            ->where("b.is_instruct=0 or (b.is_instruct=1 and b.instruct_status=3)")//需要发送指令并且指令状态为已发送
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
    }

    /**
     * 订单短期借款列表
     * @param array $where
     * @param $page
     * @param $pageSize
     * @return array
     * @throws \think\exception\DbException
     */
    public static function dqjkList($where = [], $page, $pageSize)
    {
        $search = input('post.search', '', 'trim');
        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        $where['x.status'] = ['in','1,2']; //todo 2可以查看已撤单
        $where['z.status'] = 1;
        $model = self::alias('x')->field('x.order_sn,x.money,x.financing_manager_id,x.type,x.create_time,x.stage,z.turn_into_date,z.turn_back_date,x.is_return_money_finish,x.is_data_entry,z.guarantee_fee_status');
        if (!empty($search)) {
            $where['y.openbank'] = ['like', "%{$search}%"];
            $model->join('order_guarantee_bank y', 'x.order_sn=y.order_sn');
        }

        $model->join('order_guarantee z', 'x.order_sn=z.order_sn');
        $model->where($where);
        $model->order('x.create_time desc');
        return $model->group('x.order_sn')->paginate(['list_rows' => $pageSize, 'page' => $page])->toArray();
    }

    /**
     * 资料送审列表
     * @param array $where
     * @param $page
     * @param $pageSize
     * @return array
     * @throws \think\exception\DbException
     */
    public static function dataSendList($where, $page, $pageSize)
    {
        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        $where['x.status'] = 1;
        $where['y.status'] = 1;
        $where['z.status'] = 1;
//        $where['x.is_data_entry'] = 1; //风控完成
        $where['x.stage'] = array('egt', 1012); //待资料入架就能资料送审  2018.8.21
        return self::alias('x')
            ->field('x.order_sn,x.financing_manager_id,x.type,x.create_time,x.stage,x.status,y.estate_owner,y.estate_region,estate_name,z.fund_channel_name,z.money,z.delivery_status,z.fund_channel_id,z.id,a.name')
            ->join('estate y', 'x.order_sn=y.order_sn', 'left')
            ->join('order_fund_channel z', 'x.order_sn=z.order_sn')
            ->join('system_user a', 'x.financing_manager_id=a.id')
            ->where($where)
            ->where("x.type not in('SQDZ','DQJK')")
            ->order('z.create_time desc')
            ->group('z.id')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
    }

    /**
     * 订单综合列表查询
     * @param array $where
     * @param $page
     * @return array
     * @throws \think\exception\DbException
     */
    public static function totalOrderList($where, $page, $pageSize)
    {
        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        $where['z.status'] = 1;
        $where['x.status'] = ['in','1,2']; //todo 2可以查看已撤单
        return self::alias('x')
            //->field('x.order_sn,x.finance_sn,x.financing_manager_id,x.type,x.create_time,x.stage,x.status,y.estate_region,estate_name,y.estate_owner')
            ->field('x.order_sn,x.finance_sn,x.financing_manager_id,x.type,x.create_time,x.stage,x.status')
            ->join('estate y', 'x.order_sn=y.order_sn', 'left')
            ->join('order_guarantee z', 'x.order_sn=z.order_sn')
            ->where($where)
            //->where( "y.status=1 or y.status is null")
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
    }

    /**
     * 根据订单号查询出房产信息
     * @param array $resultInfo
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getOrderInfo($resultInfo)
    {
        $estatearr = ['estate_region' => "", "estate_name" => "", "estate_owner" => ""];
        foreach ($resultInfo['data'] as $k => $v) {
            if ($v['type'] == "DQJK") {
                $resultInfo['data'][$k] = array_merge($v, $estatearr);
            } else {
                $estateInfo = Db::name("estate")->where(['order_sn' => $v['order_sn'], "estate_usage" => "DB", "status" => 1])->field('estate_region,estate_name,estate_owner')->find();
                if (empty($estateInfo)) {
                    $resultInfo['data'][$k] = array_merge($v, $estatearr);
                } else {
                    $resultInfo['data'][$k] = array_merge($v, $estateInfo);
                }
            }
        }
        return $resultInfo;
    }

    /* @author 
     * 导出发送指令（额度）已发送列表
     * @Param {arr} $map    搜索条件
     * @Param {int} 
     * @Param {int} 
     */

    public static function instructionHasList($map)
    {
        $resInfo = self::alias('x')
            ->field('x.order_sn,x.finance_sn,x.type,z.name,y.estate_name,y.estate_owner,n.instruct_status,n.is_loan_finish,n.money,od.dp_redeem_bank,od.dp_redeem_bank_branch,z.deptname,y.estate_certnum,n.out_account_total,od.dp_redeem_bank,od.dp_redeem_bank_branch')
            ->join('order_guarantee n', 'x.order_sn=n.order_sn')
            ->join('estate y', 'x.order_sn=y.order_sn')
            ->join('system_user z', 'x.financing_manager_id=z.id')
            ->join('order_dp od', 'x.order_sn = od.order_sn')
            ->join('order_mortgage om', 'x.order_sn = om.order_sn', 'left')
            // ->join('customer c', 'x.order_sn=c.order_sn', 'left')
            ->where($map)
            ->order('n.loan_money_time desc')
            ->group('x.order_sn')
            ->select();
        $num = 1;
        $list=array();
        $newTypeArr =  dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_TYPE'));
        $newTypeArr2 =  dictionary_reset((new Dictionary)->getDictionaryByType('MORTGAGE_TYPE'));
        if ($resInfo) {
            foreach ($resInfo as $key=>$val) {
                $list[$key]['nod']=$num;//序号
                $num++;
                $list[$key]['order_sn']="\t".$val['order_sn']."\t";//订单号
                $list[$key]['estate_name']=implode(';',Db::name('estate')->where(['order_sn'=>$val['order_sn']])->column('estate_name'));//房产名称
                $list[$key]['estate_certnum']="\t".implode(';',Db::name('estate')->where(['order_sn'=>$val['order_sn']])->column('estate_certnum'))."\t";//房产证
                $list[$key]['cname']="\t".implode(';',Db::name('customer')->where(['order_sn'=>$val['order_sn'],'is_seller'=>2])->column('cname'))."\t";//卖方
                $list[$key]['ccomborrower']="\t".implode(';',Db::name('customer')->where(['order_sn'=>$val['order_sn'],'is_seller'=>2,'is_comborrower'=>1])->column('cname'))."\t";//卖方共同借款人            
                $list[$key]['sname']="\t".implode(';',Db::name('customer')->where(['order_sn'=>$val['order_sn'],'is_seller'=>1])->column('cname'))."\t";//买方
                $list[$key]['scomborrower']="\t".implode(';',Db::name('customer')->where(['order_sn'=>$val['order_sn'],'is_seller'=>1,'is_comborrower'=>1])->column('cname'))."\t";//买方共同借款人           
                $list[$key]['money']=$val['money'];//担保金额
                $list[$key]['out_account_total']=$val['out_account_total'];//预计出账总计
                $NOWmoney=Db::name('order_mortgage')->field('sum(money)')->where(['order_sn' => $val['order_sn'], 'type' => 'NOW','status'=>1])->find();
                $list[$key]['NOWmoney']=$NOWmoney['sum(money)'];//现按揭总计
                $list[$key]['redeem_bank']=$val['dp_redeem_bank'].'-'.$val['dp_redeem_bank_branch'];//放款银行
                //查询出所有的赎楼银行
                $arr=Db::name('order_mortgage')->where(['order_sn'=>$val['order_sn'],'status' => 1, 'type' => 'ORIGINAL'])->column('organization');
                //赎楼银行    
                $mortgage= Db::name('order_mortgage')->field('organization,mortgage_type')->where(['order_sn' => $val['order_sn'], 'status' => 1, 'type' => 'ORIGINAL'])->select();
                $val['mortgage_sum']='';
                foreach($mortgage as $k=>$v){
                    $val['mortgage_sum'].=$v['organization'].'('.$newTypeArr[$v['mortgage_type']].')'.';';                  
                }   
                $list[$key]['mortgage_sum']=$val['mortgage_sum'];
                $list[$key]['deptname']=$val['deptname'];//部门
                $list[$key]['name']=$val['name'];//理财经理
                $list[$key]['instruct_status']=$val['instruct_status']==3?'已发送':'';//指令状态
            }
        }
        return $list;
    }

    /* @author 
     * 导出综合信息查询
     * @Param {arr} $where    搜索条件
     * @Param {int} 
     * @Param {int} 
     */
    public static function allOrderList2($where)
    {
        $where['z.status'] = 1;
        $where['x.status'] = ['in','1,2']; //todo 2可以查看已撤单
        return self::alias('x')
            //->field('x.order_sn,x.finance_sn,x.financing_manager_id,x.type,x.create_time,x.stage,x.status,y.estate_region,estate_name,y.estate_owner')
            ->field('x.order_sn,x.finance_sn,x.financing_manager_id,x.type,x.create_time,x.stage,x.status,z.evaluation_price,z.money,z.guarantee_fee,z.info_fee,z.fee,z.ac_guarantee_fee,z.ac_fee,z.ac_overdue_money,z.ac_exhibition_fee,z.ac_transfer_fee,x.hang_achievement,x.order_finish_achievement,z.notarization,x.order_finish_date,t.is_normal,t.risk_rating,t.review_rating,x.inspector_id,x.order_source,x.source_info')
            ->join('estate y', 'x.order_sn=y.order_sn')
            ->join('order_guarantee z', 'x.order_sn=z.order_sn')
            ->join('trial_first t', 'x.order_sn=t.order_sn', 'left')
            ->where($where)
            ->order('x.create_time desc')
            ->group('x.order_sn')
            ->select();
    }
    
    /**
     * 根据订单号查询出房产信息2
     * @param array $resultInfo
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getOrderInfo2($resultInfo)
    {
        $estatearr = ['estate_region' => "", "estate_name" => "", "estate_owner" => "","estate_certnum"=>""];
        //$resultInfo=json_decode($resultInfo,true);
        foreach ($resultInfo as $k => $v) {
            $v=json_decode($v,true);
            if ($v['type'] == "DQJK") {
                $resultInfo[$k] = array_merge($v, $estatearr);
            } else {
                $estateInfo = Db::name("estate")->where(['order_sn' => $v['order_sn'], "estate_usage" => "DB", "status" => 1])->field('estate_region,estate_name,estate_owner,estate_certnum')->find();
                 if (empty($estateInfo)) {
                     $resultInfo[$k] = array_merge($v, $estatearr);
                 } else {
                    $resultInfo[$k] = array_merge($v, $estateInfo);
                }
            }
        }
        return $resultInfo;
    }


    /**
     * 订单综合列表查询
     * @param array $where
     * @param $page
     * @return array
     * @throws \think\exception\DbException
     */
    public static function allApplicationlist($where, $page, $pageSize)
    {
        $field = 'a.inspector_id,a.id,a.order_sn,a.create_time,a.type,a.money,a.stage,b.estate_name,b.estate_region,c.name,a.allot_time,a.guarantee_letter_outtime,z.is_normal';
        !$pageSize && $pageSize = config('apiBusiness.ADMIN_LIST_DEFAULT');
        $res =  self::alias('a')
            ->field($field)
            ->join('estate b', 'a.order_sn=b.order_sn', 'left')
            ->join('trial_first z', 'a.order_sn=z.order_sn', 'left')
            ->join('system_user c','a.financing_manager_id = c.id')
            ->where($where)
            ->order('a.create_time desc')
            ->group('a.order_sn')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();

        //将订单状态和订单类型更改为中文
        $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_JYDB_STATUS'));
        //$newTypeArr =  dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        //到初审信息表去查询该订单是否是正常单
        foreach ($res['data'] as $k => $v){
            $ectryDistrict = explode('|',$v['estate_region']);
            $res['data'][$k]['estate_ecity'] = $ectryDistrict[0];
            if(isset($ectryDistrict[1])){
                $res['data'][$k]['estate_district'] = $ectryDistrict[1];
            }else{
                $res['data'][$k]['estate_district'] = '';
            }

            $res['data'][$k]['order_type'] = $v['type'];
            $res['data'][$k]['stage'] = $newStageArr[$v['stage']] ? $newStageArr[$v['stage']]:'';
            //unset($res['data'][$k]['type']);
            //查询出改订单所有的房产
            $res['data'][$k]['sumname'] = Db::name('estate')->where(['order_sn' => $v['order_sn'],'status' => 1,'delete_time' => null,'estate_usage' => 'DB'])->column('estate_name');
            //时间只需要年月日
            $res['data'][$k]['create_time'] = substr($v['create_time'],0,10);
            //查询出审查员
            $res['data'][$k]['inspector_name'] = Db::name('system_user')->where(['id' => $v['inspector_id'],'status' => 1])->value('name');
        }
        return $res;

    }

    /**
     * 根据订单号查询出房产信息
     * @param array $resultInfo
     * @return array
     * @throws \think\exception\DbException
     */
    public static function getoldOrderInfo($resultInfo)
    {
        $estatearr = ['estate_district' => "", "estate_name" => "", "estate_ecity" => ""];
        foreach ($resultInfo['data'] as $k => $v) {
            if ($v['type'] == "DQJK") {
                $resultInfo['data'][$k] = array_merge($v, $estatearr);
            } else {
                $estateInfo = Db::name("estate")->where(['order_sn' => $v['order_sn'], "estate_usage" => "DB", "status" => 1])->field('estate_region,estate_name')->find();
                if (empty($estateInfo)) {
                    $resultInfo['data'][$k] = array_merge($v, $estatearr);
                } else {
                    $ectryDistrict = explode('|',$estateInfo['estate_region']);
                    $oldarr['estate_name'] = $estateInfo['estate_name'];
                    $arrInfo = array_merge($oldarr,$ectryDistrict);
                    $resultInfo['data'][$k] = array_merge($v, $arrInfo);
                }
            }
        }
        return $resultInfo;
    }




}
