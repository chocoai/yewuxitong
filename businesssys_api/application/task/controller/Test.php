<?php

namespace app\task\controller;

use think\Controller;
use Workerman\Lib\Timer;

class Test extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加定时器
     *
     */
    public function addTimer()
    {
        Timer::add(5, array($this, 'test'), array(), true); //同步OA用户数据到业务系统
    }

    public function test()
    {
        echo 'Test' . "*****\n";
    }

}
