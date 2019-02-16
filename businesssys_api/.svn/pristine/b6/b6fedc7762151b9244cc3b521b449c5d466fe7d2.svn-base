<?php
/**
 * 处理后台接口请求权限
 * @since   2017-07-25
 * @author
 */

namespace app\admin\behavior;


use app\model\SystemAuthGroupAccess;
use app\model\SystemAuthRule;
use app\util\ReturnCode;
use app\util\Tools;
use think\Request;

class ApiPermission
{

    /**用户权限检测
     * @return \think\response\Json
     */
    public function run()
    {
        $request = Request::instance();
        $route = $request->routeInfo();
        $header = config('apiBusiness.CROSS_DOMAIN');
        $ApiAuth = $request->header('ApiAuth', '');
        $userInfo = cache('Login:' . $ApiAuth);
        $userInfo = json_decode($userInfo, true);
        if (!$this->checkAuth($userInfo['id'], $route['route'])) {
            $data = ['code' => ReturnCode::INVALID, 'msg' => '您没有权限！', 'data' => []];

            return json($data, 200, $header);
        }
    }

    /**检测用户权限
     * @param $uid 用户uid
     * @param $route 路由
     * @return bool
     */
    private function checkAuth($uid, $route)
    {
        $isSupper = Tools::isAdministrator($uid);
        if (!$isSupper) {
            $rules = $this->getAuth($uid);
            return in_array($route, $rules);
        } else {
            return true;
        }

    }

    /**根据用户ID获取全部权限节点
     * @param $uid 用户id
     * @return array
     */
    private function getAuth($uid)
    {
        $groups = SystemAuthGroupAccess::where(['uid' => $uid])->value('groupid');
        $rule = [];
        if ($groups) {
            $allRules = SystemAuthRule::whereIn('groupid', $groups)->where(['status' => 1])->column('url');
            $rule = array_filter(array_unique($allRules));
        }
        return $rule;
    }


}
