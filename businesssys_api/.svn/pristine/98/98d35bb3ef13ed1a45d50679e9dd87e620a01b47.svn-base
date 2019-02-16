<?php
/**
 * Created by PhpStorm.
 * User: turun
 * Date: 2018/4/21
 * Time: 14:31
 */
namespace app\model;

use think\Db;

class Update extends Base {
	protected $table = 'app_update';

	public static  function getInfos($where='', $page, $pageSize){
		return self::where($where)
					->order('create_time desc')
					->paginate(array('list_rows' => $pageSize, 'page' => $page))
					->toArray();
	}

	public static  function getInfo($where=''){
		return self::where($where)->find();
	}

	public static  function modify($data){
		if($data['id']){
			$data['update_time'] = time();
			return self::update($data);
		}else{
			$data['create_time'] = time();
			return self::insert($data);
		}
	}

}