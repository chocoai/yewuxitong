<?php

/**
 * 工作流路由
 * User: bordon
 */
return [
    '[workflow]' => [
        'flow/index' => [
            'workflow/flow/index',
            ['method' => 'get']
        ],
        'flow/publish' => [
            'workflow/flow/publish',
            ['method' => 'post']
        ],
        'flow/add' => [
            'workflow/flow/add',
            ['method' => 'get']
        ],
        'flow/edit' => [
            'workflow/flow/edit',
            ['method' => 'get']
        ],
        'flow/del' => [
            'workflow/flow/del',
            ['method' => 'get']
        ],
        'flow/forbid' => [
            'workflow/flow/forbid',
            ['method' => 'get']
        ],
        'flow/resume' => [
            'workflow/flow/resume',
            ['method' => 'get']
        ],
        'flow/create' => [
            'workflow/flow/create',
            ['method' => 'post']
        ],
        'flow/design' => [
            'workflow/flow/design',
            ['method' => 'get']
        ],
        'flow/test' => [
            'workflow/flow/test',
            ['method' => 'get']
        ],
        'process/store' => [
            'workflow/process/store',
            ['method' => 'post']
        ],
        'process/update' => [
            'workflow/process/update',
            ['method' => 'post']
        ],
        'process/destroy' => [
            'workflow/process/destroy',
            ['method' => 'post']
        ],
        'process/attribute' => [
            'workflow/process/attribute',
            ['method' => 'get']
        ],
        'process/condition' => [
            'workflow/process/condition',
            ['method' => 'post']
        ],
        'process/setFirst' => [
            'workflow/process/setFirst',
            ['method' => 'post']
        ],
        'process/setLast' => [
            'workflow/process/setLast',
            ['method' => 'post']
        ],
        'flowlink/update' => [
            'workflow/flowlink/update',
            ['method' => 'post']
        ],
        'flowlink/dept' => [
            'workflow/flowlink/dept',
            ['method' => 'get']
        ],
        'flowlink/role' => [
            'workflow/flowlink/role',
            ['method' => 'get']
        ],
        'flowlink/get_auth_list' => [
            'workflow/flowlink/get_auth_list',
            ['method' => 'post']
        ],
        'flowlink/emp' => [
            'workflow/flowlink/emp',
            ['method' => 'get']
        ],
        'flowlink/get_user_list' => [
            'workflow/flowlink/get_user_list',
            ['method' => 'post']
        ],
        'proc/index' => [
            'workflow/proc/index',
            ['method' => 'get']
        ],
        'proc/unpass' => [
            'workflow/proc/unpass',
            ['method' => 'post']
        ],
        'proc/pass' => [
            'workflow/proc/pass',
            ['method' => 'post']
        ],
        'proc/resend' => [
            'workflow/proc/resend',
            ['method' => 'post']
        ],
        'proc/show' => [
            'workflow/proc/show',
            ['method' => 'get']
        ],
        'login/index' => [
            'workflow/login/index',
            ['method' => 'get']
        ],
        'login/login' => [
            'workflow/login/login',
            ['method' => 'post']
        ],
        'login/loginout' => [
            'workflow/login/loginout',
            ['method' => 'get']
        ],
        '__miss__' => ['workflow/flow/index'],
    ],
];