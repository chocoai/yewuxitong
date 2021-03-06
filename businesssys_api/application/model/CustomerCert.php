<?php

namespace app\model;

use think\Model;

class CustomerCert extends Model {

    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $createTime = 'create_time';

    /**
     * 获取用户证件信息
     * @return bool/数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param $customer_id 用户id 
     * @author zhongjiaqi 5.7
     */
    public function Getcertinfo($customer_id) {
        $flag = false;
        if ($customer_id) {
            $where = [
                'customer_id' => $customer_id,
                'status' => 1
            ];
            $this->cus = new Customer();
            $this->dictionary = new Dictionary();
            $typecode = $this->cus->where('id', $customer_id)->value('ctype') == 1 ? 'CERTTYPE' : 'ENTERPRICE_CERTTYPE';
            $certinfo = $this->where($where)->field('certtype,certcode')->select();
            if ($certinfo) {
                foreach ($certinfo as $key => $value) {
                    $certinfo[$key]['certname'] = $this->dictionary->getValnameByCode($typecode, $value['certtype']);
                }
                return $certinfo;
            }
            return $flag;
        }
        return $flag;
    }

    /**
     * 编辑征信申请证件处理（筛选与数据库原有数据对比，存在的保留，不存在的新增，缺少的删除）
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @param $customer_id 用户id   $newdata 证件数据集
     * @author zhongjiaqi 5.7
     */
    public function filterCreditcert($customer_id, $newdata) {
        $where = [
            'customer_id' => $customer_id,
            'status' => 1
        ];
        $addids = []; // 新增的证件类型
        $addcode = []; // 新增的证件号码
        $saveids = []; //与原来的对比 需要保留的证件类型
        $data = $this->where($where)->column('certtype');
        foreach ($newdata as $k => $v) {
            if (in_array($v['certtype'], $data)) {
                $saveids[] = $v['certtype'];
            } else {
                $addids[] = $v['certtype'];
                $addcode[] = $v['certcode'];
            }
        }
        $delids = array_diff($data, $saveids); // 需要删除的证件
        if (!empty($delids))
            foreach ($delids as $key => $value) {
                $this->where(['customer_id' => $customer_id, 'certtype' => $value])->update(['status' => -1]);
            }
        $attach_pic = [];
        if (!empty($addids)) {
            foreach ($addids as $key => $value) {
                $attach_pic[] = [
                    'customer_id' => $customer_id,
                    'certtype' => $value,
                    'certcode' => $addcode[$key]
                ];
            }
        }
        $this->saveAll($attach_pic);
        return $attach_pic;
    }

}
