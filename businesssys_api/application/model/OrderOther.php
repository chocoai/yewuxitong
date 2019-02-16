<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/8/15
 * Time: 16:38
 */

namespace app\model;


use think\Db;
use app\model\Order;
use app\util\OrderComponents;

class OrderOther extends Base
{

    protected $dateFormat = 'Y-m-d';
    protected $autoWriteTimestamp = true;

    // 关联模型
    public function otherExtend()
    {
        $this->hasOne('OrderOtherExhibition', 'order_other_id');
    }

    public function otherDiscounts()
    {
        return $this->hasMany('OrderOtherDiscount','order_other_id')->field('id,order_other_id,old_rate,old_money,new_rate,new_money,order_advance_money_id');
    }

    /**折扣申请列表
     * @param $where
     * @param $paginate
     * @return array
     * @throws \think\exception\DbException
     * @author: bordon
     */
    public static function discountApplyList($where, $paginate)
    {
        $field = 'or.id as other_id,or.process_sn,o.order_sn,o.finance_sn,e.estate_name,e.estate_owner,z.name,or.create_time,or.stage,o.type';
        $result = self::alias('or')
            ->join('order o', 'o.order_sn = or.order_sn')
            ->join('estate e', 'e.order_sn = or.order_sn', 'left')
            ->join('system_user z ', 'z.id=o.financing_manager_id ')
            ->where($where)
            ->field($field)
            ->order('or.create_time desc')
            ->group('or.id')
            ->paginate($paginate)
            ->each(function ($item, $key) {
                $item->stage_text = self::getType($item->stage);
            })
            ->toArray();
        return $result;
    }

    /**
     * 信息费支付列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */
    public static function infoPayList($map, $field, $page, $pageSize)
    {
        $resInfo = self::alias('x')
            ->field($field)
            ->join('order o', 'x.order_sn = o.order_sn')
            ->join('estate y', 'x.order_sn = y.order_sn', 'left')
            ->join('system_user z ', 'x.create_uid = z.id')
            ->where($map)
            ->order('x.create_time desc')
            ->group('x.id')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
        foreach ($resInfo['data'] as $key => $value) {
            //时间年月日
            $resInfo['data'][$key]['create_time'] = date('Y-m-d', strtotime($value['create_time']));
            $resInfo['data'][$key]['type_text'] = (new order())->getType($value['type']); //订单类型
            $resInfo['data'][$key]['stage_text'] = self::getType($value['stage']); //订单状态
        }
        return $resInfo;
    }

    /**
     * 其他退费申请列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */
    public static function otherRefundList($map, $field, $page, $pageSize)
    {
        $resInfo = self::alias('x')
            ->field($field)
            ->join('order o', 'x.order_sn = o.order_sn')
            ->join('estate y', 'x.order_sn = y.order_sn', 'left')
            ->join('system_user z ', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('x.create_time desc')
            ->group('x.id')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
        foreach ($resInfo['data'] as $key => $value) {
            //时间年月日
            $resInfo['data'][$key]['create_time'] = date('Y-m-d', strtotime($value['create_time']));
            $resInfo['data'][$key]['process_type_text'] = self::getProcessTypeStr($value['process_type']); //订单类型
            $resInfo['data'][$key]['stage_text'] = self::getType($value['stage']); //订单状态
            $resInfo['data'][$key]['process_type_code'] = self::getProcessTypecodew($value['process_type']); //订单状态
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
    public static function getType($type = '')
    {
        $types = (new Dictionary())->getValnameByCode('OTHER_BUS_STATUS', $type);
        return empty($types) ? '' : $types;
    }

    /**
     * 获取订单基本信息
     * @Param {arr} $process_type   费用申请类型
     */
    public static function getProcessTypeStr($process_type)
    {
        switch ($process_type) {
            case 'SQ_TRANSFER' :
                return '首期款转账';
                break;
            case 'DEPOSIT' :
                return '退保证金';
                break;
            case 'XJ_GUARANTEE_FEE' :
                return '现金单退担保费';
                break;
            case 'ED_GUARANTEE_FEE' :
                return '额度单退担保费';
                break;
            case 'EXHIBITION' :
                return '展期申请';
                break;
            case 'IMPORTANT_MATTER' :
                return '要事审批申请';
                break;
            case 'CANCEL_ORDER_REFUND' :
                return '退担保费';
                break;
            case 'CANCEL_ORDER_NOREFUND' :
                return '不退保费';
                break;
            case 'CANCEL_ORDER_PREMIUM' :
                return '保费调整';
                break;
            default:
                return '';
        }

    }

    /**
     * 获取订单基本信息
     * @Param {arr} $process_type   费用申请类型
     * @Param {int} $order_sn    订单编号
     */
    public static function getProcessTypecodew($process_type)
    {
        switch ($process_type) {
            case 'SQ_TRANSFER' :
                return 2;
                break;
            case 'DEPOSIT' :
                return 3;
                break;
            case 'XJ_GUARANTEE_FEE' :
                return 4;
                break;
            case 'ED_GUARANTEE_FEE' :
                return 5;
                break;
            case 'ED_TAIL' :
                return 6;
                break;
            case 'XJ_TAIL' :
                return 7;
                break;
            default:
                return '';
        }

    }

    /**
     * 获取账户信息
     * @Param {int} $id    其他业务表主键id
     */
    public function getAccountsInfo($id)
    {
        return Db::name('order_other_account')->where(['order_other_id' => $id, 'status' => 1])->field('id,bank_account,account_type,account_source,bank_card,bank,bank_branch,money,exhibition_fee,actual_payment,expense_taxation')->select();
    }

    /**
     * 获取申请信息
     * @Param {int} $id    其他业务表主键id
     */
    public function getApplyInfo($id)
    {
        $field = 'oo.id,oo.process_sn,oo.order_type,oo.loan_way,oo.transfer_type,oo.info_fee_rate,oo.info_fee,oo.collector,
        oo.mobile,oo.reason,oo.return_money,oo.total_money,oo.money,oo.stage,oo.default_interest,oo.short_loan,oe.exhibition_rate,oe.exhibition_starttime,oe.exhibition_endtime,
        oe.exhibition_day,oe.exhibition_fee,oe.exhibition_guarantee_fee,oe.exhibition_info_fee';
        $applyInfo = Db::name('order_other')->alias('oo')->where(['oo.id' => $id])
            ->join('order_other_exhibition oe', 'oo.id = oe.order_other_id', 'left')
            ->field($field)->find();
        $applyInfo['attachment'] = Db::name('order_other_attachment')->alias('ooa')
            ->join('attachment a', 'ooa.attachment_id = a.id')
            ->field('a.id,a.url,a.name,a.thum1,a.ext')
            ->where(['ooa.order_other_id' => $id, 'status' => 1])
            ->select();
        foreach ($applyInfo['attachment'] as $k => $v) {
            $applyInfo['attachment'][$k]['url'] = config('uploadFile.url') . $v['url'];
        }
        return $applyInfo;
    }


    /**
     * 获取订单基本信息
     * @Param {arr} $process_type   费用申请类型
     * @Param {int} $order_sn    订单编号
     */
    public function getOrderInfo($process_type, $order_sn)
    {
        switch ($process_type) {
            case 'INFO_FEE' :
                return self::getXXF($order_sn);
                break;
            case 'SQ_TRANSFER' :
                return self::getSQ($order_sn);
                break;
            case 'DEPOSIT' :
                return self::getBZJ($order_sn);
                break;
            case 'XJ_GUARANTEE_FEE' :
                return self::getATDBF($order_sn);
                break;
            case 'ED_GUARANTEE_FEE' :
                return self::getEDDBF($order_sn);
                break;
            case 'ED_TAIL' :
                return self::getEDFWK($order_sn);
                break;
            case 'XJ_TAIL' :
                return self::getEDFWK($order_sn);
                break;
            case 'EXHIBITION' :
                return self::getZQSQ($order_sn);
                break;
            case 'CANCEL_ORDER_REFUND' :
                return self::getCORSQ($order_sn);
                break;
            default:
                return '';
        }
    }

    /*
    * 获取撤单申请订单基本信息
    * */
    private function getCORSQ($order_sn)
    {
        $orderInfo = Db::name('order')->alias('o')
            ->join('system_user su', 'o.financing_manager_id = su.id')
            ->join('system_dept sd', 'o.financing_dept_id = sd.id')
            ->join('order_guarantee og', 'o.order_sn = og.order_sn')
            ->field('o.order_sn,o.finance_sn,o.financing_dept_id,o.stage,su.name,sd.name sname,su.mobile financing_mobile,og.money')
            ->where(['o.order_sn' => $order_sn, 'o.status' => 1])
            ->find();
        //获取理财经理部门经理
        $userModel = new SystemUser();
        $deptInfo = $userModel->where(['deptid' => $orderInfo['financing_dept_id'], 'status' => 1, 'ranking' => '经理'])->field('name,mobile')->find();
        $orderInfo['stage_str'] = show_status_name($orderInfo['stage'], 'ORDER_JYDB_STATUS');
        $orderInfo['dept_manager_name'] = $deptInfo['name'];
        $orderInfo['dept_mobile'] = $deptInfo['mobile'];
        $orderInfo['estateinfo'] = $this->getEstates($order_sn)['estateNameStr'];
        return $orderInfo;

    }

    /*
    * 获取额度放尾款订单基本信息
    * */
    private function getEDFWK($order_sn)
    {
        $orderInfo = Db::name('order')->alias('o')
            ->join('system_user su', 'o.financing_manager_id = su.id')
            ->join('system_dept sd', 'o.financing_dept_id = sd.id')
            ->field('o.order_sn,o.finance_sn,su.name,sd.name sname')
            ->where(['o.order_sn' => $order_sn, 'o.status' => 1])
            ->find();
        $orderInfo['seller'] = implode(',', Db::name('customer')->where(['order_sn' => $order_sn, 'is_seller' => 2, 'status' => 1])->column('cname'));
        $orderInfo['buyer'] = implode(',', Db::name('customer')->where(['order_sn' => $order_sn, 'is_seller' => 1, 'status' => 1])->column('cname'));
        return $orderInfo;

    }

    /*
     * 获取按天退担保费订单基本信息
     * */
    private function getZQSQ($order_sn)
    {
        $orderInfo = Db::name('order')->alias('o')
            ->join('system_user su', 'o.financing_manager_id = su.id')
            ->join('system_dept sd', 'o.financing_dept_id = sd.id')
            ->field('o.order_sn,o.finance_sn,su.name,sd.name sname,o.financing_dept_id')
            ->where(['o.order_sn' => $order_sn, 'o.status' => 1])
            ->find();
        //获取理财经理部门经理
        $userModel = new SystemUser();
        $orderInfo['dept_manager_name'] = $userModel->where(['deptid' => $orderInfo['financing_dept_id'], 'status' => 1, 'ranking' => '经理'])->value('name');
        $orderInfo['estateinfo'] = $this->getEstates($order_sn)['estateNameStr'];
        return $orderInfo;

    }

    /*
     * 获取按天退担保费订单基本信息
     * */
    private function getEDDBF($order_sn)
    {
        $orderInfo = Db::name('order')->alias('o')
            ->join('order_guarantee og', 'o.order_sn = og.order_sn')
            ->join('system_user su', 'o.financing_manager_id = su.id')
            ->join('system_dept sd', 'o.financing_dept_id = sd.id')
            ->field('o.order_sn,o.finance_sn,o.financing_dept_id,og.money,og.guarantee_rate,og.guarantee_fee,og.ac_guarantee_fee,og.ac_fee,su.name,sd.name sname')
            ->where(['o.order_sn' => $order_sn, 'o.status' => 1])
            ->find();
        //获取理财经理部门经理
        $userModel = new SystemUser();
        $orderInfo['dept_manager_name'] = $userModel->where(['deptid' => $orderInfo['financing_dept_id'], 'status' => 1, 'ranking' => '经理'])->value('name');
        $orderInfo['paymatters'] = '额度单退担保费';
        $orderInfo['estateinfo'] = $this->getEstates($order_sn)['estateNameStr'];
        $orderInfo['estateOwner'] = $this->getEstates($order_sn)['estateOwnerStr'];
        return $orderInfo;

    }

    /*
     * 获取按天退担保费订单基本信息
     * */
    private function getATDBF($order_sn)
    {
        $orderInfo = Db::name('order')->alias('o')
            ->join('order_guarantee og', 'o.order_sn = og.order_sn')
            ->join('system_user su', 'o.financing_manager_id = su.id')
            ->join('system_dept sd', 'o.financing_dept_id = sd.id')
            ->field('o.order_sn,o.finance_sn,o.financing_dept_id,og.ac_guarantee_fee,og.guarantee_fee,og.ac_fee,og.ac_exhibition_fee,og.ac_overdue_money,og.info_fee,su.name,sd.name sname')
            ->where(['o.order_sn' => $order_sn, 'o.status' => 1])
            ->find();
        //获取理财经理部门经理
        $userModel = new SystemUser();
        $orderInfo['dept_manager_name'] = $userModel->where(['deptid' => $orderInfo['financing_dept_id'], 'status' => 1, 'ranking' => '经理'])->value('name');
        $orderInfo['paymatters'] = '现金单退担保费';
        $orderInfo['estateinfo'] = $this->getEstates($order_sn)['estateNameStr'];
        $orderInfo['estateOwner'] = $this->getEstates($order_sn)['estateOwnerStr'];
        return $orderInfo;

    }

    /*
     * 获取退保证金订单基本信息
     * */
    private function getBZJ($order_sn)
    {
        $orderInfo = Db::name('order')->alias('o')
            ->join('order_guarantee og', 'o.order_sn = og.order_sn')
            ->join('system_user su', 'o.financing_manager_id = su.id')
            ->join('system_dept sd', 'o.financing_dept_id = sd.id')
            ->field('o.order_sn,o.finance_sn,og.ac_deposit,og.guarantee_fee,su.name,sd.name sname')
            ->where(['o.order_sn' => $order_sn, 'o.status' => 1])
            ->find();
        $orderInfo['paymatters'] = '退保证金';
        $orderInfo['estateOwner'] = $this->getEstates($order_sn)['estateOwnerStr'];
        return $orderInfo;

    }

    /*
     * 获取首期转账订单基本信息
     * */
    private function getSQ($order_sn)
    {
        $orderInfo = Db::name('order')->alias('o')
            ->join('order_guarantee og', 'o.order_sn = og.order_sn')
            ->join('system_user su', 'o.financing_manager_id = su.id')
            ->join('system_dept sd', 'o.financing_dept_id = sd.id')
            ->field('o.order_sn,o.finance_sn,og.money,og.guarantee_fee,su.name,sd.name sname')
            ->where(['o.order_sn' => $order_sn, 'o.status' => 1])
            ->find();
        $orderInfo['paymatters'] = '首期款转账';
        $orderInfo['estateinfo'] = $this->getEstates($order_sn)['estateNameStr'];
        $orderInfo['estateOwner'] = $this->getEstates($order_sn)['estateOwnerStr'];
        //获取所有的关联的订单
        $orderInfo['associated'] = $this->getAssociatedOrder($order_sn);
        return $orderInfo;

    }

    /*
     * 获取信息费订单基本信息
     * */
    private function getXXF($order_sn)
    {
        $orderInfo = Db::name('order')->alias('o')
            ->join('order_guarantee og', 'o.order_sn = og.order_sn')
            ->join('system_user su', 'o.financing_manager_id = su.id')
            ->join('system_dept sd', 'o.financing_dept_id = sd.id')
            ->field('o.order_sn,o.finance_sn,og.money,og.ac_guarantee_fee,og.guarantee_fee,og.info_fee,o.order_source,o.source_info,su.name,sd.name sname')
            ->where(['o.order_sn' => $order_sn, 'o.status' => 1])
            ->find();
        $orderInfo['estateinfo'] = $this->getEstates($order_sn)['estateNameStr'];
        $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_YWLY'));
        $orderInfo['order_source_str'] = !empty($orderInfo['order_source']) ? $newStageArr[$orderInfo['order_source']] : '';
        $orderInfo['costomerinfo'] = $this->getCustomer($order_sn);
        return $orderInfo;

    }


    /*
     * 根据房产名称获取所有的关联单
     * */
    private function getAssociatedOrder($order_sn)
    {
        $estaterarrInfo = $this->getEstates($order_sn);
        $estaterarr = $estaterarrInfo['estatearr'];
        $estateownerarr = $estaterarrInfo['estateownerarr'];
        $orderSnStr = '';
        if (!empty($estaterarr[0])) {
            foreach ($estaterarr as $k => $v) {
                $map['e.estate_name'] = ['like', $v['estate_name']];
                //$map['o.stage'] = ['not in','1021,1023'];
                $map['e.order_sn'] = ['<>', $order_sn];
                $orderSnInfo = Db::name('estate')->alias('e')
                    ->join('order o', 'e.order_sn = o.order_sn')
                    ->where($map)
                    ->group('e.order_sn')
                    ->column('e.order_sn');
                $str = join(',', $orderSnInfo);
                $orderSnStr .= $str . ',';
            }

        }

        if (!empty($estateownerarr[0])) {
            foreach ($estateownerarr as $k => $v) {
                $map['e.estate_owner'] = ['like', $v];
                //$map['o.stage'] = ['not in','1021,1023'];
                $map['e.order_sn'] = ['<>', $order_sn];
                $orderSnInfo = Db::name('estate')->alias('e')
                    ->join('order o', 'e.order_sn = o.order_sn')
                    ->where($map)
                    ->group('e.order_sn')
                    ->column('e.order_sn');
                $strold = join(',', $orderSnInfo);
                $orderSnStr .= $strold . ',';
            }

        }
        return rtrim($orderSnStr, ',');


    }


    /*
     * 查询出所有的房产名称
     * */
    private function getEstates($order_sn)
    {
        $resInfo = Db::name('estate')->where(['order_sn' => $order_sn, 'status' => 1, 'estate_usage' => 'DB'])->field('estate_name')->select();
        $returnInfo['estatearr'] = $resInfo;
        $returnInfo['estateownerarr'] = Db::name('customer')->where(['order_sn' => $order_sn, 'is_seller' => 2, 'status' => 1])->column('cname');
        $estateNameStr = '';
        foreach ($resInfo as $k => $v) {
            $estateNameStr .= $v['estate_name'] . ',';
        }
        $returnInfo['estateNameStr'] = rtrim($estateNameStr, ',');
        $returnInfo['estateOwnerStr'] = implode('，', $returnInfo['estateownerarr']); //业主姓名
        return $returnInfo;
    }

    /*
     * 查询出所有的担保申请人
     * */
    private function getCustomer($order_sn)
    {
        $customerInfo = Db::name('customer')->where(['order_sn' => $order_sn, 'status' => 1])->column('cname');
        return join(',', $customerInfo);
    }

    /*
     * 查询出所有的审批记录
     * */
    public function getApprovalRecords($order_sn, $process_type, $id)
    {
        $jlField = 'wp.order_sn,wp.create_time,wp.process_name,wp.auditor_name,wp.auditor_dept,wp.status_desc status,wp.content';
        //查询出审批记录
        $appMap['wp.order_sn'] = $order_sn;
        $appMap['wp.is_deleted'] = 1;
        $appMap['wp.status'] = ['in', '-1,9'];
        if($process_type == 'CANCEL_ORDER'){
            $appMap['wf.flow_type'] = 'canel_order';
        }else{
            $appMap['wf.flow_type'] = 'other_refund';
        }

        $appMap['wf.type'] = $process_type;
        $appMap['we.mid'] = $id;
        $appInfo = Db::name('workflow_proc')->alias('wp')
            ->join('workflow_flow wf', 'wp.flow_id = wf.id')
            ->join('workflow_entry we', 'we.id = wp.entry_id')
            ->where($appMap)
            ->field($jlField)
            //->fetchSql()
            ->select();
        foreach ($appInfo as $k => $v) {
            $appInfo[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
        }
        return $appInfo;
    }

    /* @author 赵光帅
     * 信息费审批列表查询
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function costList($map, $field, $page, $pageSize)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field($field)
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('system_user z', 'x.create_uid = z.id')
            ->where($map)
            ->order('d.status asc')
            ->order('d.create_time asc')
            ->group('x.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();

        foreach ($res['data'] as $k => $v) {
            $res['data'][$k]['create_time'] = date('Y-m-d', $v['create_time']);
            $res['data'][$k]['type_text'] = (new order())->getType($v['type']); //订单类型
            $res['data'][$k]['stage_text'] = self::getType($v['stage']); //订单状态
        }
        return $res;
    }


    /* @author 赵光帅
     * 其他费用审批列表查询
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function otherApprovalList($map, $field, $page, $pageSize)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field($field)
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('system_user z', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('d.status asc')
            ->order('d.create_time asc')
            ->group('x.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        foreach ($res['data'] as $k => $v) {
            $res['data'][$k]['create_time'] = date('Y-m-d', $v['create_time']);
            $res['data'][$k]['process_type_text'] = self::getProcessTypeStr($v['process_type']); //订单类型
            $res['data'][$k]['stage_text'] = self::getType($v['stage']); //订单状态
            $res['data'][$k]['process_type_code'] = self::getProcessTypecodew($v['process_type']); //订单状态
        }
        return $res;
    }

    /**获取展期数据列表
     * @param array $where 查询条件
     * @param bool $filed 查询字段
     * @param array $paginate 分页参数
     * @return array
     * @author: bordon
     */
    public static function getExtenList($where = [], $field = true, $paginate = [])
    {
        $list = self::alias('ot')
            ->join('order_other_exhibition oe', 'oe.order_other_id = ot.id')
            ->join('system_user su', 'su.id = ot.create_uid')
            ->join('order_guarantee og', 'og.order_sn = ot.order_sn')
            ->where($where)
            ->field($field)
            ->order('ot.create_time desc')
            ->paginate($paginate)
            ->each(function ($item, $key) {
                $item->money = $item->money + $item->exhibition_guarantee_fee + $item->exhibition_info_fee;
                $where = [
                    'order_sn' => $item->order_sn,
                    'type' => 2,
                    'status' => 1,
                    'create_uid' => -1
                ];
                $item->total_money = OrderCollectFee::where($where)
                    ->whereTime('cal_date', 'between', [$item->exhibition_starttime, $item->actual_exhibition_endtime ? $item->actual_exhibition_endtime : $item->exhibition_endtime])
                    ->sum('money');  // 展期开始时间-展期结束时间
            })
            ->toArray();
        return $list;
    }

    /**获取展期汇总数据
     * @param array $where 查询条件
     * @param bool $filed 查询字段
     * @return array
     * @author: bordon
     */
    public static function getExtenInfo($where = [])
    {
        $list = self::alias('ot')
            ->join('order_other_exhibition oe', 'oe.order_other_id = ot.id')
            ->where($where)
            ->field('count(*) as exten_time,sum(oe.actual_exhibition_day) as exten_days,sum(ot.total_money) as exten_total_money,sum(ot.money) as exten_receive_money,oe.exhibition_rate')
            ->select();
        return $list;
    }


    /**获取展期申请列表
     * @param array $where 查询条件
     * @param bool $filed 查询字段
     * @param array $paginate 分页参数
     * @return array
     * @author: 赵光帅
     */
    public static function rollList($where = [], $field = true, $paginate = [])
    {
        $list = self::alias('x')
            ->join('order_other_exhibition oe', 'oe.order_other_id = x.id')
            ->join('estate y', 'x.order_sn = y.order_sn', 'left')
            ->join('order o', 'o.order_sn = x.order_sn')
            ->join('system_user z', 'z.id = o.financing_manager_id')
            ->where($where)
            ->field($field)
            ->group('x.id')
            ->order('x.create_time desc')
            ->paginate($paginate)
            ->toArray();
        foreach ($list['data'] as $key => $value) {
            //时间年月日
            $list['data'][$key]['create_time'] = date('Y-m-d', strtotime($value['create_time']));
            $list['data'][$key]['stage_text'] = self::getType($value['stage']); //订单状态
        }
        return $list;
    }

    /* @author 赵光帅
     * 展期费审批列表查询
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function renewalAppList($map, $field, $page, $pageSize)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field($field)
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('order_other_exhibition oe', 'oe.order_other_id = x.id')
            ->join('system_user z', 'z.id = o.financing_manager_id')
            ->where($map)
            ->order('d.id desc')
            ->group('x.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();

        foreach ($res['data'] as $k => $v) {
            $res['data'][$k]['create_time'] = date('Y-m-d', $v['create_time']);
            $res['data'][$k]['stage_text'] = self::getType($v['stage']); //订单状态
        }
        return $res;
    }

    /* @author 赵光帅
     * 展期费审批列表查询
     *
     * @Param {string} $order_sn    订单号
     *
     * */

    public function costInformation($order_sn)
    {
        $otherInfo = Db::name('order_other')->alias('oo')
            ->join('order_other_exhibition oe', 'oe.order_other_id = oo.id')
            ->where(['order_sn' => $order_sn, 'process_type' => 'EXHIBITION', 'status' => 1, 'stage' => 308])
            ->field('sum(exhibition_day) sumexhibition_day,sum(exhibition_fee) sumexhibition_fee,count(*) sumperiods')->find();  //已展期总天数  已收展期费
        if (empty($otherInfo['sumexhibition_day'])) $otherInfo['sumexhibition_day'] = 0;
        //实收预收担保费
        $guaranteeInfo = Db::name('order_guarantee')->where(['order_sn' => $order_sn, 'status' => 1])->field('guarantee_fee,info_fee,ac_guarantee_fee,ac_overdue_money')->find();
        //根据订单编号查询出展期费率
        $guaranteeInfo['exhibition_rate'] = Db::name('order_advance_money')->where(['order_sn' => $order_sn, 'status' => 1])->value('advance_rate');
        //逾期天数
        $yuQiMoney = Db::name('order_collect_fee')->where(['order_sn' => $order_sn, 'type' => '3', 'status' => 1])->field('sum(money) yuqimoney,count(*) yuqiday')->find();  //应收逾期费
        //剩余展期费
        $returnInfo = array_merge($otherInfo, $guaranteeInfo, $yuQiMoney);

        return $returnInfo;
    }

    /* @author 赵光帅
     * 获取沟通记录
     * @Param {string} $process_sn   流程编号
     * */

    public function getCommunicate($process_sn)
    {
        $cateInfo = Db::name('order_communicate')->alias('oc')->where(['sn' => $process_sn, 'status' => 1])->field('id,initiate_time,node,initiator,content')->select();

        foreach ($cateInfo as $k => $v) {
            $cateInfo[$k]['initiate_time'] = date('Y-m-d H:i', $v['initiate_time']);
            $goutongType = Db::name('order_communicate_reply')->where(['order_communicate_id' => $v['id'], 'status' => 1])->column('user_name'); //沟通类型
            $cateInfo[$k]['communicationtype'] = '沟通：' . join(',', $goutongType);
            $replyInfo = Db::name('order_communicate_reply')->where(['order_communicate_id' => $v['id'], 'status' => 1, 'content' => ['<>', '']])->field('user_name,content,reply_time')->select(); //沟通类型
            $resInfo['initiate_time'] = date('Y-m-d H:i', $v['initiate_time']);
            $resInfo['node'] = '沟通回复';
            $resInfo['communicationtype'] = '回复:' . $v['initiator'];
            $initiator = '';
            $arr = [];
            foreach ($replyInfo as $key => $val) {
                $initiator .= $val['user_name'] . ',';
                $val['reply_time'] = date('Y-m-d H:i', $val['reply_time']);
                $arr[] = $val;
            }
            $resInfo['content'] = $arr;
            $resInfo['initiator'] = rtrim($initiator, ',');
            if (isset($arr) && !empty($arr)) {
                $cateInfo[$k]['replyinfo'] = $resInfo;
            }
        }

        $returnInfo = [];
        foreach ($cateInfo as $k => $v) {
            $returnInfo[] = $v;
            if (isset($v['replyinfo']['content'])) {
                $returnInfo[] = $v['replyinfo'];
            }
        }

        foreach ($returnInfo as $k => $v) {
            if (isset($returnInfo[$k]['replyinfo'])) {
                unset($returnInfo[$k]['replyinfo']);
            }
        }
        return $returnInfo;

    }

    /*
     * 判断是否需要显示该审批
     * @param int $user_id 用户id
     * @param int $stage 申请状态
     * @param string $process_type 申请类型
     * @return int  1需要显示审批  2不需要显示审批
     * @author: 赵光帅
     * */
    /*public function isShowApproval($user_id, $stage, $process_type)
    {
        $groupid = Db::name('system_auth_group_access')->where(['uid' => $user_id])->value('groupid');
        if (empty($groupid)) return 2;
        $map['id'] = ['in', $groupid];
        $map['status'] = 1;
        $signArr = Db::name('system_auth_group')->where($map)->column('sign');
        if (empty($signArr)) return 2;

        return $this->getShowApp($process_type, $stage, $signArr);

    }*/

    /*
     * 判断是否需要显示该审批
     * @param int $user_id 用户id
     * @param int $stage 申请状态
     * @param string $process_type 申请类型
     * @return int  1需要显示审批  2不需要显示审批
     * @author: 赵光帅
     * */
    public function isShowApproval($user_id, $otherInfo, $id)
    {
        if($otherInfo['process_type'] == 'ED_TAIL' || $otherInfo['process_type'] == 'XJ_TAIL' ){
           $map['type'] = 'ED_TAIL';
        }elseif (in_array($otherInfo['process_type'],['CANCEL_ORDER_REFUND','CANCEL_ORDER_NOREFUND','CANCEL_ORDER_PREMIUM'])){
            $map['type'] = 'CANCEL_ORDER';
        }else{
            $map['type'] = $otherInfo['process_type'];
        }
        $map['status'] = 1;
        $map['delete_time'] = null;
        //查询出flowid
        $flowId = Db::name('workflow_flow')->where($map)->value('id');

        $entryMap['mid'] = $id;
        $entryMap['order_sn'] = $otherInfo['order_sn'];
        $entryMap['flow_id'] = $flowId;
        //查询出entryid
        $entryId = Db::name('workflow_entry')->where($entryMap)->value('id');

        $procMap['flow_id'] = $flowId;
        $procMap['entry_id'] = $entryId;
        $procMap['order_sn'] = $otherInfo['order_sn'];
        $procMap['user_id'] = $user_id;
        $procMap['is_back'] = 0;
        $procMap['is_deleted'] = 1;
        $procMap['status'] = 0;
        $proc_id = Db::name('workflow_proc')->where($procMap)->value('id');
        if(empty($proc_id)){
            return 2;
        }else{
            return 1;
        }

    }

    /**
     * 返回是否需要显示审批
     * @Param {string} $process_type   费用申请类型
     * @Param {int} $stage    订单状态
     * @Param {arr} $signArr   所拥有的权限组
     */
    public function getShowApp($process_type, $stage, $signArr)
    {
        switch ($process_type) {
            case 'INFO_FEE' :
                return self::getAPPXXF($stage, $signArr);
                break;
            case 'SQ_TRANSFER' :
                return self::getAPPSQ($stage, $signArr);
                break;
            case 'DEPOSIT' :
                return self::getAPPBZJ($stage, $signArr);
                break;
            case 'XJ_GUARANTEE_FEE' :
                return self::getAPPATDBF($stage, $signArr);
                break;
            case 'ED_GUARANTEE_FEE' :
                return self::getAPPEDDBF($stage, $signArr);
                break;
            case 'ED_TAIL' :
                return self::getAPPEDFWK($stage, $signArr);
                break;
            case 'XJ_TAIL' :
                return self::getAPPEDFWK($stage, $signArr);
                break;
            case 'EXHIBITION' :
                return self::getAPPZQSQ($stage, $signArr);
                break;
            default:
                return 2;
        }

    }

    /*
    * 获取额度现金退放尾款
    * */
    private function getAPPEDFWK($stage, $signArr)
    {
        if ($stage == 301) {
            return 2;
        } elseif ($stage == 309) {  //赎楼经理审批
            if (in_array('foreclosure_manager', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 304) {  //核算专员
            if (in_array('accounting', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 306) {  //资金专员
            if (in_array('other_treasury_staff', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 2;
        }
    }

    /*
     * 获取额度退担保
     * $stage int  申请状态
     * $signArr arr  改用拥有的权限组
     * */
    private function getAPPEDDBF($stage, $signArr)
    {
        if ($stage == 301) {
            return 2;
        } elseif ($stage == 304) {  //核算专员
            if (in_array('accounting', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 303) {  //风控经理
            if (in_array('censorship_manager', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 306) {  //资金专员
            if (in_array('other_treasury_staff', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 2;
        }
    }

    /*
     * 获取按天退担保费
     * $stage int  申请状态
     * $signArr arr  改用拥有的权限组
     * */
    private function getAPPATDBF($stage, $signArr)
    {
        if ($stage == 301) {
            return 2;
        } elseif ($stage == 305) {   //回款专员
            if (in_array('hk_staff', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 304 || $stage == 307) {  //核算专员
            if (in_array('accounting', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 303) {  //风控经理
            if (in_array('censorship_manager', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 306) {  //资金专员
            if (in_array('other_treasury_staff', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 2;
        }
    }

    /*
     * 获取保证金退费
     * $stage int  申请状态
     * $signArr arr  改用拥有的权限组
     * */
    private function getAPPBZJ($stage, $signArr)
    {
        if ($stage == 301) {
            return 2;
        } elseif ($stage == 302) {   //部门经理
            if (in_array('branch_manager', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 303) {  //风控经理
            if (in_array('censorship_manager', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 304) {  //核算专员
            if (in_array('accounting', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 306) {  //资金专员退费
            if (in_array('other_treasury_staff', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 2;
        }
    }

    /*
     * 获取首期转账
     * $stage int  申请状态
     * $signArr arr  改用拥有的权限组
     * */
    private function getAPPSQ($stage, $signArr)
    {
        if ($stage == 301) {
            return 2;
        } elseif ($stage == 303) {   //风控经理
            if (in_array('censorship_manager', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 305) {  //回款专员
            if (in_array('hk_staff', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 2;
        }
    }

    /*
     * 获取信息费申请是否展示审批
     * $stage int  申请状态
     * $signArr arr  改用拥有的权限组
     * */
    private function getAPPXXF($stage, $signArr)
    {
        if ($stage == 301) {
            return 2;
        } elseif ($stage == 304) {   //核算专员
            if (in_array('accounting', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 306) {  //资金专员
            if (in_array('other_treasury_staff', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 2;
        }
    }

    /*
     * 获取展期费申请是否展示审批
     * $stage int  申请状态
     * $signArr arr  改用拥有的权限组
     * */
    private function getAPPZQSQ($stage, $signArr)
    {
        if ($stage == 301) {
            return 2;
        } elseif ($stage == 302) {
            if (in_array('branch_manager', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } elseif ($stage == 305) {
            if (in_array('hk_staff', $signArr)) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 2;
        }
    }

    /**获取沟通回复列表
     * @param array $where 查询条件
     * @param bool $filed 查询字段
     * @param array $paginate 分页参数
     * @return array
     * @author: 赵光帅
     */
    public static function replyList($where = [], $field = true, $paginate = [])
    {
        $list = Db::name('order_communicate_reply')->alias('ocr')
            ->join('order_communicate oc', 'oc.id = ocr.order_communicate_id')
            ->join('order_other x', 'x.process_sn = oc.sn')
            ->join('estate y', 'x.order_sn = y.order_sn', 'left')
            ->join('order o', 'o.order_sn = x.order_sn')
            ->join('system_user z', 'z.id = o.financing_manager_id')
            ->where($where)
            ->field($field)
            ->group('x.id')
            ->order('x.create_time desc')
            ->paginate($paginate)
            ->toArray();
        foreach ($list['data'] as $key => $value) {
            //时间年月日
            if (isset($value['create_time']) && !empty($value['create_time'])) {
                if (empty($value['content'])) {
                    $list['data'][$key]['reply_state'] = '待回复';
                } else {
                    $list['data'][$key]['reply_state'] = '已回复';
                }

                $list['data'][$key]['application_type'] = self::getProcessTypeStr($value['process_type']);
                $list['data'][$key]['create_time'] = date('Y-m-d', $value['create_time']);
                $list['data'][$key]['stage_text'] = self::getType($value['stage']); //订单状态
                unset($list['data'][$key]['content']);
            }
        }
        return $list;
    }

    /**
     * 其他退费申请列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */
    public static function tailList($map, $field, $page, $pageSize)
    {
        $resInfo = self::alias('x')
            ->field($field)
            ->join('order o', 'x.order_sn = o.order_sn')
            ->join('estate y', 'x.order_sn = y.order_sn', 'left')
            ->join('system_user z ', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('x.create_time desc')
            ->group('x.id')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
        foreach ($resInfo['data'] as $key => $value) {
            //时间年月日
            $resInfo['data'][$key]['create_time'] = date('Y-m-d', strtotime($value['create_time']));
            $resInfo['data'][$key]['stage_text'] = self::getType($value['stage']); //订单状态
            $resInfo['data'][$key]['process_type_code'] = self::getProcessTypecodew($value['process_type']); //订单状态
        }
        return $resInfo;

    }

    /*
     * 额度类订单放尾款信息
     * @Param {string} $order_sn    订单号
     * @author 赵光帅
     * */

    public function showTailSection($order_sn, $id = false)
    {
        //银行放款时间
        $recondInfo = Db::name('order_account_record')->where(['order_sn' => $order_sn])->field('create_time')->find();
        //银行放款金额,赎楼返还款 ,实收罚息,实收短贷利息
        $field = 'loan_money,ac_return_money,ac_default_interest,ac_short_loan_interest';
        $guaranteeInfo = Db::name('order_guarantee')->where(['order_sn' => $order_sn, 'status' => 1])->field($field)->find();
        //赎楼金额
        $sureMap['order_sn'] = $order_sn;
        $sureMap['item'] = ['in', '1,2,3'];
        $sureMap['status'] = 1;
        $ForeclosureInfo = Db::name('order_ransom_out')->where($sureMap)->sum('money');
        //已退赎楼，已退罚息，已退短贷利息
        $otherInfo = Db::name('order_other')->where(['order_sn' => $order_sn, 'status' => 1, 'stage' => ['<>',301]])->field('sum(return_money) return_money,sum(default_interest) default_interest,sum(short_loan) short_loan')->find();
        //var_dump($otherInfo);exit;
        //可退赎楼金额
        $backFloor = $guaranteeInfo['loan_money'] - $ForeclosureInfo + $guaranteeInfo['ac_return_money'] - $otherInfo['return_money'];
        //已用罚息金额  已用短贷利息
        if(empty($id)){
            $outMoney = 0;
            $usedShortLoan = 0;
        }else{
            $ooInfo = Db::name('order_other')->where(['id' => $id])->field('used_interest,used_short_loan')->find();
            $outMoney = $ooInfo['used_interest'];
            $usedShortLoan = $ooInfo['used_short_loan'];
        }
        //可退罚息金额
        $canBackMoney = $guaranteeInfo['ac_default_interest'] - $outMoney - $otherInfo['default_interest'];

        //可退短贷利息
        $canBackShortLoan = $guaranteeInfo['ac_short_loan_interest'] - $usedShortLoan - $otherInfo['short_loan'];
        //额度赎楼信息
        $linesInfo = [];
        $linesInfo[0]['create_time'] = date('Y-m-d',$recondInfo['create_time']);
        $linesInfo[0]['loan_money'] = $guaranteeInfo['loan_money'];
        $linesInfo[0]['foreclosure'] = $ForeclosureInfo;
        $linesInfo[0]['ac_return_money'] = $guaranteeInfo['ac_return_money'];
        $linesInfo[0]['sum_return_money'] = $otherInfo['return_money'];
        $linesInfo[0]['back_floor'] = $backFloor;
        //罚息信息
        $canBackInfo = [];
        $canBackInfo[0]['ac_default_interest'] = $guaranteeInfo['ac_default_interest'];
        $canBackInfo[0]['used_interest'] = $outMoney;
        $canBackInfo[0]['sum_default_interest'] = $otherInfo['default_interest'];
        $canBackInfo[0]['can_back_money'] = $canBackMoney;
        //短贷利息信息
        $shortLoan = [];
        $shortLoan[0]['ac_short_loan_interest'] = $guaranteeInfo['ac_short_loan_interest'];
        $shortLoan[0]['used_short_loan'] = $usedShortLoan;
        $shortLoan[0]['sum_short_loan'] = $otherInfo['short_loan'];
        $shortLoan[0]['can_back_short_loan'] = $canBackShortLoan;
        return ['linesInfo' => $linesInfo, 'canBackInfo' => $canBackInfo, 'shortLoan' => $shortLoan];
    }

    /*
     * 现金详情
     * @Param {string} $order_sn    订单号
     * @author 赵光帅
     * */

    public function showRemainingCash($order_sn, $id = false)
    {
        //回款时间
        $returntime = Db::name('order_ransom_return')->where(['order_sn' => $order_sn])
            ->where('status', 1)
            ->where('(return_money_into_status IN (2, 3)) OR (return_money_into_status = 1 AND is_rebut = 1)')
            ->value('return_time');
        //回款金额
        $remainingMoney = OrderRansomReturn::getReturnMoney(['order_sn' => $order_sn]);
        //银行放款金额,赎楼返还款 ,实收罚息,实收短贷利息
        $field = 'loan_money,ac_return_money,ac_default_interest,ac_short_loan_interest';
        $guaranteeInfo = Db::name('order_guarantee')->where(['order_sn' => $order_sn, 'status' => 1])->field($field)->find();
        //赎楼金额
        $sureMap['order_sn'] = $order_sn;
        $sureMap['status'] = 1;
        $sureMap['account_status'] = ['in', '2,3,5'];
        $ForeclosureInfo = Db::name('order_ransom_out')->where($sureMap)->sum('money');
        //已退赎楼，已退罚息，已退短贷利息
        $otherInfo = Db::name('order_other')->where(['order_sn' => $order_sn, 'status' => 1, 'stage' => ['<>',301]])->field('sum(return_money) return_money,sum(default_interest) default_interest,sum(short_loan) short_loan')->find();
        //var_dump($otherInfo);exit;
        //可退赎楼金额
        $backFloor = $guaranteeInfo['loan_money'] - $ForeclosureInfo + $guaranteeInfo['ac_return_money'] - $otherInfo['return_money'];
        //已用罚息金额
        if(empty($id)){
            $outMoney = 0;
        }else{
            $outMoney = Db::name('order_other')->where(['id' => $id])->value('used_interest');
        }
        //可退罚息金额
        $canBackMoney = $guaranteeInfo['ac_default_interest'] - $outMoney - $otherInfo['default_interest'];

        //现金赎楼信息
        $linesInfo = [];
        $linesInfo[0]['create_time'] = $returntime;
        $linesInfo[0]['loan_money'] = $remainingMoney;
        $linesInfo[0]['foreclosure'] = $ForeclosureInfo;
        $linesInfo[0]['ac_return_money'] = $guaranteeInfo['ac_return_money'];
        $linesInfo[0]['sum_return_money'] = $otherInfo['return_money'];
        $linesInfo[0]['back_floor'] = $backFloor;

        //罚息信息
        $canBackInfo = [];
        $canBackInfo[0]['ac_default_interest'] = $guaranteeInfo['ac_default_interest'];
        $canBackInfo[0]['used_interest'] = $outMoney;
        $canBackInfo[0]['sum_default_interest'] = $otherInfo['default_interest'];
        $canBackInfo[0]['can_back_money'] = $canBackMoney;

        return ['linesInfo' => $linesInfo, 'canBackInfo' => $canBackInfo];
    }

    /* @author 赵光帅
     * 放尾款审批列表查询
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function balanceApprovalList($map, $field, $page, $pageSize)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field($field)
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('system_user z', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('d.status asc')
            ->order('d.create_time asc')
            ->group('x.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        foreach ($res['data'] as $k => $v) {
            $res['data'][$k]['create_time'] = date('Y-m-d', $v['create_time']);
            $res['data'][$k]['process_type_text'] = self::getProcessTypeStr($v['process_type']); //订单类型
            $res['data'][$k]['stage_text'] = self::getType($v['stage']); //订单状态
            $res['data'][$k]['process_type_code'] = self::getProcessTypecodew($v['process_type']); //订单状态
        }
        return $res;
    }

    /* @author 赵光帅
     * 放尾款审批列表查询
     *
     * @Param {float} $money  应退金额
     * */

    public static function realBackMoney($money)
    {
        $return = [];
        if ($money <= 10000) {
            $return['actual_payment'] = $money - 5.5;
            $return['expense_taxation'] = 5.5;
        } elseif ($money > 10000 && $money <= 100000) {
            $return['actual_payment'] = $money - 10.5;
            $return['expense_taxation'] = 10.5;
        } elseif ($money > 100000 && $money <= 500000) {
            $return['actual_payment'] = $money - 15.5;
            $return['expense_taxation'] = 15.5;
        } elseif ($money > 500000 && $money <= 1000000) {
            $return['actual_payment'] = $money - 20.5;
            $return['expense_taxation'] = 20.5;
        } else {
            //sprintf("%.2f", ($money * 0.00002) + 0.5)
            $return['actual_payment'] = $money - sprintf("%.2f", ($money * 0.00002) + 0.5);
            $return['expense_taxation'] = sprintf("%.2f", ($money * 0.00002) + 0.5);
        }

        return $return;
    }

    /* @author 赵光帅
     * 添加申请操作日志
     *
     * @Param {int} $type  申请类型
     * @Param {arr} $userinfo  用户信息
     * @Param {string} $ordersn  订单号
     * */

    public static function addOperationLog($type = false, $userinfo = false, $ordersn = false)
    {
        $stage_code = Db::name('order')->where(['order_sn' => $ordersn])->value('stage');
        $operate_node = self::getLogStr($type);
        $stage = $operate = show_status_name($stage_code, 'ORDER_JYDB_STATUS');
        $operate_det = $userinfo['name'] . "提交" . $operate_node;
        $operate_reason = '';
        $operate_table = 'order_other';
        return OrderComponents::addOrderLog($userinfo, $ordersn, $stage, $operate_node, $operate, $operate_det, $operate_reason, $stage_code, $operate_table);
    }

    /**
     * 获取订单基本信息
     * @Param {arr} $process_type   费用申请类型
     */
    public static function getLogStr($process_type)
    {
        switch ($process_type) {
            case '1' :
                return '信息费支付申请';
                break;
            case '2' :
                return '首期款转账申请';
                break;
            case '3' :
                return '退保证金申请';
                break;
            case '4' :
                return '现金按天退担保费申请';
                break;
            case '5' :
                return '额度退担保费申请';
                break;
            case '6' :
                return '额度类订单放尾款申请';
                break;
            case '7' :
                return '现金类订单放尾款申请';
                break;
            case '8' :
                return '展期申请';
                break;
            case '9' :
                return '要事审批申请';
                break;
            case '10' :
                return '新增撤单申请(退担保费)';
                break;
            case '11' :
                return '新增撤单申请(不退保费)';
                break;
            case '12' :
                return '新增撤单申请(保费调整)';
                break;
            default:
                return '';
        }

    }

    /* @author 赵光帅
     * 添加申请操作日志
     *
     * @Param {int} $type  申请类型
     * @Param {arr} $userinfo  用户信息
     * @Param {string} $ordersn  订单号
     * */

    public static function addSubLog($type = false, $userinfo = false, $ordersn = false, $opertype = false)
    {
        if (in_array($type, [2,3,4,5,10,11,12])) {
            $stage_code = Db::name('order')->where(['order_sn' => $ordersn])->value('stage');
            if ($type == 2) {
                $operate_node = '财务出账';
            } elseif ($type == 3) {
                $operate_node = '退保证金';
            }else {
                $operate_node = '退担保费';
            }

            $stage = $operate = show_status_name($stage_code, 'ORDER_JYDB_STATUS');
            if ($opertype == 1) {
                $operate_det = $userinfo['name'] . "通过" . self::getLogStr($type) . '退费成功';
            } else {
                $operate_det = $userinfo['name'] . "驳回" . self::getLogStr($type) . '退费取消';
            }

            $operate_reason = '';
            $operate_table = 'order_other';
            return OrderComponents::addOrderLog($userinfo, $ordersn, $stage, $operate_node, $operate, $operate_det, $operate_reason, $stage_code, $operate_table);
        } else {
            return true;
        }

    }
    /**
     * 要事审批详情
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */
    public static function importantMat($map)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field('max(d.id) proc_id,x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name,z.mobile,z.deptname,o.dept_manager_id,x.reason')
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('system_user z', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('d.status asc')
            ->order('d.create_time asc')
            ->group('x.id')
            ->find();
        $res['create_time'] = date('Y-m-d', $res['create_time']);
        $res['process_type_text'] = self::getProcessTypeStr($res['process_type']); //订单类型
        $res['stage_text'] = self::getType($res['stage']); //订单状态
        $res['process_type_code'] = self::getProcessTypecodew($res['process_type']); //订单状态
        $res['dept_manager']=Db::name('system_user')->where(['id'=>$res['dept_manager_id']])->value('name');
        $res['estate_name']=implode(';', Db::name('estate')->where(['order_sn' => $res['order_sn']])->column('estate_name')); //房产名称
        return $res;
    }

    /**
     * 编辑要事审批详情
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */
    public static function editImportantMat($map)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field('max(d.id) proc_id,x.id,x.process_sn,x.process_type,o.order_sn,o.finance_sn,o.type,y.estate_name,y.estate_owner,x.money,x.stage,x.create_time,z.name,z.mobile,z.deptname,o.dept_manager_id,x.reason')
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('system_user z', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('d.status asc')
            ->order('d.create_time asc')
            ->group('x.id')
            ->find();
        $res['create_time'] = date('Y-m-d', $res['create_time']);
        $res['process_type_text'] = self::getProcessTypeStr($res['process_type']); //订单类型
        $res['stage_text'] = self::getType($res['stage']); //订单状态
        $res['process_type_code'] = self::getProcessTypecodew($res['process_type']); //订单状态
        $res['dept_manager']=Db::name('system_user')->where(['id'=>$res['dept_manager_id']])->value('name');
        $res['estate_name']=implode(';', Db::name('estate')->where(['order_sn' => $res['order_sn']])->column('estate_name')); //房产名称
        $attachment= Db::name('order_other_attachment')->alias('ooa')
            ->join('attachment a', 'ooa.attachment_id = a.id')
            ->field('a.id,a.url,a.name,a.thum1,a.ext')
            ->where(['ooa.order_other_id' => $res['id'], 'status' => 1])
            ->select();
        foreach ($attachment as $key => $val) {
            $attachment[$key]['url'] = config('uploadFile.url') . $val['url'];
        }
        $res['attachment']=$attachment;
        return $res;
    }

    /* 
     * 要事审批列表查询
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function importantMatlList($map, $field, $page, $pageSize)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field($field)
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('system_user z', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('d.status asc')
            ->order('d.create_time asc')
            ->group('x.id')
            //->fetchSql(true)
            //->select();
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        foreach ($res['data'] as $k => $v) {
            $res['data'][$k]['create_time'] = date('Y-m-d', $v['create_time']);
            $res['data'][$k]['process_type_text'] = self::getProcessTypeStr($v['process_type']); //订单类型
            //$res['data'][$k]['stage_text'] = self::getType($v['stage']); //订单状态
            $res['data'][$k]['process_type_code'] = self::getProcessTypecodew($v['process_type']); //订单状态
            $res['data'][$k]['dept_manager']=Db::name('system_user')->where(['id'=>$v['dept_manager_id']])->value('name');
            $res['data'][$k]['estate_name']=implode(';', Db::name('estate')->where(['order_sn' => $v['order_sn']])->column('estate_name')); //房产名称
            $attachment= Db::name('order_other_attachment')->alias('ooa')
                ->join('attachment a', 'ooa.attachment_id = a.id')
                ->field('a.id,a.url,a.name,a.thum1,a.ext')
                ->where(['ooa.order_other_id' => $v['id'], 'status' => 1])
                ->select();
            foreach ($attachment as $key => $val) {
                $attachment[$key]['url'] = config('uploadFile.url') . $val['url'];
            }
            $res['data'][$k]['attachment']=$attachment;
        }
        return $res;
    }


    /**
     * 撤单申请列表
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     */
    public static function cancellationsList($map, $field, $page, $pageSize)
    {
        $resInfo = self::alias('x')
            ->field($field)
            ->join('order o', 'x.order_sn = o.order_sn')
            ->join('estate y', 'x.order_sn = y.order_sn', 'left')
            ->join('system_user z ', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('x.create_time desc')
            ->group('x.id')
            ->paginate(['list_rows' => $pageSize, 'page' => $page])
            ->toArray();
        foreach ($resInfo['data'] as $key => $value) {
            //时间年月日
            $resInfo['data'][$key]['create_time'] = date('Y-m-d', strtotime($value['create_time']));
            $resInfo['data'][$key]['type_text'] = (new order())->getType($value['type']); //订单类型
            $resInfo['data'][$key]['stage_text'] = self::getType($value['stage']); //审批状态
            $resInfo['data'][$key]['refund_type'] = self::getRefundType($value['process_type']);
        }
        return $resInfo;
    }

    /**
     * 获取退费类型
     * @Param {arr} $process_type   费用申请类型
     */
    public static function getRefundType($process_type)
    {
        switch ($process_type) {
            case 'CANCEL_ORDER_REFUND' :
                return '退担保费';
                break;
            case 'CANCEL_ORDER_NOREFUND' :
                return '不退保费';
                break;
            case 'CANCEL_ORDER_PREMIUM' :
                return '保费调整';
                break;
            default:
                return '';
        }

    }

    /*
    * 获取撤单申请订单费用信息
    * */
    public function getCostInfo($order_sn)
    {
        $costInfo = Db::name('order_guarantee')
            ->field('guarantee_fee,ac_guarantee_fee,ac_fee,ac_self_financing,ac_short_loan_interest,ac_default_interest,ac_transfer_fee,ac_deposit,ac_other_money')
            ->where(['order_sn' => $order_sn, 'status' => 1])
            ->find();
        $costInfo['sum_money'] = array_sum($costInfo);
        return $costInfo;

    }

    /* @author 赵光帅
     * 撤单申请审批列表查询
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function cancellApprovalList($map, $field, $page, $pageSize)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field($field)
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('system_user z', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('d.status asc')
            ->order('d.create_time asc')
            ->group('x.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('OTHER_BUS_STATUS'));
        foreach ($res['data'] as $k => $v) {
            $res['data'][$k]['create_time'] = date('Y-m-d', $v['create_time']);
            $res['data'][$k]['process_type_text'] = self::getProcessTypeStr($v['process_type']); //订单类型
            $res['data'][$k]['stage_text'] = !empty($v['stage']) ? $newStageArr[$v['stage']] : '';
            $res['data'][$k]['type_text'] = (new Order())->getType($v['type']); //订单类型
        }
        return $res;
    }

    /* @author 赵光帅
     * 撤单申请审批列表查询
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function cancellManagementlList($map, $field, $page, $pageSize)
    {
        $res = Db::name('workflow_proc')->alias('d')
            ->field($field)
            ->join('workflow_entry we', 'd.entry_id = we.id')
            ->join('workflow_flow wf', 'd.flow_id = wf.id')
            ->join('order_other x', 'x.id = we.mid')
            ->join('estate y', 'd.order_sn = y.order_sn', 'LEFT')
            ->join('order o', 'd.order_sn = o.order_sn')
            ->join('system_user z', 'o.financing_manager_id = z.id')
            ->where($map)
            ->order('d.status asc')
            ->order('d.create_time asc')
            ->group('x.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();
        $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('OTHER_BUS_STATUS'));
        foreach ($res['data'] as $k => $v) {
            $res['data'][$k]['create_time'] = date('Y-m-d', $v['create_time']);
            $res['data'][$k]['process_type_text'] = self::getProcessTypeStr($v['process_type']); //订单类型
            $res['data'][$k]['stage_text'] = !empty($v['stage']) ? $newStageArr[$v['stage']] : '';
        }
        return $res;
    }

    /**
     * 获取撤单申请信息
     * @Param {int} $id    其他业务表主键id
     */
    public function getCancellInfo($id)
    {
        $field = 'id,process_sn,reason';
        $applyInfo = Db::name('order_other')->where(['id' => $id])->field($field)->find();
        $applyInfo['attachment'] = Db::name('order_other_attachment')->alias('ooa')
            ->join('attachment a', 'ooa.attachment_id = a.id')
            ->field('a.id,a.url,a.name,a.thum1,a.ext')
            ->where(['ooa.order_other_id' => $id, 'status' => 1])
            ->select();
        foreach ($applyInfo['attachment'] as $k => $v) {
            $applyInfo['attachment'][$k]['url'] = config('uploadFile.url') . $v['url'];
        }
        return $applyInfo;
    }


}