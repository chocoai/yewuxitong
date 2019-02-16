<?php

namespace app\workflow\controller;

use think\Controller;
use app\workflow\model\SystemUser;
use app\util\Tools;

class Login extends Controller
{
    public function index()
    {
        if (session('user.id')) {
            $this->redirect('@workflow/flow/index');
        }
        return view();
    }

    /**
     * 登录
     */
    public function login()
    {
        if ($this->request->isPost()) {
            $username = input('username');
            $password = input('password');
            if (!$username) {
                return $this->error('缺少用户名');
            }
            if (!$password) {
                return $this->error('缺少密码');
            } else {
                $password = Tools::userMd5($password);
            }
            $userInfo = SystemUser::get(['num' => $username, 'password' => $password]);
            if ($userInfo) {
                session('user', $userInfo);
                return redirect(url('flow/index'));
            }
            return $this->error('用户名或密码不正确');
        }
    }

    /**
     * 退出
     */
    public function loginout()
    {
        session('user', null);
        session_destroy();
        return redirect(url('login/index'));
    }
}