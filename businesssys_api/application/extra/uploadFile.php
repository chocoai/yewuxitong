<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/23
 * Time: 13:58
 */
/**
 * 上传文件配置
 */
return [

    //上传文件的路径
    'img_path' => ROOT_PATH . 'public' . DS ,
    //缩略图地址
    'thum_path' => ROOT_PATH . 'public' . DS . 'uploads' . DS .'thum',
    //缩略图路径
    'thum_lujing' => 'uploads' . DS .'thum'. DS .date('Ymd',time()),
    //缩略图url
    'thum_url' => 'http://119.23.24.187'. DS .'businesssys_api'. DS .'public'. DS .'uploads'. DS .'thum'. DS .date('Ymd',time()),
    //虚拟主机地址
    'url' =>'http://119.23.24.187'. DS .'businesssys_api'. DS .'public',
    //上传文件类型
    'file_type' =>'jpg,png,gif,jpeg,pdf,doc,docx,xls,xlsx,html,htm,txt'

];