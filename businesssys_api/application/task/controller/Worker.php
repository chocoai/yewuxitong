<?php

namespace app\task\controller;

use think\worker\Server;

class Worker extends Server
{
    protected $port      = '2347';
    protected $processes = 3;

    public function onWorkerStart($work)
    {
        if($this->worker->id == 0) {
            // APP定时任务
            $app_handle = new AppTask();
            $app_handle->addTimer();
        }

        if($this->worker->id == 1) {
            // 回款定时任务
            $returnMoney_handle = new ReturnMoney();
            $returnMoney_handle->addTimer();
        }
        
        if($this->worker->id == 2) {
            // 同步OA系统数据到业务系统
            $syncOA_handle = new SyncOA();
            $syncOA_handle->addTimer();
        }
    }

    // protected $socket = 'websocket://127.0.0.1:2346';

    // /**
    //  * 收到信息
    //  * @param $connection
    //  * @param $data
    //  */
    // public function onMessage($connection, $data)
    // {
    //     $connection->send('我收到你的信息了');
    // }

    // /**
    //  * 当连接建立时触发的回调函数
    //  * @param $connection
    //  */
    // public function onConnect($connection)
    // {

    // }

    // /**
    //  * 当连接断开时触发的回调函数
    //  * @param $connection
    //  */
    // public function onClose($connection)
    // {

    // }

    // /**
    //  * 当客户端的连接上发生错误时触发
    //  * @param $connection
    //  * @param $code
    //  * @param $msg
    //  */
    // public function onError($connection, $code, $msg)
    // {
    //     echo "error $code $msg\n";
    // }

    // /**
    //  * 每个进程启动
    //  * @param $worker
    //  */
    // public function onWorkerStart($worker)
    // {

    // }
}
