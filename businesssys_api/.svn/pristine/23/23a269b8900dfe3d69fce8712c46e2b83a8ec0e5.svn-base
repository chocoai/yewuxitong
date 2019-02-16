<?php
/**
登录api
 */

namespace app\api\controller;
use app\util\ReturnCode;


use think\Db;
use think\Exception;

class Login extends Base
{



    private $expire_time = 120;

    //获取登录授权秘钥
    public function loginSecret()
    {
        $secret = config('apiBusiness.AUTH_SECRET');
        $system = input('system','','strtoupper');
        if($system == '' || !isset($secret[$system])){
            return $this->buildFailed(ReturnCode::PARAM_INVALID,'参数有误');
        }
        $systemInfo = $secret[$system];
        $ApiAuth = input('apiAuth','');

        //验证登录信息
        if ($ApiAuth) {
            $userInfo = cache('Login:' . $ApiAuth);
            $userInfo = json_decode($userInfo, true);
            if (!$userInfo || !isset($userInfo['id'])) {
                return $this->redirect($systemInfo['url']);
            }
        }else{
           // return $this->buildFailed(ReturnCode::PARAM_INVALID,'缺少ApiAuth');
            return $this->redirect($systemInfo['url']);
        }

        $code = md5($userInfo['num'].microtime());

      $pass = $this->passport_encrypt($code,$systemInfo['secret']);
      if(Db::name('system_login_auth')->insert(['code'=>$code,'num'=>$userInfo['num'],'create_time'=>time(),'system'=>$system]))
      
          $this->redirect($systemInfo['url'].'secret='.$pass);

      return $this->redirect($systemInfo['url']);

    }

    //验证code

    /**
     * @return array|false|\PDOStatement|string|\think\Model|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *
     */
    public function checkCode()
    {
        $code = input('code','');
        if($code){
           $result =  Db::name('system_login_auth')->where(['code'=>$code])->find();
           if($result){
               if($result['status'] == 0 ){
                   if($result['create_time'] + $this->expire_time >= time()){
                       Db::name('system_login_auth')->where(['code'=>$code])->setField('status',1);//更新已使用
                       return $this->buildSuccess(['num'=>$result['num']]);//成功返回工号
                   }else{
                       Db::name('system_login_auth')->where(['code'=>$code])->setField('status',2);//更新已超时
                       return $this->buildFailed(ReturnCode::PARAM_INVALID,'授权码已过期');
                   }
               }else{
                   return $this->buildFailed(ReturnCode::PARAM_INVALID,'授权码已使用');
               }
           }else{
               return $this->buildFailed(ReturnCode::PARAM_INVALID,'授权码不存在');
           }
        }else{
            return $this->buildFailed(ReturnCode::PARAM_INVALID,'授权码不能为空');
        }
    }


    function passport_encrypt($txt, $key)
    {
        srand((double)microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $encrypt_key[$ctr] . ($txt[$i] ^ $encrypt_key[$ctr++]);
        }
        return urlencode(base64_encode($this->passport_key($tmp, $key)));
    }

    function passport_decrypt($txt, $key)
    {
        $txt = $this->passport_key(base64_decode(urldecode($txt)), $key);
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = $txt[$i];
            $tmp .= $txt[++$i] ^ $md5;
        }
        return $tmp;
    }


    function passport_key($txt, $encrypt_key)
    {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }


}
