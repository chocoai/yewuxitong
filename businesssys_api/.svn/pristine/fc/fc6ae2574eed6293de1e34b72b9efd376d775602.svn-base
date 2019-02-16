<?php

namespace app\admin\service;

use think\Db;

/* * 查档管理
 * Class Consultfiles
 * @package app\api\controller
 */

class Consultfiles {

    CONST API_SEARCH_PROFILE_URL = 'http://119.23.24.187:8080/ris.szpl.gov.cn_business_client-1.0.0/fbb/spider/searchProfile/business/index';        //查档接口

    /**
     * 查档
     */

    public function Consultfiles($where = []) {
        $list = Db::name('estate')->where($where)->field('house_type,estate_certtype,estate_certnum,estate_name,estate_owner')->find();
        if (!$list) {
            return json(['msg' => 'finish']);
        }
        $list['searchtype'] = $list['house_type'] == 1 ? '分户' : '分栋';
        $estatename = explode(',', $list['estate_name']);
        $list['estate_name'] = $estatename[0];
        if ($list['estate_certtype'] == 1) {
            $data = ["searchType" => $list['searchtype'], "proveType" => "房地产权证书", "proveCode" => $list['estate_certnum'], "certificateNo" => '', "orgName" => $list['estate_owner']];
        } else if ($list['estate_certtype'] == 2) {
            $list['ownershipyear'] = substr($list['estate_certnum'], 0, 4);
            $list['estate_certnum'] = substr($list['estate_certnum'], 4);
            $data = ["searchType" => $list['searchtype'], "proveType" => "不动产权证书", "proveCode1" => $list['ownershipyear'], "proveCode2" => $list['estate_certnum'], "certificateNo" => '', "orgName" => $list['estate_owner']];
        }
        $result = json_decode($this->post(self::API_SEARCH_PROFILE_URL, json_encode($data), '', ['Content-type: application/json']), true);
        if ($result['code'] == -1) {
            $res = ['code' => -1, 'msg' => '目前网络波动较大，请稍后重试！', 'estate_inquiry_text' => '网络波动较大', 'result_code' => $result['code']];
        } else if ($result['code'] == 1) {
            $res = ['code' => $result['code'], 'msg' => '查询成功！', 'estatestatus' => $result['data']['status_txt'], 'result_code' => $result['code']];
        } else if ($result['code'] == 2) {
            $res = ['code' => -1, 'msg' => '查询失败，请确认资料是否正确！', 'estate_inquiry_text' => '资料错误', 'result_code' => $result['code']];
        }
        return $res;
    }

    /**
     * POST 请求（支持文件上传）
     * @param string $url HTTP请求URL地址
     * @param array|string $data POST提交的数据
     * @param int $second 请求超时时间
     * @param array $header 请求Header信息
     * @return bool|string
     */
    static public function post($url, $data = [], $second = 30, $header = []) {
        $curl = curl_init();
        self::applyData($data);
        self::applyHttp($curl, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $second);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        list($content, $status) = [curl_exec($curl), curl_getinfo($curl), curl_close($curl)];
        return (intval($status["http_code"]) === 200) ? $content : false;
    }

    /**
     * Post 数据过滤处理
     * @param array $data
     * @param bool $isBuild
     * @return string
     */
    private static function applyData(&$data, $isBuild = true) {
        if (!is_array($data)) {
            return null;
        }
        foreach ($data as &$value) {
            is_array($value) && $isBuild = true;
            if (!(is_string($value) && strlen($value) > 0 && $value[0] === '@')) {
                continue;
            }
            if (!file_exists(($file = realpath(trim($value, '@'))))) {
                continue;
            }
            list($isBuild, $mime) = [false, FileService::getFileMine(pathinfo($file, 4))];
            if (class_exists('CURLFile', false)) {
                $value = new CURLFile($file, $mime);
            } else {
                $value = "{$value};type={$mime}";
            }
        }
        $isBuild && $data = http_build_query($data);
    }

    /**
     * 设置SSL参数
     * @param $curl
     * @param string $url
     */
    private static function applyHttp(&$curl, $url) {
        if (stripos($url, "https") === 0) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        }
    }

}
