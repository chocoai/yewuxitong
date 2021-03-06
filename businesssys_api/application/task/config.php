<?php

return [
    // 应用模式状态
    'url_route_on' => false,

    // APP推送基础URL
    'app_push_base_url' => 'http://119.23.24.187/business_backend/public/back.php/backend_api/v103/',

    //
    'cache' => [
        'type' => 'redis', // 驱动方式
        'host' => '127.0.0.1', // 服务器地址
        'prefix' => 'backend_test', // 缓存前缀
        'expire' => 864000, // 缓存有效期 0表示永久缓存 10天
    ],

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log' => [
        // 日志记录方式，内置 file socket 支持扩展
        'type' => 'File',
        // 日志保存目录
        //'path' => LOG_PATH . 'tasklog/',//已在入口文件server.php初始化
        // 日志记录级别
        'level' => [],
    ],

];
