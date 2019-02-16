<?php

namespace app\admin\controller;

use app\model\Update;
use app\util\ReturnCode;
use think\Validate;
/**
 * APP版本更新
 * User: turun
 * Date: 2018/6/20
 * Time: 11:15
 */
class AppUpdate extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 列表
	 */
	public function index() {
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : 10;
		$list = Update::getInfos(['delete_time'=>null], $page, $pageSize);
		if(empty($list['data'])) return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '暂无记录');
		foreach ($list['data'] as $key => &$value) {
			$value['force'] = $value['force'] == 1 ? '是' : '否';
		}
		return $this->buildSuccess($list);
	}

	/**
	 * 编辑页获取信息
	 * @return [type] [description]
	 */
	public function getinfo(){
		$getData = request()->get();
		$info = Update::getInfo(['id' =>$getData['id']]);
		if(empty($info)) return $this->buildFailed(ReturnCode::PARAM_INVALID, '参数无效');
		return $this->buildSuccess($info);


		$rule = ['platform' => 'require|alpha', 'version' => 'require'];
		$msg  = ['platform.require' => '必须输入平台类型', 'platform.alpha' => '平台类型参数为字母', 'version.require' => '必须输入版本号'];
		$validate = new Validate($rule, $msg);
		if(!$validate->check($getData)){
			return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, $validate->getError());
		}
	}

	/**
	 * 编辑/新增
	 * @return [type] [description]
	 */
	public function modify(){
		$postData = request()->post();
		$rule = [
			'android_ver_newest' => 'require|max:10|regex:\d+.\d+.\d+',
			'android_download_url' => 'require|url',
			'ios_ver_newest' => 'require|max:10|regex:\d+.\d+.\d+',
			'ios_download_url' => 'require|url',
			'desc' => 'require',
			'force' => 'require|in:0,1',
		];
		$msg  = [
			'android_ver_newest.require' => 'android版本号不能为空',
			'android_ver_newest.max' => 'android版本号最大字符长度为10',
			'android_ver_newest.regex' => 'android版本号只能填数字和小数点',
			'android_download_url.require' => 'android下载地址不能为空',
			'android_download_url.url' => 'android下载地址格式错误',
			'ios_ver_newest.require' => 'ios版本号不能为空',
			'ios_ver_newest.max' => 'ios版本号最大字符长度为10',
			'ios_ver_newest.regex' => 'ios版本号只能填数字和小数点',
			'ios_download_url.require' => 'ios下载地址不能为空',
			'ios_download_url.url' => 'ios下载地址格式错误',
			'desc.require' => '更新内容不能为空',
			'force.require' => '必须选择是否强制更新' ,
			'force.in' => '是否强制更新字段参数错误',
		];
		$validate = new Validate($rule, $msg);
		if(!$validate->check($postData)){
			return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, $validate->getError());
		}
		if($postData['id']){
			$info = Update::where(['delete_time'=>null ,'id'=>['not in',$postData['id']]])->order('id desc')->find();
			$data['update_time'] = time();
		}else{
			$data['create_time'] = time();
			$info = Update::where(['delete_time'=>null])->order('id desc')->find();
		}
		if($info){
			if($postData['android_ver_newest'] < $info['android_ver_newest']) return $this->buildFailed(ReturnCode::PARAM_INVALID, 'android版本号过低');
			if($postData['ios_ver_newest'] < $info['ios_ver_newest']) return $this->buildFailed(ReturnCode::PARAM_INVALID, 'IOS版本号过低');
		}
		try{
			Update::modify($postData);
			return $this->buildSuccess('操作成功');
		}catch (\Exception $e){
			return $this->buildFailed(ReturnCode::UPDATE_FAILED, $e->getMessage());
		}
	}

	/**
	 * 删除
	 * @return [type] [description]
	 */
	public function delete(){
		$postData = request()->post();
		try{
			Update::modify(['id' =>$postData['id'] , 'delete_time'=>time()]);
			return $this->buildSuccess('操作成功');
		}catch (\Exception $e){
			return $this->buildFailed(ReturnCode::UPDATE_FAILED, $e->getMessage());
		}
	}
}