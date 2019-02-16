<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    'APP_VERSION' => 'v1.0',
    'APP_NAME' => 'BusinessSysApi',

    //鉴权相关
    'USER_ADMINISTRATOR' => [1],

    //安全秘钥
    'AUTH_KEY' => 'I&TC{pft>L,C`wFQ>&#ROW>k{Kxlt1>ryW(>r<#R',

    //后台登录状态维持时间[目前只有登录和解锁会重置登录时间]
    'ONLINE_TIME' => 28800,
    //AccessToken失效时间
    'ACCESS_TOKEN_TIME_OUT' => 28800,
    'COMPANY_NAME' => '房帮帮',

    //跨域配置
    'CROSS_DOMAIN' => [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST,PUT,GET,DELETE',
        'Access-Control-Allow-Headers' => 'ApiAuth,Category, User-Agent, Keep-Alive, Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With',
        'Access-Control-Allow-Credentials' => 'true',
    ],

    //后台列表默认一页显示数量
    'ADMIN_LIST_DEFAULT' => 10,

    'AUTH_SECRET' => [
        'OA' => ['secret' => 'zcjfoa', 'url' => 'http://119.23.24.187/zcoa/?m=login&a=authlogin&'],
        //'OA' => ['secret'=>'fsafsfsdafdasfdsfsdf','url'=>'http://test.zcoa.com?m=login&a=authlogin&'],
        'ZCDC' => ['secret' => 'dsafsdfasdfdsafaerwqrfsd', 'url' => 'http://119.23.12.189/testzcdc.ezczx.com/public/api/loginauth/authLogin.html?'], //楼盘字典
    ],
];
