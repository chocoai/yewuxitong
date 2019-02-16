<?php

namespace app\api\controller;

use think\Controller;
use think\Log;
use think\Db;
use app\model\OrderRansomReturn;
use app\model\OrderCollectFee;

class ReturnMoney extends Controller {

    private $ordercollectfee;

    public function _initialize() {
        parent::_initialize();
        $this->ordercollectfee = new OrderCollectFee();
    }

    /*
     * 如果回款定时任务历史数据有 但是前天没有  补上历史数据最新日期后一天到前天的记录
     * $par order_sn订单编号  com_day 需要补上的日期  comput_type 计算类型
     * comput_type==1 注意定时任务当天执行是前一天的数据  所以如果发现前天没有的话（因为昨天的是要今天跑） 说明缺失数据
     * comput_type==2 回款入账隔天被作废
     * comput_type==3 赎楼出账被退回
     * comput_type==4 回款入账被核算
     */

    public function CheckisGetfee() {
        ignore_user_abort(TRUE); //如果客户端断开连接，不会引起脚本abort
        set_time_limit(0); //取消脚本执行延时上限
        $par = $this->request->param();
        $par = json_decode(urldecode($par['query']), true);
        if (empty($par)) {
            Log::write('参数有误！');
            return true;
        }
        $this->today = $par['com_day'];
        $this->yestoday = date('Y-m-d', strtotime('-1 day', strtotime($par['com_day'])));
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
                $now_fee_total = Db::name('order_collect_fee')->where(['order_sn' => $data['order_sn'], 'type' => 1, 'status' => 1])->sum('money'); //当前正常担保费
                $now_exfee_total = Db::name('order_collect_fee')->where(['order_sn' => $data['order_sn'], 'type' => ['in', '1,2'], 'status' => 1])->sum('money'); //当前正常担保费加展期费
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
                                DB::name('order_other_exhibition')->where(['order_other_id' => $return_same_data['order_other_id']])->update(['actual_exhibition_endtime' => $this->yestoday, 'actual_exhibition_update_time' => time()]);
                            } else {
                                if ($return_same_data['exhibition_endtime'] == $this->today) {///如果按正常展期算费还够今日费用但是今天正好是合同结束时间  那就结束当前合同
                                    DB::name('order_other_exhibition')->where(['order_other_id' => $return_same_data['order_other_id']])->update(['actual_exhibition_endtime' => $this->today, 'actual_exhibition_update_time' => time()]);
                                }
                            }
                        }
                    }
                }
                //如果今天跑出来的费用小于0 则默认成0
                $totay_fee < 0 && $totay_fee = 0;
                $data = ['order_sn' => $data['order_sn'], 'wait_money' => $wait_money, 'type' => $type, 'rate' => $rate, 'money' => $totay_fee, 'create_uid' => -1, 'create_time' => time(), 'update_time' => time()];
                //存储数据
                if ($par['comput_type'] == 1) {
                    $where = [];
                    $data = array_merge($data, ['cal_date' => $this->today]);
                    if (!$Db::name('order_collect_fee')->insert($data)) {
                        Log::write('添加失败！订单号' . $par['order_sn'] . '，日期为' . $this->today);
                    }
                } else {
                    $where = ['cal_date' => $this->today];
                    if (!$Db::name('order_collect_fee')->where($where)->update($data)) {
                        Log::write('添加失败！订单号' . $par['order_sn'] . '，日期为' . $this->today);
                    }
                }
            } catch (\Exception $e) {
                Log::write($e->getCode());
                Log::write($e->getMessage());
            }
        }
    }

    /*
     * 处理异常操作
     * $par 数据
     */

    public function dealwithNormaldata($par) {
        if ($par['comput_type'] == 1) {
            $today_back_money = OrderRansomReturn::getReturnMoney(['order_sn' => $par['order_sn'], 'return_time' => $this->today]);
            $wait_money = Db::name('order_cost_detail')->where(['order_sn' => $par['order_sn'], 'cost_date' => $this->today])->order('id', 'desc')->value('return_money_wait');
            if (empty($wait_money)) {//如果当天无变动  说明当天没有出账，回款入账操作 那取前一天的定时任务跑出来的待回款金额
                $wait_money = DB::name('order_collect_fee')->where(['order_sn' => $par['order_sn'], 'cal_date' => $this->yestoday, 'status' => 1])->value('wait_money');
                if (empty($wait_money)) {
                    Log::write('订单号' . $par['order_sn'] . '数据有误！（费用表日期为' . $this->yestoday . '空）');
                    return true;
                }
            }
            $computer_total = $today_back_money + $wait_money; //当日回款金额+公司累计垫资 = 用来计算担保费总额
        } else {
            $out_account_com_total = DB::name('order_collect_fee')->where(['order_sn' => $par['order_sn'], 'cal_date' => $this->today, 'status' => 1])->field('wait_money,cal_money')->find();
            if (empty($out_account_com_total)) {
                Log::write('订单号' . $par['order_sn'] . '数据有误！（费用表日期为' . $this->today . '空）');
                return FALSE;
            }
            if ($par['comput_type'] == 2) {//回款入账延期退回
                $wait_money = $out_account_com_total['wait_money'] - $par['money'];
                if ($par['sta_day'] == $this->today) {//变动当天数据单独处理
                    $computer_total = $out_account_com_total['cal_money'];
                } else {
                    $computer_total = $out_account_com_total['cal_money'] - $par['money'];
                }
            } elseif ($par['comput_type'] == 3) {//财务出账延期退回
                $wait_money = $out_account_com_total['wait_money'];
                if ($par['sta_day'] == $this->today) {//变动当天数据单独处理
                    $computer_total = $out_account_com_total['cal_money'] + $par['money'];
                } else {
                    $computer_total = $out_account_com_total['cal_money'];
                }
            } elseif ($par['comput_type'] == 4) {//回款入账延期提交
                $wait_money = $out_account_com_total['wait_money'] + $par['money'];
                if ($par['sta_day'] == $this->today) {//变动当天数据单独处理
                    $computer_total = $out_account_com_total['cal_money'];
                } else {
                    $computer_total = $out_account_com_total['cal_money'] + $par['money'];
                }
            }
            if (Db::name('order_other_exhibition')->where(['actual_exhibition_endtime' => $this->today])->update(['actual_exhibition_endtime' => null])) {
                log::write('订单号' . $par['order_sn'] . '日期为' . $this->today . '展期合同实际结束时间取消更新失败');
            }
        }
        return ['wait_money' => $wait_money, 'computer_total' => $computer_total];
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
            if ($edata['exhibition_starttime'] <= $this->today && $edata['exhibition_endtime'] >= $this->today) {//今天与展期合同重叠的合同数
                $data['order_other_id'] = $edata['order_other_id'];
                $data['exhibition_endtime'] = $edata['exhibition_endtime'];
                $exhibition_rate = $edata['exhibition_rate'];
                $data['same_time'] ++;
            }
        }

        if ($data['same_time'] == 1) {
            $data['exhibition_rate'] = $exhibition_rate;
        } elseif ($data['same_time'] > 1) {//如果多次重叠  取最新一个展期合同 并将其他的展期合同添加展期实际结束时间
            foreach ($order_exhibition as $value) {
                $ids[] = $value['id'];
                $all_exhibition[] = DB::name('order_other_exhibition')->where(['order_other_id' => $value['id']])->field('order_other_id,exhibition_rate,exhibition_endtime')->find();
            }
            $deal_data = $this->getArrayMax($all_exhibition, 'exhibition_endtime');
            $data['order_other_id'] = $deal_data['order_other_id'];
            $data['exhibition_endtime'] = $deal_data['exhibition_endtime']; //筛选完之后  只剩下一个符合条件的合同
            $ids = array_diff($ids, [$deal_data['order_other_id']]);
            $ids_str = implode(',', $ids);
            DB::name('order_other_exhibition')->where(['order_other_id' => ['in', $ids_str]])->update(['actual_exhibition_endtime' => $this->yestoday, 'actual_exhibition_update_time' => time()]);
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

}
