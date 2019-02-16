<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件
use think\Db;

/**
 * 系统主键id(14位时间戳)
 * @return bigint
 */
function get_primary_key() {
    list($s1, $s2) = explode(' ', microtime());
    return (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 10000);
}

/* * 判断用户角色
 * check_auth($this->auth_group['branch_manager'],$this->userInfo['group'])
 * @param string or array $arr 角色对应id 1或[1,2]
 * @param array $tag group 用户所在组
 * @return bool
 */

function check_auth($arr, $tag = []) {
    if (!is_array($arr)) {
        $arr = [$arr];
    }
    if (array_intersect($arr, $tag)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 把返回的数据集转换成Tree
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param string $root
 * @return array
 */
function listToTree($list, $pk = 'id', $pid = 'fid', $child = '_child', $root = '0') {
    $tree = array();
    if (is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}

function formatTree($list, $lv = 0, $title = 'name') {
    $formatTree = array();
    foreach ($list as $key => $val) {
        $title_prefix = '';
        for ($i = 0; $i < $lv; $i++) {
            $title_prefix .= "|---";
        }
        $val['lv'] = $lv;
        $val['namePrefix'] = $lv == 0 ? '' : $title_prefix;
        $val['showName'] = $lv == 0 ? $val[$title] : $title_prefix . $val[$title];
        if (!array_key_exists('_child', $val)) {
            array_push($formatTree, $val);
        } else {
            $child = $val['_child'];
            unset($val['_child']);
            array_push($formatTree, $val);
            $middle = formatTree($child, $lv + 1, $title); //进行下一层递归
            $formatTree = array_merge($formatTree, $middle);
        }
    }
    return $formatTree;
}

/* * 根据分类获取数据字典
 * @param $type string
 * @return array
 */

function getdictionary($type) {
    static $list;
    if (empty($list)) {
        $list = cache('dic_list');
    }
    $key = "{$type}";
    if (isset($list[$key])) {
        $dic = $list[$key];
    } else {
        $info = Db::name('dictionary')->where(['type' => $type, 'status' => 1])->field('code,valname')->order('sort')->select();
        $dic = $list[$key] = $info;
        cache('dic_list', $list, ['expire' => 86400]);
    }
    return $dic;
}

/**
 * 批量获取数据字典
 * @param type $arr array
 * @return array
 */
function getdictionarylist($arr) {
    if (is_array($arr)) {
        foreach ($arr as $value) {
            $data[strtolower($value)] = getdictionary($value);
        }
    } else {
        $data[strtolower($arr)] = getdictionary($arr);
    }
    return $data;
}

/**
 * 二维数组去重根据其中的一个键值对去重
 * @param $arr array
 * @return array
 */
function unique($data = array()) {
    $tmp = [-1, -2];
    foreach ($data as $key => $v) {
        if (!in_array($v['id'], $tmp)) { //不在该id就添加进去
            array_push($tmp, $v['id']);
        } else {  //在里面就删除该值
            unset($data[$key]);
        }
    }
    return $data;
}

if (!function_exists('uuid')) {

    function uuid($prefix = '') {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }

}

/** 字符串转数组
 * @param $str 要分割的字符串
 * @param string $glue 分隔符
 * @return array
 */
function str2arr($str, $glue = ',') {
    return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array $arr 要连接的数组
 * @param  string $glue 分割符
 * @return string
 */
function arr2str($arr, $glue = ',') {
    return implode($glue, $arr);
}

/**
 * 一维数据数组生成数据树
 * @param array $list 数据列表
 * @param string $id 父ID Key
 * @param string $pid ID Key
 * @param string $son 定义子数据Key
 * @return array
 */
function arr2tree($list, $id = 'id', $pid = 'pid', $son = 'sub') {
    $tree = $map = array();
    foreach ($list as $item) {
        $map[$item[$id]] = $item;
    }
    foreach ($list as $item) {
        if (isset($item[$pid]) && isset($map[$item[$pid]])) {
            $map[$item[$pid]][$son][] = &$map[$item[$id]];
        } else {
            $tree[] = &$map[$item[$id]];
        }
    }
    unset($map);
    return $tree;
}

/**
 * 一维数据数组生成数据树
 * @param array $list 数据列表
 * @param string $id ID Key
 * @param string $pid 父ID Key
 * @param string $path
 * @return array
 */
function arr2table($list, $id = 'id', $pid = 'pid', $path = 'path', $ppath = '') {
    $_array_tree = arr2tree($list, $id, $pid);
    $tree = array();
    foreach ($_array_tree as $_tree) {
        $_tree[$path] = $ppath . '-' . $_tree[$id];
        $_tree['spl'] = str_repeat("&nbsp;&nbsp;&nbsp;├&nbsp;&nbsp;", substr_count($ppath, '-'));
        if (!isset($_tree['sub'])) {
            $_tree['sub'] = array();
        }
        $sub = $_tree['sub'];
        unset($_tree['sub']);
        $tree[] = $_tree;
        if (!empty($sub)) {
            $sub_array = arr2table($sub, $id, $pid, $path, $_tree[$path]);
            $tree = array_merge($tree, (Array) $sub_array);
        }
    }
    return $tree;
}

/**
 * 格式化金额,带有千分位，100000=>100,000.00
 * @param type $moeny
 * @return type
 */
function format_money($moeny) {
    return number_format((float) $moeny, 2);
}

/**
 * 数据字典组装
 * @param $arr
 * @return array
 */
function dictionary_reset($arr, $type = 0) {
    $newArr = [];
    if ($arr) {
        if ($type === 0) {
            foreach ($arr as $val) {
                ;
                $newArr[$val['code']] = $val['valname'];
            }
        } else {
            foreach ($arr as $val) {
                !isset($newArr[$val['type']]) && $newArr[$val['type']] = [];
                $newArr[$val['type']][$val['code']] = $val['valname'];
            }
        }
    }
    unset($arr);
    return $newArr;
}

/*
 * //根据订单状态获取订单的状态描述
 * @param $code
 * */

function show_status_name($code, $type) {
    return Db::name('dictionary')->where(['code' => $code, 'type' => $type])->value('valname');
}

/*
 * //根据订单号获取订单类型
 * @param $orderSn
 * */

function get_order_type($orderSn) {
    return Db::name('order')->where(['order_sn' => $orderSn])->value('type');
}

/**
 *
 * execl数据导出
 * 应用场景：订单导出
 * @param string $title 模型名（如Member），用于导出生成文件名的前缀
 * @param array $cellName 表头及字段名
 * @param array $data 导出的表数据
 *
 * 特殊处理：合并单元格需要先对数据进行处理
 */
function exportOrderExcel($title, $data) {
    //引入核心文件
    vendor("PHPExcel.PHPExcel");
    $objPHPExcel = new \PHPExcel();
    //处理数据
    $objPHPExcel->getActiveSheet()->fromArray($data, NULL, 'A1');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $Path = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'download' . DS . date('Ymd');
    if (!file_exists($Path)) {
        //检查是否有该文件夹，如果没有就创建，并给予最高权限
        mkdir($Path, 0700);
    }
    $pathName = $Path . DS . $title . '.xlsx';
    $objWriter->save($pathName);
    return $pathName;
}

/**
 * @param string $url post请求地址
 * @param array $params
 * @return mixed
 */
function curl_post($url, array $params = array()) {
    $data_string = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
            )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

/**
 * @param string $order_type
 * @return string
 */
function get_approval_logo($order_type) {
    switch ($order_type) {
        case 'JYDB' :
            return 'JYDB_RISK';
            break;
        case 'JYXJ' :
            return 'JYXJ_RISK';
            break;
        case 'TMXJ' :
            return 'TMXJ_RISK';
            break;
        case 'PDXJ' :
            return 'PDXJ_RISK';
            break;
        case 'GMDZ' :
            return 'GMDZ_RISK';
            break;
        case 'SQDZ' :
            return 'SQDZ_RISK';
            break;
        case 'DQJK' :
            return 'DQJK_RISK';
            break;
        default:
            return '';
    }
}

//php 异步执行
function sock_post($url, $query) {
    $_post = strval(NULL);
    if (!empty($query)) {
        $_post = "query=" . urlencode(json_encode($query));
    }
    $info = parse_url($url);
    $fp = fsockopen($info["host"], 80, $errno, $errstr, 3);
    $head = "POST " . $info['path'] . "?" . $_post . " HTTP/1.0\r\n";
    $head .= "Host: " . $info['host'] . "\r\n";
    $head .= "Referer: http://" . $info['host'] . $info['path'] . "\r\n";
    $head .= "Content-type: application/x-www-form-urlencoded\r\n";
    $head .= "Content-Length: " . strlen(trim($_post)) . "\r\n";
    $head .= "\r\n";
    $head .= trim($_post);
    fwrite($fp, $head);
    fclose($fp);
}

/**
 * 获取用户角色唯一标识
 * @param string $user_id  用户id
 * @return arr
 */
function get_user_sing($user_id) {
    $groupid = Db::name('system_auth_group_access')->where(['uid' => $user_id])->value('groupid');
    if (empty($groupid)) return 2;
    $map['id'] = ['in', $groupid];
    $map['status'] = 1;
    return Db::name('system_auth_group')->where($map)->column('sign');
}

