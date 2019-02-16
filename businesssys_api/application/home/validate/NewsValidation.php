<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/31
 * Time: 17:53
 */
namespace app\home\validate;
use think\Validate;


class NewsValidation extends Validate{

    protected $rule = [
        'type|新闻类型' =>'require|max:1|number',
        'img1|新闻图片'  =>  'require|min:1',
        'title|新闻标题' =>  'require|max:50',
        'summary|新闻摘要'=> 'max:100',
        'source|新闻来源' =>'require|max:15',
        'author|新闻作者'  =>  'require|max:10',
        'content|新闻内容' =>  'require'
    ];

}