<?php

namespace app\api\controller;

use app\util\ReturnCode;
use think\Controller;


/**
 * 接口基础控制器
 */
class Base extends Controller {

    public function _initialize() {

    }


    /**
     * @param string $data
     * @param string $msg
     * @param $code
     * @return array
     */

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



}
