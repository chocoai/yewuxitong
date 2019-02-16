<?php
/**
 * home路由 后台首页
 */
$afterBehavior = [
     '\app\admin\behavior\ApiAuth',
//     '\app\admin\behavior\ApiPermission',
//         '\app\admin\behavior\SystemLog',
];

return [
    '[home]' => [
        //新闻列表
        'News/newList' => [
            'home/News/newList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加或者编辑新闻
        'News/addNew' => [
            'home/News/addNew',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //新闻详情
        'News/newDetail' => [
            'home/News/newDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //删除新闻
        'News/delNew' => [
            'home/News/delNew',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //待审批列表
        'News/processList' => [
            'home/News/processList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //添加或编辑广告位
        'News/addBanner' => [
            'home/News/addBanner',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //首页轮播图列表/
        'News/bannerList' => [
            'home/News/bannerList',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //首页轮播图详情
        'News/bannerDetail' => [
            'home/News/bannerDetail',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //拖动排序接口
        'News/bannerOrderBy' => [
            'home/News/bannerOrderBy',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //首页banner图展示信息
        'News/indexBannerInfo' => [
            'home/News/indexBannerInfo',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        //删除首页banner图
        'News/delIndexBanner' => [
            'home/News/delIndexBanner',
            ['method' => 'post', 'after_behavior' => $afterBehavior],
        ],
        '__miss__' => ['admin/Miss/index'],
    ],
];
