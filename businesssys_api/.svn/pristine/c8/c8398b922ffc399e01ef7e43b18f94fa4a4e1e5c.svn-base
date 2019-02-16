<?php

namespace app\model;

class FundBankQuota extends Base {

    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $createTime = 'create_time';

    /**
     * 增存保证金数据判断以及数据更新
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param $data 数据集
     * @author zhongjiaqi 8.2
     */
    public function checkisOvercreditquota($data) {
        //增存后的启用额度=当前启用额度+增存金额/保证金比例    不能大于当前授信额度
        if ($data['type'] == 1) {
            $afterQuota = $data['enable_quota'] + $data['money'] / ($data['deposit_ratio'] / 100); //增存后的启用额度
            $afterDeposit = $data['deposit'] + $data['money']; //增存后的保证金金额
            if ($data['credit_quota'] < $afterQuota) {
                return ['code' => 0, 'msg' => "启用额度不能大于授信额度"];
            }
        } else {
            $afterQuota = $data['enable_quota'] - $data['money'] / ($data['deposit_ratio'] / 100); //解付后的启用额度
            $afterDeposit = $data['deposit'] - $data['money']; //解付后的保证金金额
            if ($afterQuota < $data['stay_quota']) {
                return ['code' => 0, 'msg' => "启用额度不能小于在保额度"];
            }
        }
        if ($this->where(['id' => $data['fund_source_id']])->update(['deposit' => $afterDeposit, 'enable_quota' => $afterQuota]) < 1) {
            return ['code' => 0, 'msg' => "数据无改动"];
        }
        return ['code' => 1];
    }

}
