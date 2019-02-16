<?php
/**
 * 后台操作日志记录
 * @since   2018-02-28
 * @author
 */

namespace app\admin\behavior;

use app\model\SystemMenu;
use app\model\SystemUserLog;
use app\util\ReturnCode;
use think\Request;

class SystemLog
{

    /**
     * 后台操作日志记录
     * @author
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function run()
    {
        $header = config('apiBusiness.CROSS_DOMAIN');
        $request = Request::instance();
        $route = $request->routeInfo();
        $ApiAuth = $request->header('ApiAuth', '');
        $userInfo = cache('Login:' . $ApiAuth);
        $userInfo = json_decode($userInfo, true);
        $menuInfo = SystemMenu::get(['url' => $route['route']]);
        if ($menuInfo) {
            $menuInfo = $menuInfo->toArray();
        } else {
            $data = ['code' => ReturnCode::INVALID, 'msg' => '当前路由非法：' . $route['route'], 'data' => []];
            return json($data, 200, $header);
        }

        SystemUserLog::create([
            'actionname' => $menuInfo['title'],
            'uid' => $userInfo['id'],
            'name' => $userInfo['name'],
            'addtime' => time(),
            'url' => $route['route'],
            'data' => json_encode($request->param()),
        ]);
    }

}
