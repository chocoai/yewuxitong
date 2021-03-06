<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/18
 * Time: 18:35
 */

namespace app\model;

use think\Db;

class OrderVerifyCard extends Base {

    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $createTime = 'create_time';

    /**
     * 获取核卡状态
     * @return 核卡状态
     * @throws \think\db\exception\DataNotFoundException
     * @param $status  核卡状态
     * @author zhongjiaqi 6.27
     */
    public function getCheckstatus($status = '') {
        $statusList = ['0' => '待核卡', '1' => '待财务复核', '2' => '驳回待处理', '3' => '已完成'];
        return  $statusList[$status];
    }

    /**
     * 获取账户状态
     * @return 账户状态
     * @throws \think\db\exception\DataNotFoundException
     * @param $status  账户状态
     * @author zhongjiaqi 6.27
     */
    public function getAccountstatus($status = '') {
        $statusList = ['1' => '正常', '2' => '冻结', '3' => '锁卡', '4' => '挂失', '5' => '注销'];
        return empty($status) ? '' : $statusList[$status];
    }

    /**
     * 获取账户类型
     * @return 账户类型
     * @throws \think\db\exception\DataNotFoundException
     * @param $id  担保赎楼银行账号id
     * @author zhongjiaqi 6.27
     */
    public function getAccounttype($id = '') {
        $statusList = ['1' => '赎楼卡', '2' => '尾款卡', '3' => '过账卡', '4' => '回款卡', '5' => '监管卡', '6' => '收款卡'];
        if(empty($id)) return "";

        $typeArr = Db::name('order_guarantee_bank_type')->where(['order_guarantee_bank_id' => $id])->column('type');
        if(empty($typeArr)) return "";

        $statusStr = '';
        foreach ($typeArr as $k => $v){
            $statusStr .= $statusList[$v].',';
        }
        return trim($statusStr,',');
    }

    /**
     * 获取开通类型
     * @return 开通类型
     * @throws \think\db\exception\DataNotFoundException
     * @param $status  开通类型
     * @author zhongjiaqi 6.27
     */
    public function getOpentype($status = '') {
        $statusList = ['0' => '未开通', '1' => '已开通', '2' => '已关闭'];
        return  $statusList[$status];
    }

    /**
     * 获取绑定类型
     * @return 绑定类型
     * @throws \think\db\exception\DataNotFoundException
     * @param $status  绑定类型
     * @author zhongjiaqi 6.27
     */
    public function getBindtype($status = '') {
        $statusList = ['0' => '未绑定', '1' => '已绑定', '2' => '已解绑'];
        return $statusList[$status];
    }

}
