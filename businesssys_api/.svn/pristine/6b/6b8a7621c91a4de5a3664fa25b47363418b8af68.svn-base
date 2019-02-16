#!/usr/bin/env php
<?php
define('APP_PATH', __DIR__ . '/application/');
define('BIND_MODULE', 'task/Worker');
// 加载框架基础文件
require __DIR__ . '/thinkphp/base.php';
// 绑定当前入口文件到task模块
\think\Route::bind('task');
//初始化定时任务日志文件保存目录，与其它模块存到相同的目录在linux服务器会出现无权限错误
\think\Log::init(['path' => RUNTIME_PATH . 'tasklog/']);
// 关闭task模块的路由
\think\App::route(false);
// 执行应用
\think\App::run()->send();