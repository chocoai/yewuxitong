<?php

/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/23
 * Time: 14:06
 */

namespace app\model;

use think\Model;

class Attachment extends Base {

    /**
     * 下载文件
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @param $id 与数据库字段对应的数组集合
     * @author zhongjiaqi 4.27
     */
    public function downFilelocal($id) {
        if ($id) {
            $fileinfo = $this->where('id', $id)->field('name,url,path')->find()->toArray(); // 文件信息
            $fileurl = $fileinfo['path'] . $fileinfo['url']; //绝对路径D:\wamp\www\businesssys_api\public\uploads\20180426\24dc7b794230dc2e5d1d7f75dbacb86d.htm
            header("Cache-Control:");
            header("Cache-Control: public");
            $size = filesize($fileurl);
            //设置输出浏览器格式 
            $data = date('Ymd');
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $data . "|" . $fileinfo['name']);
            header("Accept-Ranges: bytes");
            header("Accept-Length:" . $size);
            readfile($fileurl);
        } else {
            return FALSE;
        }
    }

    /**
     * 查询图片url
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @param $id 图片id
     * @author zhongjiaqi 5.29
     */
    public function getUrl($id) {
        $picinfo = $this->where('id', $id)->field('name,url,id')->find();
        $picinfo['url'] = config('uploadFile.url') . $picinfo['url'];
        return $picinfo;
    }

}
