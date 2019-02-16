<?php


/**
 * Created by PhpStorm.
 * User: bordon
 * Date: 2018-04-19
 * Time: 8:57
 */
return [
    ['name' => '1', 'title' => '业务委托', 'icon' => 'ios-paper', 'module' => '0', 'children' => [
        [
            'path' => '/quota',
            'icon' => 'wrench',
            'name' => 'quota',
            'title' => '额度类',
            'component' => 'Main',
            'children' => [
                [
                    'path' => 'guarantee',
                    'icon' => 'navicon-round',
                    'name' => 'quota_guarantee',
                    'title' => '交易担保',
                    'component' => 'quota/guarantee',
                ],
                [
                    'path' => 'record',
                    'icon' => 'navicon-round',
                    'name' => 'quota_record',
                    'title' => '查档记录',
                    'component' => 'quota/record',
                ]
            ],
        ]
    ]],
    ['name' => '2', 'title' => '风控管理', 'icon' => 'ios-people', 'module' => '1', 'children' => [
        [
            'path' => '/app',
            'icon' => 'android-cloud',
            'name' => 'app',
            'title' => '审批管理',
            'component' => 'Main',
            'children' => [
                [
                    'path' => "group",
                    'icon' => "ios-box",
                    'name' => "app_group",
                    'title' => "待审业务",
                    'component' => 'app/group',
                ],
                [
                    'path' => 'index',
                    'icon' => 'navicon-round',
                    'name' => 'app_index',
                    'title' => '其他待审',
                    'component' => 'app/list',
                ]
            ]
        ],
        [
            'path' => '/credit',
            'icon' => 'android-cloud',
            'name' => 'credit',
            'title' => '征信查询',
            'component' => 'Main',
            'children' => [
                [
                    'path' => "list",
                    'icon' => "ios-box",
                    'name' => "credit_list",
                    'title' => "征信查询",
                    'component' => 'credit/list',
                ]
            ]
        ],
    ]],
    ['name' => '3', 'title' => '赎楼管理', 'icon' => 'ios-keypad', 'module' => '2', 'children' => []],
//            'finance'=>['name' => '4', 'title' => '财务管理', 'icon' => 'ios-people', 'module' => 'finance','children'=>[]],
//            'warrant'=>['name' => '5', 'title' => '权证管理', 'icon' => 'ios-navigate', 'module' => 'warrant','children'=>[]]
];