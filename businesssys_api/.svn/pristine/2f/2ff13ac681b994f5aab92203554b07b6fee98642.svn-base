<?php
/**
 * 统一支持跨域
 * @since   2017-07-25
 * @author  
 */

namespace app\admin\behavior;


use think\Config;
use think\Response;

class BuildResponse {

    /**
     * 返回参数过滤（主要是将返回参数的数据类型给前端，处理跨域问题）
     * @param $response
     * @author 
     */
    public function run(Response $response) {
        $header = Config::get('apiBusiness.CROSS_DOMAIN');
        $response->header($header);
    }

}
