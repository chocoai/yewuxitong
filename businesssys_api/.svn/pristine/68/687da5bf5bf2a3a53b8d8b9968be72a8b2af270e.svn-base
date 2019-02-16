<?php

namespace app\wiki\controller;

use app\util\ReturnCode;

class Index extends Base
{
    public function index()
    {
        $this->checkLogin();

        return view('');
    }

    public function log()
    {
        $this->checkLogin();

        $content = "";
        $dir = RUNTIME_PATH . "log" . DS . date('Ym', time()) . DS . date('d', time()) . '.log';
        if (file_exists($dir)) {
            $file_arr = file($dir);
            for ($i = 0; $i < count($file_arr); $i++) { //逐行读取文件内容
                $content .= $file_arr[$i];
            }
        }
        return view('', ['content' => $content, 'filename' => date('Ym', time()) . DS . date('d', time()) . '.log']);
    }

    public function apilog()
    {
        $this->checkLogin();

        $content = "";
        $file_dir = '';
        $dir = RUNTIME_PATH . "ApiLog";
        $data = $this->_read_dir_queue($dir);
        if (count($data) > 0) {
            $file_dir = $data[count($data) - 1];
        }
        if (file_exists($file_dir)) {
            $file_arr = file($file_dir);
            for ($i = 0; $i < count($file_arr); $i++) { //逐行读取文件内容
                $content .= $file_arr[$i];
            }
        }
        return view('', ['content' => $content, 'filename' => substr($file_dir, -14)]);
    }

    //队列方式
    public function _read_dir_queue($dir)
    {
        $files = array();
        $queue = array($dir);
        while ($data = each($queue)) {
            $path = $data['value'];
            if (is_dir($path) && $handle = opendir($path)) {
                while ($file = readdir($handle)) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }

                    $real_path = $path . DS . $file;
                    if (is_file($real_path)) {
                        $files[] = $real_path;
                    }
                    if (is_dir($real_path)) {
                        $queue[] = $real_path;
                    }

                }
                closedir($handle);
            }
        }
        return $files;
    }

    public function calculation()
    {
        $this->checkLogin();

        return view();
    }

    public function errorCode()
    {
        $this->checkLogin();
        $codeArr = ReturnCode::getConstants();
        $errorInfo = array(
            ReturnCode::SUCCESS => '请求成功',
            ReturnCode::INVALID => '非法操作',
            ReturnCode::DB_SAVE_ERROR => '数据存储失败',
            ReturnCode::DB_READ_ERROR => '数据读取失败',
            ReturnCode::CACHE_SAVE_ERROR => '缓存存储失败',
            ReturnCode::CACHE_READ_ERROR => '缓存读取失败',
            ReturnCode::FILE_SAVE_ERROR => '文件读取失败',
            ReturnCode::LOGIN_ERROR => '登录失败',
            ReturnCode::NOT_EXISTS => '不存在',
            ReturnCode::JSON_PARSE_FAIL => 'JSON数据格式错误',
            ReturnCode::TYPE_ERROR => '类型错误',
            ReturnCode::NUMBER_MATCH_ERROR => '数字匹配失败',
            ReturnCode::EMPTY_PARAMS => '丢失必要数据',
            ReturnCode::DATA_EXISTS => '数据已经存在',
            ReturnCode::AUTH_ERROR => '权限认证失败',
            ReturnCode::OTHER_LOGIN => '别的终端登录',
            ReturnCode::VERSION_INVALID => 'API版本非法',
            ReturnCode::CURL_ERROR => 'CURL操作异常',
            ReturnCode::RECORD_NOT_FOUND => '记录未找到',
            ReturnCode::DELETE_FAILED => '删除失败',
            ReturnCode::ADD_FAILED => '添加记录失败',
            ReturnCode::UPDATE_FAILED => '更新记录失败',
            ReturnCode::PARAM_INVALID => '数据类型非法',
            ReturnCode::ACCESS_TOKEN_TIMEOUT => '身份令牌过期',
            ReturnCode::SESSION_TIMEOUT => 'SESSION过期',
            ReturnCode::UNKNOWN => '未知错误',
            ReturnCode::EXCEPTION => '系统异常',
        );

        return view('', [
            'errorInfo' => $errorInfo,
            'codeArr' => $codeArr,
        ]);
    }

    public function login()
    {
        return view();
    }

    /**
     * 处理wiki登录
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function doLogin()
    {
        $appId = $this->request->post('appId');
        $appSecret = $this->request->post('appSecret');

        if ($appId === 'business' && $appSecret = '123456') {
            $appInfo = ['app_id' => $appId, 'app_secret' => $appSecret, 'app_status' => 1];
        }

        if (!empty($appInfo)) {
            if ($appInfo['app_status']) {
                //保存用户信息和登录凭证
                session('app_info', json_encode($appInfo));
                $this->success('登录成功', url('/wiki/index'));
            } else {
                $this->error('当前应用已被封禁，请联系管理员');
            }
        } else {
            $this->error('AppId或AppSecret错误');
        }
    }

    public function logout()
    {
        session('app_info', null);
        $this->success('退出成功', url('/wiki/login'));
    }

}
