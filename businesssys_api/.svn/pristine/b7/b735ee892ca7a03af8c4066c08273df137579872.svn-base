<?php

namespace app\task\controller;

use think\Controller;
use Workerman\Lib\Timer;
use think\Log;

class SyncOA extends Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 添加定时器
     *
     */
    public function addTimer() {
        Timer::add(60 * 60, array($this, 'syncUser'), array(), true); //同步OA用户数据
        Timer::add(60 * 60, array($this, 'syncDept'), array(), true); //同步OA部门数据
    }

    /**
     * 同步OA系统用户数据到业务系统
     * 每天 0 点执行，定时器设置 60*60 秒执行一次
     */
    public function syncUser() {
        if (date('H') === '00') {
            $syncUser = new \app\common\controller\OASystem();
            $result = $syncUser->syncSystemUser();
            if ($result['code'] == 1) {
                Log::record($result['msg'], 'info');
            } else {
                Log::record($result['msg'], 'error');
            }
        }
    }

    /**
     * 同步OA系统部门数据到业务系统
     * 每天 0 点执行，定时器设置 60*60 秒执行一次
     */
    public function syncDept() {
        if (date('H') === '00') {
            $syncUser = new \app\common\controller\OASystem();
            $result = $syncUser->syncDept();
            if ($result['code'] == 1) {
                Log::record($result['msg'], 'info');
                return;
            } else {
                Log::record($result['msg'], 'error');
                return;
            }
        }
    }

    private function getSex($sex) {
        switch ($sex) {
            case '男':
                return 1;
                break;
            case '女':
                return 2;
                break;
            default:
                return 0;
        }
    }

}
