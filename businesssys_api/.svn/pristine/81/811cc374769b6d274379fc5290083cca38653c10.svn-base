<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/18
 * Time: 18:35
 */

namespace app\model;

class Message extends Base {

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $createTime = 'create_time';

    /*
     * 新增消息推送记录
     * @param $userId 用户id
     * @param $category  '类别，1.APP系统通知 2.APP业务消息'
     * @param $type  消息类型 1.派单(正常派单和改派) 2.财务审批通过 3.财务出账
     * @param $unique_id  '对应业务的唯一ID'
     * @param $order_code  '订单编号，没有的时候不填'
     * @param $order_status  '订单当前状态，没有的时候不填'
     * @param $title  '消息标题'
     * @param $content  '消息内容'
     * @param $is_send  'APP是否已发送 1.未发送 2.已发送'
     * @param $is_read  '是否已读  1未读 2 已读'
     * @param $send_time  '发送时间'
     * @param $read_time  '已读时间'
     * @param $mid  '模块id (根据type)'
     * @param $remarks  '备注'
     * @param $table_name  '表名'
     * zjq 2018.8.27
     */

    public function AddmessageRecord($user_id, $category, $type, $unique_id, $order_code, $order_status, $title, $content, $is_send, $is_read, $send_time, $read_time, $mid = '', $remarks, $table_name = '') {
        $data ['user_id'] = $user_id;
        $data ['category'] = $category;
        $data ['type'] = $type;
        $data ['unique_id'] = $unique_id;
        $data ['table_name'] = $table_name;
        $data ['order_code'] = $order_code;
        $data ['order_status'] = $order_status;
        $data ['title'] = $title;
        $data ['content'] = $content;
        $data ['is_send'] = $is_send;
        $data ['is_read'] = $is_read;
        $data ['send_time'] = $send_time;
        $data ['read_time'] = $read_time;
        $data ['mid'] = $mid;
        $data ['remarks'] = $remarks;
        if (!$this->save($data)) {
            return false;
        }
        return true;
    }

}
