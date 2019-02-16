<?php

namespace app\task\controller;

use think\Cache;
use think\Config;
use think\Controller;
use think\Db;
use think\Log;
use app\model\OrderRansomReturn;
use Workerman\Lib\Timer;
use app\model\OrderRansomOut;
use app\model\OrderCostDetail;
use app\model\OrderCollectFee;

class ReturnMoney extends Controller {

    private $orderrannsomOut;
    private $ordercostDetail;
    private $orderrannsomReturn;
    private $ordercollectfee;

    public function _initialize() {
        parent::_initialize();
        $this->orderrannsomOut = new OrderRansomOut();
        $this->ordercostDetail = new OrderCostDetail();
        $this->orderrannsomReturn = new OrderRansomReturn();
        $this->ordercollectfee = new OrderCollectFee();
    }

    /**
     * 添加定时器
     *
     */
    public function addTimer() {
        Timer::add(60 * 60, array($this, 'AutoComputerFee'), array(), true); //定时任务 算每日回款费用（3600秒 正式）
//        Timer::add(10, array($this, 'AutoComputerFee'), array(), true); //定时任务 算每日回款费用（测式）
    }

    public function AutoComputerFee() {
        try {
            if (date('H') === '00') {
                $this->today = date("Y-m-d");
                $this->yestoday = date("Y-m-d", strtotime("-1 day"));
                $this->beforyestoday = date("Y-m-d", strtotime("-2 day"));
                log::record('ReturnMoney_Start@@@@@@@@@@@@@@@@@@@@@@@@@');
                $map = ['g.status' => 1, 'o.stage' => ['in', '1014,1015,1016,1017,1018,1019,1020,1025,1024']];
                $data = Db::name('order_guarantee')
                        ->alias('g')
                        ->join('__ORDER__ o', 'o.order_sn=g.order_sn')
                        ->field('g.out_account_com_total,g.order_sn,g.guarantee_rate,g.ac_guarantee_fee,ac_exhibition_fee,g.info_fee')
                        ->whereNotNull('out_account_com_total')
                        ->where($map)
                        ->select();
                if (!empty($data)) {
                    foreach ($data as $value) {
                        log::record($value['order_sn']);
                        //判断最新的一条是不是前天  如果不是前天 那么说明前天（前天之前的）的数据都没跑定时任务 要补上
                        $this->CheckisYestoday($value['order_sn']);
                        //检测今天是否有到账时间不是今天的回款记录/回款退回/出账退回 （出现这三种情况，从生效时间到今天之前都要重新计算费用）
                        $this->CheckisAbnormalRecord($value['order_sn']);
                        $is_yestoday_waitmoney = DB::name('order_collect_fee')->where(['order_sn' => $value['order_sn'], 'cal_date' => $this->yestoday, 'status' => 1])->value('wait_money'); //如果昨天存在记录 不再执行
                        if (!empty($is_yestoday_waitmoney)) {
                            continue;
                        }
                        $today_back_money = OrderRansomReturn::getReturnMoney(['order_sn' => $value['order_sn'], 'return_time' => $this->yestoday]);
                        $out_account_com_total = $value['out_account_com_total'];
                        $computer_total = $today_back_money + $out_account_com_total; //当日回款金额+公司累计垫资 = 用来计算担保费总额
                        $exhabition_info = Db::name('order_other')->where(['process_type' => 'EXHIBITION', 'stage' => 308, 'status' => 1, 'order_sn' => $value['order_sn']])->field('id')->select(); //所有审批通过的展期合同信息
                        $exhabition_times = count($exhabition_info); //展期次数
                        $rate = DB::name('order_advance_money')->where(['order_sn' => $value['order_sn'], 'status' => 1])->value('advance_rate'); //今日默认的担保费率
                        $totay_fee = sprintf('%.2f', $computer_total * $rate / 100); //今日累计担保费用(默认没有逾期和展期)
                        $now_fee_total = Db::name('order_collect_fee')->where(['order_sn' => $value['order_sn'], 'type' => 1, 'status' => 1])->sum('money'); //当前正常担保费
                        $now_exfee_total = Db::name('order_collect_fee')->where(['order_sn' => $value['order_sn'], 'type' => ['in', '1,2'], 'status' => 1])->sum('money'); //当前正常担保费加展期费
                        $ac_get_fee = $value['ac_guarantee_fee'] - $value['info_fee']; //实收费用 = 实收担保费-信息费
                        $ac_get_fee_exhibition = $value['ac_guarantee_fee'] - $value['info_fee'] + $value['ac_exhibition_fee']; //实收费用 = 实收担保费-信息费+已收展期费;
                        $type = 1;
                        if ($exhabition_times == 0) {//没有展期合同
                            $ac_fee_total = $totay_fee + $now_fee_total; //累计总费用 = 今日累计费用+当前正常担保费
                            if ($ac_fee_total > $ac_get_fee) {//如果当前累计生实际产费用大于实收费用  按逾期费算 = 当日担保费总额*0.0015
                                $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                                $type = 3;
                                $rate = 0.15;
                            }
                        } else {//存在展期合同
                            $type = 2;
                            $returndata = $this->Checkisexhibition($exhabition_info); //判断是否所有展期合同都存在实际展期结束时间
                            if ($returndata['is_exhibition_endtime']) {//如果所有的展期合同都存在实际结束时间  今日费用按逾期算
                                $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                                $type = 3;
                                $rate = 0.15;
                            } else {
                                $return_same_data = $this->ChecktodayisSame($exhabition_info); //判断所有展期合同是否与今日重叠
                                if ($return_same_data['same_time'] == 0) {//没有重叠
                                    if (!$return_same_data['is_frist_exhibition']) {//是否是第一次展期
                                        $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                                        $type = 3;
                                        $rate = 0.15;
                                    } else {
                                        $ac_fee_total = $totay_fee + $now_fee_total; //累计总费用 = 今日累计费用+当前正常担保费
                                        if ($ac_fee_total > $ac_get_fee) {//如果当前累计生实际产费用大于实收费用  按逾期费算 = 当日担保费总额*0.0015
                                            $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                                            $type = 3;
                                            $rate = 0.15;
                                        } else {
                                            $type = 1;
                                        }
                                    }
                                } else {
                                    $rate = $return_same_data['exhibition_rate'];
                                    $totay_fee = sprintf('%.2f', $computer_total * $return_same_data['exhibition_rate'] / 100);
                                    $ac_fee_total = $totay_fee + $now_exfee_total; //累计总费用 = 今日展期费用+累计费用
                                    if ($ac_fee_total > $ac_get_fee_exhibition) {//如果当前累计生实际产费用大于实收费用  按逾期费算 = 当日担保费总额*0.0015
                                        $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                                        $type = 3;
                                        $rate = 0.15;
                                        DB::name('order_other_exhibition')->where(['order_other_id' => $return_same_data['order_other_id']])->update(['actual_exhibition_endtime' => $this->beforyestoday, 'actual_exhibition_day' => $this->diffInDays($return_same_data['exhibition_starttime'], $this->beforyestoday), 'actual_exhibition_update_time' => time()]);
                                    } else {
                                        if ($return_same_data['exhibition_endtime'] == $this->yestoday) {///如果按正常展期算费还够今日费用但是今天正好是合同结束时间  那就结束当前合同
                                            DB::name('order_other_exhibition')->where(['order_other_id' => $return_same_data['order_other_id']])->update(['actual_exhibition_endtime' => $this->yestoday, 'actual_exhibition_day' => $this->diffInDays($return_same_data['exhibition_starttime'], $this->yestoday), 'actual_exhibition_update_time' => time()]);
                                        }
                                    }
                                }
                            }
                        }
                        //存储数据
                        //如果今天跑出来的费用小于0 则默认成0
                        $totay_fee < 0 && $totay_fee = 0;
                        $adddata = ['order_sn' => $value['order_sn'], 'cal_date' => $this->yestoday, 'wait_money' => $out_account_com_total, 'cal_money' => $computer_total, 'type' => $type, 'rate' => $rate, 'money' => $totay_fee, 'create_uid' => -1, 'create_time' => time(), 'update_time' => time()];
                        if (!Db::name('order_collect_fee')->insert($adddata)) {
                            Log::record('添加失败');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::record($e->getCode());
            Log::record($e->getMessage());
        }
    }

    /*
     * 判断是否有展期结束时间 
     * $param 所有合同
     */

    public function Checkisexhibition($param) {
        $data['is_exhibition_endtime'] = true;
        $data['notendexhition_times'] = 0;
        foreach ($param as $k => $v) {
            $edata = DB::name('order_other_exhibition')->where('order_other_id', $v['id'])->field('actual_exhibition_endtime')->find();
            if (empty($edata['actual_exhibition_endtime'])) {//如果一个不存在展期实际结束时间
                $data['notendexhition_times'] ++; //统计所有不存在展期实际结束时间的次数
                $data['is_exhibition_endtime'] = false;
                break;
            }
        }
        return $data;
    }

    /*
     * 两个日期之间的天数
     */

    private function diffInDays($begin_time, $end_time) {
        return (strtotime($end_time) - strtotime($begin_time)) / 86400;
    }

    /*
     * 判断是否存在遗漏数据（服务器崩了，费用补上）
     * $order_sn 订单号
     */

    public function CheckisYestoday($order_sn) {
        $all_where = ['create_uid' => -1, 'order_sn' => $order_sn, 'status' => 1]; //create_uid == -1就是定时任务跑出来的数据
        $all_data = Db::name('order_collect_fee')->where($all_where)->select();
        if (!empty($all_data)) {//如果这个订单有历史数据 说明不是第一次跑定时任务
            $all_date = array_column($all_data, 'cal_date');
            if (!array_search($this->beforyestoday, $all_date)) {//如果历史数据有 但是前天没有  补上历史数据最新日期到昨天的记录
                $start = date('Y-m-d', strtotime('+1 day', strtotime(max($all_date))));
                $dataArr = $this->prDates($start, $this->beforyestoday);
                foreach ($dataArr as $value) {
                    sock_post('http://127.0.0.1/businesssys_api/public/api/ReturnMoney/CheckisGetfee', ['order_sn' => $order_sn, 'com_day' => $value, 'comput_type' => 1]);
                }
            }
        }
    }

    /*
     * 检测今天是否有到账时间不是今天的回款记录/回款退回/出账退回 （出现这三种情况，从生效时间到今天之前都要重新计算费用）
     * $order_sn 订单号
     */

    public function CheckisAbnormalRecord($order_sn) {
        $Obsolete_today = Db::name('order_cost_detail')->where(['order_sn' => $order_sn, 'cost_date' => $this->yestoday, 'status' => 3, 'type' => 3])->field('tablename,tableid')->select(); //回款入账隔天被作废  优先变更等级最高
        $Outaccount_today = Db::name('order_cost_detail')->where(['order_sn' => $order_sn, 'cost_date' => $this->yestoday, 'status' => 3, 'type' => 1])->field('tablename,tableid')->select(); //赎楼出账被退回 优先变更等级次之
        $Overdue_today = Db::name('order_cost_detail')->where(['order_sn' => $order_sn, 'cost_date' => $this->yestoday, 'status' => 1, 'type' => 3])->field('tablename,tableid')->select(); //回款入账被核算（但是到账时间不是今天）优先变更等级最低
        if (!empty($Obsolete_today)) {
            foreach ($Obsolete_today as $key => $value) {
                $start_data = Db::name('order_cost_detail')->where(['tableid' => $value['tableid'], 'tablename' => $value['tablename'], 'cost_date' => ['neq', $this->yestoday], 'status' => ['neq', 3]])->field('cost_date,money')->find();
                if (!empty($start_data)) {
                    $dataArr = $this->prDates($start_data['cost_date'], $this->beforyestoday);
                    foreach ($dataArr as $value) {
                        $this->CheckisGetfee(['order_sn' => $order_sn, 'com_day' => $value, 'sta_day' => $start_data['cost_date'], 'comput_type' => 2, 'money' => $start_data['money']]);
//                        sock_post('http://127.0.0.1/businesssys_api/public/api/ReturnMoney/CheckisGetfee', ['order_sn' => $order_sn, 'com_day' => $value, 'sta_day' => $start_data['cost_date'], 'comput_type' => 2, 'money' => $start_data['money']]);
                    }
                }
            }
        }
        if (!empty($Outaccount_today)) {
            foreach ($Outaccount_today as $key => $value) {
                $start_data = Db::name('order_cost_detail')->where(['tableid' => $value['tableid'], 'tablename' => $value['tablename'], 'cost_date' => ['neq', $this->yestoday], 'status' => ['neq', 3]])->field('cost_date,money')->find();
                if (!empty($start_data)) {
                    $dataArr = $this->prDates($start_data['cost_date'], $this->beforyestoday);
                    foreach ($dataArr as $value) {
                        $this->CheckisGetfee(['order_sn' => $order_sn, 'com_day' => $value, 'sta_day' => $start_data['cost_date'], 'comput_type' => 3, 'money' => $start_data['money']]);
//                        sock_post('http://127.0.0.1/businesssys_api/public/api/ReturnMoney/CheckisGetfee', ['order_sn' => $order_sn, 'com_day' => $value, 'sta_day' => $start_data['cost_date'], 'comput_type' => 3, 'money' => $start_data['money']]);
                    }
                }
            }
        }
        if (!empty($Overdue_today)) {
            foreach ($Overdue_today as $key => $value) {
                $start_data = Db::name('order_ransom_return')->where(['id' => $value['tableid']])->field('return_time,money')->find();
                if (!empty($start_data)) {
                    if ($start_data['return_time'] < $this->yestoday) {//如果实际到账时间不是昨天 那么就是延期回款录系统 
                        $dataArr = $this->prDates($start_data['return_time'], $this->beforyestoday);
                        foreach ($dataArr as $value) {
                            $this->CheckisGetfee(['order_sn' => $order_sn, 'com_day' => $value, 'sta_day' => $start_data['return_time'], 'comput_type' => 4, 'money' => $start_data['money']]);
//                            sock_post('http://127.0.0.1/businesssys_api/public/api/ReturnMoney/CheckisGetfee', ['order_sn' => $order_sn, 'com_day' => $value, 'sta_day' => $start_data['return_time'], 'comput_type' => 4, 'money' => $start_data['money']]);
                        }
                    }
                }
            }
        }
    }

    /*
     * 获取两个date时间之间的日期
     */

    function prDates($start, $end) {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        $dataArr = [];
        while ($dt_start <= $dt_end) {
            $dataArr[] = date('Y-m-d', $dt_start);
            $dt_start = strtotime('+1 day', $dt_start);
        }
        return $dataArr;
    }

    /*
     * 判断当天与展期合同重叠个数
     * $param 所有合同
     */

    public function ChecktodayisSame($param) {
        $data['same_time'] = 0; //今天与展期合同重叠的合同数
        $data['is_frist_exhibition'] = false; //是否是第一次展期
        foreach ($param as $k => $v) {
            if (empty($v['actual_exhibition_endtime'])) {//筛选所有没有展期实际结束时间的展期合同
                $order_exhibition[] = $v;
            }
        }
        foreach ($order_exhibition as $k => $v) {
            $edata = DB::name('order_other_exhibition')->where('order_other_id', $v['id'])->field('order_other_id,exhibition_endtime,exhibition_starttime,exhibition_effective_period,exhibition_rate')->find();
            if ($edata['exhibition_effective_period'] == 1) {//如果剩下的展期合同 是第一次
                $data['is_frist_exhibition'] = true;
            }
            if ($edata['exhibition_starttime'] <= $this->yestoday && $edata['exhibition_endtime'] >= $this->yestoday) {//今天与展期合同重叠的合同数
                $data['order_other_id'] = $edata['order_other_id'];
                $data['exhibition_endtime'] = $edata['exhibition_endtime'];
                $data['exhibition_starttime'] = $edata['exhibition_starttime'];
                $exhibition_rate = $edata['exhibition_rate'];
                $data['same_time'] ++;
            }
        }

        if ($data['same_time'] == 1) {
            $data['exhibition_rate'] = $exhibition_rate;
        } elseif ($data['same_time'] > 1) {//如果多次重叠  取最新一个展期合同 并将其他的展期合同添加展期实际结束时间
            foreach ($order_exhibition as $value) {
                $ids[] = $value['id'];
                $all_exhibition[] = DB::name('order_other_exhibition')->where(['order_other_id' => $value['id']])->field('order_other_id,exhibition_starttime,exhibition_rate,exhibition_endtime')->find();
            }
            $deal_data = $this->getArrayMax($all_exhibition, 'exhibition_endtime');
            $data['order_other_id'] = $deal_data['order_other_id'];
            $data['exhibition_endtime'] = $deal_data['exhibition_endtime']; //筛选完之后  只剩下一个符合条件的合同
            $data['exhibition_starttime'] = $deal_data['exhibition_starttime']; //筛选完之后  只剩下一个符合条件的合同
            $ids = array_diff($ids, [$deal_data['order_other_id']]);
            $ids_str = implode(',', $ids);
            DB::name('order_other_exhibition')->where(['order_other_id' => ['in', $ids_str]])->update(['actual_exhibition_endtime' => $this->beforyestoday, 'actual_exhibition_update_time' => time()]);
            $data['exhibition_rate'] = $deal_data['exhibition_rate'];
        }
        return $data;
    }

    /*
     * 二位数组取最大值pv
     */

    function getArrayMax($arr, $field) {
        foreach ($arr as $k => $v) {
            $temp[] = $v[$field];
        }
        $max_data = max($temp);
        $a = [];
        array_walk($arr, function($value, $key) use ($max_data, &$a) {
            if (array_search($max_data, $value)) {
                $a = $value;
            }
        });
        return $a;
    }

    public function CheckisGetfee($par) {
//        ignore_user_abort(TRUE); //如果客户端断开连接，不会引起脚本abort
//        set_time_limit(0); //取消脚本执行延时上限
//        $par = $this->request->param();
//        $par = json_decode(urldecode($par['query']), true);
        $today = $par['com_day'];
        $yestoday = date('Y-m-d', strtotime('-1 day', strtotime($par['com_day'])));
        $map = ['g.status' => 1, 'o.stage' => ['in', '1014,1015,1016,1017,1018,1019,1020,1025,1024'], 'g.order_sn' => $par['order_sn']];
        $data = Db::name('order_guarantee')
                ->alias('g')
                ->join('__ORDER__ o', 'o.order_sn=g.order_sn')
                ->field('g.out_account_com_total,g.order_sn,g.guarantee_rate,g.ac_guarantee_fee,ac_exhibition_fee,g.info_fee')
                ->where($map)
                ->find();
        if (!empty($data)) {
            try {
                $deal_data = $this->dealwithNormaldata($par);
                $computer_total = $deal_data['computer_total'];
                $wait_money = $deal_data['wait_money'];
                $exhabition_info = Db::name('order_other')->where(['process_type' => 'EXHIBITION', 'stage' => 308, 'status' => 1, 'order_sn' => $data['order_sn']])->field('id')->select(); //所有审批通过的展期合同信息
                $exhabition_times = count($exhabition_info); //展期次数
                $rate = DB::name('order_advance_money')->where(['order_sn' => $data['order_sn'], 'status' => 1])->value('advance_rate'); //今日默认的担保费率
                $totay_fee = sprintf('%.2f', $computer_total * $rate / 100); //今日累计担保费用(默认没有逾期和展期)
                $now_fee_total = Db::name('order_collect_fee')->where(['order_sn' => $data['order_sn'], 'type' => 1, 'status' => 1, 'cal_date' => ['lt', $today]])->sum('money'); //当前正常担保费
                $now_exfee_total = Db::name('order_collect_fee')->where(['order_sn' => $data['order_sn'], 'type' => ['in', '1,2'], 'status' => 1, 'cal_date' => ['lt', $today]])->sum('money'); //当前正常担保费加展期费
                $ac_get_fee = $data['ac_guarantee_fee'] - $data['info_fee']; //实收费用 = 实收担保费-信息费
                $ac_get_fee_exhibition = $data['ac_guarantee_fee'] - $data['info_fee'] + $data['ac_exhibition_fee']; //实收费用 = 实收担保费-信息费+已收展期费;
                $type = 1;
                if ($exhabition_times == 0) {//没有展期合同
                    $ac_fee_total = $totay_fee + $now_fee_total; //累计总费用 = 今日累计费用+当前正常担保费
                    if ($ac_fee_total > $ac_get_fee) {//如果当前累计生实际产费用大于实收费用  按逾期费算 = 当日担保费总额*0.0015
                        $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                        $type = 3;
                        $rate = 0.15;
                    }
                } else {//存在展期合同
                    $type = 2;
                    $returndata = $this->Checkisexhibition($exhabition_info); //判断是否所有展期合同都存在实际展期结束时间
                    if ($returndata['is_exhibition_endtime']) {//如果所有的展期合同都存在实际结束时间  今日费用按逾期算
                        $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                        $type = 3;
                        $rate = 0.15;
                    } else {
                        $return_same_data = $this->ChecktodayisSame($exhabition_info); //判断所有展期合同是否与今日重叠
                        if ($return_same_data['same_time'] == 0) {//没有重叠
                            if (!$return_same_data['is_frist_exhibition']) {//是否是第一次展期
                                $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                                $type = 3;
                                $rate = 0.15;
                            } else {
                                $ac_fee_total = $totay_fee + $now_fee_total; //累计总费用 = 今日累计费用+当前正常担保费
                                if ($ac_fee_total > $ac_get_fee) {//如果当前累计生实际产费用大于实收费用  按逾期费算 = 当日担保费总额*0.0015
                                    $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                                    $type = 3;
                                    $rate = 0.15;
                                } else {
                                    $type = 1;
                                }
                            }
                        } else {
                            $rate = $return_same_data['exhibition_rate'];
                            $totay_fee = sprintf('%.2f', $computer_total * $return_same_data['exhibition_rate'] / 100);
                            $ac_fee_total = $totay_fee + $now_exfee_total; //累计总费用 = 今日展期费用+累计费用
                            if ($ac_fee_total > $ac_get_fee_exhibition) {//如果当前累计生实际产费用大于实收费用  按逾期费算 = 当日担保费总额*0.0015
                                $totay_fee = sprintf('%.2f', $computer_total * 0.0015);
                                $type = 3;
                                $rate = 0.15;
                                DB::name('order_other_exhibition')->where(['order_other_id' => $return_same_data['order_other_id']])->update(['actual_exhibition_endtime' => $yestoday, 'actual_exhibition_day' => $this->diffInDays($return_same_data['exhibition_starttime'], $yestoday), 'actual_exhibition_update_time' => time()]);
                            } else {
                                if ($return_same_data['exhibition_endtime'] == $today) {///如果按正常展期算费还够今日费用但是今天正好是合同结束时间  那就结束当前合同
                                    DB::name('order_other_exhibition')->where(['order_other_id' => $return_same_data['order_other_id']])->update(['actual_exhibition_endtime' => $today, 'actual_exhibition_day' => $this->diffInDays($return_same_data['exhibition_starttime'], $today), 'actual_exhibition_update_time' => time()]);
                                }
                            }
                        }
                    }
                }
                //如果今天跑出来的费用小于0 则默认成0
                $totay_fee < 0 && $totay_fee = 0;
                $data = ['order_sn' => $data['order_sn'], 'cal_money' => $computer_total, 'wait_money' => $wait_money, 'type' => $type, 'rate' => $rate, 'money' => $totay_fee, 'create_uid' => -1, 'create_time' => time(), 'update_time' => time()];
                //存储数据
                if ($par['comput_type'] == 1) {
                    $where = [];
                    $data = array_merge($data, ['cal_date' => $today]);
                    if (!Db::name('order_collect_fee')->insert($data)) {
                        Log::record('添加失败！订单号' . $par['order_sn'] . '，日期为' . $today);
                    }
                } else {
                    $where = ['cal_date' => $today];
                    if (!Db::name('order_collect_fee')->where($where)->update($data)) {
                        Log::record('添加失败！订单号' . $par['order_sn'] . '，日期为' . $today);
                    }
                }
            } catch (\Exception $e) {
                Log::record($e->getCode());
                Log::record($e->getMessage());
            }
        }
    }

    /*
     * 处理异常操作
     * $par 数据
     */

    public function dealwithNormaldata($par) {
        $today = $par['com_day'];
        $yestoday = date('Y-m-d', strtotime('-1 day', strtotime($par['com_day'])));
        if ($par['comput_type'] == 1) {
            $today_back_money = OrderRansomReturn::getReturnMoney(['order_sn' => $par['order_sn'], 'return_time' => $today]);
            $wait_money = Db::name('order_cost_detail')->where(['order_sn' => $par['order_sn'], 'cost_date' => $today])->order('id', 'desc')->value('return_money_wait');
            if (empty($wait_money)) {//如果当天无变动  说明当天没有出账，回款入账操作 那取前一天的定时任务跑出来的待回款金额
                $wait_money = DB::name('order_collect_fee')->where(['order_sn' => $par['order_sn'], 'cal_date' => $yestoday, 'status' => 1])->value('wait_money');
                if (empty($wait_money)) {
                    Log::record('订单号' . $par['order_sn'] . '数据有误！（费用表日期为' . $yestoday . '空）');
                    return true;
                }
            }
            $computer_total = $today_back_money + $wait_money; //当日回款金额+公司累计垫资 = 用来计算担保费总额
        } else {
            $out_account_com_total = DB::name('order_collect_fee')->where(['order_sn' => $par['order_sn'], 'cal_date' => $today, 'status' => 1])->field('wait_money,cal_money')->find();
            if (empty($out_account_com_total)) {
                Log::record('订单号' . $par['order_sn'] . '数据有误！（费用表日期为' . $today . '空）');
                return FALSE;
            }
            if ($par['comput_type'] == 2) {//回款入账延期退回
                $wait_money = $out_account_com_total['wait_money'] + $par['money'];
                if ($par['sta_day'] == $today) {//变动当天数据单独处理
                    $computer_total = $out_account_com_total['cal_money'];
                } else {
                    $computer_total = $out_account_com_total['cal_money'] + $par['money'];
                }
            } elseif ($par['comput_type'] == 3) {//财务出账延期退回
                $wait_money = $out_account_com_total['wait_money'] - $par['money'];
                $computer_total = $out_account_com_total['cal_money'] - $par['money'];
            } elseif ($par['comput_type'] == 4) {//回款入账延期提交
                $wait_money = $out_account_com_total['wait_money'] - $par['money'];
                if ($par['sta_day'] == $today) {//变动当天数据单独处理
                    $computer_total = $out_account_com_total['cal_money'];
                } else {
                    $computer_total = $out_account_com_total['cal_money'] - $par['money'];
                }
            }
            if (Db::name('order_other_exhibition')->where(['actual_exhibition_endtime' => $today])->update(['actual_exhibition_endtime' => null])) {
                log::record('订单号' . $par['order_sn'] . '日期为' . $today . '展期合同实际结束时间取消更新失败');
            }
        }
        return ['wait_money' => $wait_money, 'computer_total' => $computer_total];
    }

}
