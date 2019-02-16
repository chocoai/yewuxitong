<?php

return [
    '[api]' => [
        //额度发送指令
        'AppFlow/linesSendInstruct' => [
            'api/AppFlow/linesSendInstruct',
            ['method' => 'get']
        ],
        //同步OA用户
        'SystemUser/syncSystemUser' => [
            'api/SystemUser/syncSystemUser',
            ['method' => 'get']
        ],
        //同步OA部门
        'SystemUser/syncDept' => [
            'api/SystemUser/syncDept',
            ['method' => 'get']
        ],
        //同步预计出账总额
        'Order/totalAmountsame' => [
            'api/Order/totalAmountsame',
            ['method' => 'get']
        ],
        //获取登录授权
        'login/loginSecret' => [
            'api/login/loginSecret',
            ['method' => 'get']
        ],
        //授权登录校验
        'login/checkCode' => [
            'api/login/checkCode',
            ['method' => 'post']
        ],
        //同步OA公司id
        'SystemUser/syncCompanyId' => [
            'api/SystemUser/syncCompanyId',
            ['method' => 'get']
        ],
        //同步订单公司id
        'SystemUser/syncOrderCompanyId' => [
            'api/SystemUser/syncOrderCompanyId',
            ['method' => 'get']
        ],
        //app驳回派单接口
        'AppFlow/Reject' => [
            'api/AppFlow/Reject',
            ['method' => 'post']
        ],
        //额度发送指令
        'AppFlow/linesSendInstruct' => [
            'api/AppFlow/linesSendInstruct',
            ['method' => 'post']
        ],
        //定时任务 异步加载
        'ReturnMoney/CheckisGetfee' => [
            'api/ReturnMoney/CheckisGetfee',
            ['method' => 'post']
        ],
        '__miss__' => ['wiki/index/index'],
    ],
];
