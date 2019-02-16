<?php

//资金相关组件类

namespace app\util;

use think\Db;
use app\model\Dictionary;

class Quota {

    public static function quotabaseinfo($id) {
        $field = 'bank,bank_branch,business_breed,credit_quota,enable_quota,deposit_ratio,deposit,paving_deposit,single_limit,customeranager,mobile,sign_date,due_date,status';
        $data = DB::name('fund_bank_quota')->where('id', $id)->field($field)->find();
        $data['business_breed'] = explode(',', $data['business_breed']);
        $newStageArr = dictionary_reset((new Dictionary)->getDictionaryByType('CAPITAL_BUSINESS_VARIETY'));
        foreach ($data['business_breed'] as $value) {
            $data['business_breed_text'][] = $newStageArr[$value];
        }
        $data['status_text'] = $data['status'] == 1 ? '正常' : '禁用'; //状态（文本）;
        return $data;
    }

    public static function depositLog($fund_source = '', $fund_source_id = '') {
        $field = 'id,type,money,deposit,enable_quota,create_uid,create_time';
        $data = DB::name('fund_deposit')->where(['fund_source' => $fund_source, 'fund_source_id' => $fund_source_id, 'status' => 1])->field($field)->select();
        foreach ($data as &$value) {
            $value['type_text'] = $value['type'] == 1 ? '增存保证金' : '解付保证金';
            $value['username'] = Db::name('system_user')->where('id', $value['create_uid'])->value('name');
            $value['create_time'] = date('Y-m-d H:i', $value['create_time']);
        }
        return $data;
    }

}
