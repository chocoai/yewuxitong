<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/21
 * Time: 14:31
 */
namespace app\model;

use think\Db;

class TrialProcess extends Base {

         /*
          * @author 赵光帅
          * 查询出审批页面其他信息
          * @Param {array} $dataInfo    审批注意事项信息
          *
          */

        public static  function show_Other_Information($dataInfo){
            foreach ($dataInfo as $k => $v){
                $fileInfo = Db::name('trial_process_attachment')->alias('a')
                            ->field('b.savename,b.path,b.url,b.name,b.ext')
                            ->join('attachment b','a.attachment_id=b.id','LEFT')
                            ->where(['a.trial_process_id' => $v['id'],'a.delete_time' => NULL])
                            ->select();
                if(!empty($fileInfo)){
                    foreach ($fileInfo as $k2 => $v2){
                       $fileInfo[$k2]['url'] = config('uploadFile.url').$v2['url'];
                    }
                }
                $dataInfo[$k]['fileinfo'] = $fileInfo;
            }
            return $dataInfo;
        }

}