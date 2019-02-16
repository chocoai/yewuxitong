<?php

/**
  赎楼出账表
 * Date: 2018/5/21
 */

namespace app\model;

class OrderAttachment extends Base {

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $createTime = 'create_time';

    /**
     * 新增编辑回款附件处理
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @param $order_sn 订单号  
     * @param $newids 附件数组  
     * @author zhongjiaqi 8.22
     */
    public function filterCreditpic($order_sn, $newids) {
        $where = ['type' => 2, 'order_sn' => $order_sn, 'status' => 1];
        $addids = []; // 新增的图片
        $saveids = []; //与原来的对比 需要保留的图片
        $data = $this->where($where)->column('attachment_id');
        foreach ($newids as $v) {
            if (in_array($v, $data)) {
                $saveids[] = $v;
            } else {
                $addids[] = $v;
            }
        }
        $delids = array_diff($data, $saveids); // 需要删除的图片
        if (!empty($delids))
            foreach ($delids as $key => $value) {
                if (!$this->updateCreditpic($value, ['status' => -1])) {
                    return FALSE;
                }
            }
        $attach_pic = [];
        if (!empty($addids)) {
            foreach ($addids as $key => $value) {
                $attach_pic[] = [
                    'order_sn' => $order_sn,
                    'type' => 2,
                    'attachment_id' => $value
                ];
            }
            $this->saveAll($attach_pic);
        }
        return true;
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
    public function updateCreditpic($aid, $data) {
        $where = ['attachment_id' => $aid,];
        $res = $this->where($where)->update($data);
        return $flag = $res > 0 ? TRUE : FALSE;
    }

}
