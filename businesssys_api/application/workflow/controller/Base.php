<?php

/**
 * Created by PhpStorm.
 * User: bordon
 * Date: 2018-06-26
 * Time: 17:51
 */

namespace app\workflow\controller;

use think\Controller;


class Base extends Controller
{
    public function _initialize()
    {
        if (!session('user.id')) {
            $this->redirect('@workflow/login/index');
        }
    }
}