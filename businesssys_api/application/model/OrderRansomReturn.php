<?php

/**
 * 赎楼出账表
 * Date: 2018/5/21
 */

namespace app\model;

use think\Db;

class OrderRansomReturn extends Base
{

    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $createTime = 'create_time'; //1回款入账待复核 2回款入账待核算 3回款入账已完成 4驳回待处理 -1已作废
    public static $intoStatusMap = [
        1 => '回款入账待复核',
        2 => '回款入账待核算',
        3 => '回款入账已完成',
        4 => '驳回待处理',
        -1 => '已作废'
    ];

    /**获取已收回款金额
     * @param $where
     * @return float|int
     * @author: bordon
     */
    public static function getReturnMoney($where)
    {
        $money = self::where($where)
            ->where('status',1)
            ->where('(return_money_into_status IN (2, 3)) OR (return_money_into_status = 1 AND is_rebut = 1)')
            ->sum('money');
        return $money ? $money : 0;
    }

    /**
     * 更新附件
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @param $order_sn 订单号
     * @param $aid 附件id
     * @param $data 修改数据集
     * @author zhongjiaqi 8.22
     */
    public function updateCreditpic($order_sn, $aid, $data)
    {
        $where = [
            'type' => 2,
            'order_sn' => $order_sn,
            'attachment_id' => $aid,
            'status' => 1
        ];
        $res = Db::name('order_attachment')->where($where)->update($data);
        return $flag = $res > 0 ? TRUE : FALSE;
    }

    /**
     * 获取核卡状态
     * @return 核卡状态
     * @throws \think\db\exception\DataNotFoundException
     * @param $status  核卡状态
     * @author zhongjiaqi 6.27
     */
    public function getReturnMoneyIntoStatus($status = '')
    {
        $statusList = ['1' => '回款入账待复核', '2' => '回款入账待核算', '3' => '回款入账已完成', '4' => '驳回待处理', '-1' => '已作废'];
        return $statusList[$status];
    }

}
