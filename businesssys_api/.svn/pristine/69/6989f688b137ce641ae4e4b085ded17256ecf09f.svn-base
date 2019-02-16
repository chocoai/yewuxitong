<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/7/17
 * Time: 8:39
 */
namespace app\admin\controller;


use app\util\ReturnCode;
use app\model\Attachment;
use think\Db;

class Appupload{

    /**
     * @api {post} admin/Index/appFileUploads APP多文件上传[admin/Index/appFileUploads]
     * @apiVersion 1.0.0
     * @apiName appFileUploads
     * @apiGroup public
     * @apiSampleRequest admin/Index/appFileUploads
     *
     *
     * @apiParam {arr}  image    上传文件
     *
     * @apiSuccess {string}  idstr   附件表主键id
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
        "code": 1,
        "msg": "操作成功",
        "data": {
        "idstr": "927,928,929"
        }
    }
     */
    public function appFileUploads(){
        $iamgeArr = request()->file('image');
        if(empty($iamgeArr)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '上传文件不能为空!');
        $path = config('uploadFile.img_path').DS.'uploads';
        $idStr = '';
        // 启动事务
        Db::startTrans();
        foreach ($iamgeArr as $k => $file){
            $info = $file->validate(['size'=>18874368,'ext'=> 'jpg,png,gif,jpeg'])->move($path,true,false);
            //文件地址
            $filePath = config('uploadFile.img_path').DS.'uploads'.DS.$info->getSaveName();
            if($info){
                //图片原始名称
                $imgInfo['name'] = $info->getInFo()['name'];
                //新的图片名称
                $imgInfo['savename'] = $info->getFilename();
                //文件大小
                $imgInfo['filesize'] = $info->getInFo()['size'];
                //文件后缀
                $imgInfo['ext'] = $info->getExtension();
                //缩略图的上传地址
                $date = date('Ymd',time());
                $thumPath = config('uploadFile.thum_path').DS.$date;
                if(!file_exists($thumPath))
                {
                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($thumPath, 0700);
                }
                //缩略图
                $image = \think\Image::open($filePath);
                // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
                $image->thumb(100, 100)->save($thumPath.DS.$imgInfo['savename']);
                //缩略图片的地址
                $imgInfo['thum1'] = config('uploadFile.thum_lujing').DS.$imgInfo['savename'];
                //图片宽
                $imgInfo['imagewidth'] = getimagesize($filePath)[0];
                //图片高
                $imgInfo['imageheight'] = getimagesize($filePath)[1];
                //mime类型
                $imgInfo['mimetype'] = getimagesize($filePath)['mime'];
                //文件类型
                $imgInfo['imagetype'] = $info->getInFo()['type'];
                //获取上传文件的hash散列值
                $imgInfo['md5'] = $info->hash('md5');
                $imgInfo['sha1'] = $info->hash('sha1');
                //路径
                $imgInfo['path'] = config('uploadFile.img_path');
                //连接地址
                $imgInfo['url'] = DS .'uploads'.DS .$info->getSaveName();
                //上传时间
                $imgInfo['create_time'] = time();
                $resImg = Attachment::create($imgInfo);
                if(empty($resImg)){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '添加附件表失败');
                }
                $idStr .= $resImg->id.',';
            }else{
                // 回滚事务
                Db::rollback();
                // 上传失败获取错误信息
                return $this->buildFailed(ReturnCode::FILE_SAVE_ERROR, $file->getError());
            }
        }
        // 提交事务
        Db::commit();
        return $this->buildSuccess(['idstr' => rtrim($idStr,',')],'上传成功');

    }

    public function buildSuccess($data='', $msg = '操作成功', $code = ReturnCode::SUCCESS) {
        $return = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];

        return $return;
    }

    public function buildFailed($code, $msg, $data = []) {
        $return = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];
        return $return;
    }

    /**
     * @api {post} admin/Index/app_Uploads base64多文件上传[admin/Index/app_Uploads]
     * @apiVersion 1.0.0
     * @apiName app_Uploads
     * @apiGroup public
     * @apiSampleRequest admin/Index/app_Uploads
     *
     *
     * @apiParam {arr}  image    base64文件
     * @apiParam {arr}  oldImageName   原文件名称
     *
     * @apiSuccess {string}  idstr   附件表主键id
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     * {
        "code": 1,
        "msg": "操作成功",
        "data": {
                 "idstr": "927,928,929"
                }
        }
     */
    public function app_Uploads(){
        $base64_img = input('image/a');
        //$oldImageName = input('oldImageName/a');
        //$oldImageName = ['a.png','b.png','c.png'];
        if(empty($base64_img)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, "上传文件不能为空!");
        //if(empty($oldImageName)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, "原文件名称不能为空!");
        //匹配出图片的格式
        $date = date('Ymd',time());
        $up_dir = config('uploadFile.img_path').'uploads'.DS.$date;//存放在当前目录的upload文件夹下
        if(!file_exists($up_dir)){
            mkdir($up_dir,0777);
        }
        $idStr = '';
        // 启动事务
        Db::startTrans();
        foreach ($base64_img as $k => $v){
            //匹配出图片的格式
            if(!preg_match('/^(data:\s*image\/(\w+);base64,)/', $v, $result)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, "请上传base64文件!");
            $type = $result[2];
            //判断库中是否存在
            $id = Db::name('Attachment')->where([
                'md5' => md5(str_replace($result[1], '', $v)),
                'sha1' => sha1(str_replace($result[1], '', $v)),
            ])->value('id');
            if($id) {
                $idStr .= $id . ',';
                continue;
            }
            if(!in_array($type,array('jpeg','jpg','gif','png','webp'))) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, "请上传图片类型的文件!");
            $newImgName = mt_rand(10000,99999).date('YmdHis_').'.'.$type;
            $new_file = $up_dir.DS.$newImgName;
            if(!file_put_contents($new_file, base64_decode(str_replace($result[1], '', $v)))) return $this->buildFailed(ReturnCode::FILE_SAVE_ERROR, "上传失败!");

            //图片原始名称
            $imgInfo['name'] = 'app_'.md5(str_replace($result[1], '', $v)) . '.' . $type;
            //新的图片名称
            $imgInfo['savename'] = $newImgName;
            //文件大小
            $imgInfo['filesize'] = filesize($new_file);
            //文件后缀
            $imgInfo['ext'] = $type;

            //缩略图的上传地址
            $date = date('Ymd',time());
            $thumPath = config('uploadFile.thum_path').DS.$date;
            if(!file_exists($thumPath))
            {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($thumPath, 0700);
            }
            //缩略图
            $image = \think\Image::open($new_file);
            // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
            $image->thumb(100, 100)->save($thumPath.DS.$newImgName);
            //缩略图片的地址
            $imgInfo['thum1'] = config('uploadFile.thum_lujing').DS.$newImgName;
            //图片宽
            $imgInfo['imagewidth'] = getimagesize($new_file)[0];
            //图片高
            $imgInfo['imageheight'] = getimagesize($new_file)[1];
            //mime类型
            $imgInfo['mimetype'] = getimagesize($new_file)['mime'];

            //文件类型
            $imgInfo['imagetype'] = getimagesize($new_file)['mime'];
            //获取上传文件的hash散列值
            $imgInfo['md5'] = md5(str_replace($result[1], '', $v));
            $imgInfo['sha1'] = sha1(str_replace($result[1], '', $v));
            //路径
            $imgInfo['path'] = config('uploadFile.img_path');
            //连接地址
            $imgInfo['url'] = DS .'uploads'.DS .$date.DS.$newImgName;
            //上传时间
            $imgInfo['create_time'] = time();

            $resImg = Attachment::create($imgInfo);
            if(empty($resImg)){
                // 回滚事务
                Db::rollback();
                return $this->buildFailed(ReturnCode::UPDATE_FAILED, '添加附件表失败');
            }
            $idStr .= $resImg->id.',';
            //return $this->buildSuccess(['id' => $resImg->id,'url' =>config('uploadFile.url').$imgInfo['url'],'thumb_url' => config('uploadFile.thum_url').DS.$newImgName]);
        }
        // 提交事务
        Db::commit();
        return $this->buildSuccess(['idstr' => rtrim($idStr,',')]);
        //return $this->buildSuccess(['id' => $resImg->id]);
    }





}