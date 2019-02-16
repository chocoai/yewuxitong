<?php

namespace app\model;

use app\util\Tools;

class SystemUser extends Base {

    public function department(){
        return $this->belongsTo('SystemDept','deptid');
    }

    /**
     * 通过人员模糊查询
     * @param $name
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function userSearch($name) {
        return self::alias('a')
                        ->field('a.id,a.name,b.name as dept_name')
                        ->join('system_dept b', 'a.deptid=b.id')
                        ->where('a.name', 'like', "%{$name}%")
                        ->limit(10)
                        ->select();
    }

    /**
     * 获取订单查询用户范围
     * @param $id
     * @return bool|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getOrderPowerStr($id,$ranking=false,$deptId=false) {
//        $where['superpath'] = ['like', '%[' . $id . ']%'];
//        $res = self::where($where)->field('GROUP_CONCAT(id) AS idstr')->find();
//        return $res['idstr'] ? $res = $id . ',' . $res['idstr'] : $id;

        if($ranking==false || $deptId==false){
            $user = self::where(['id'=>$id,'status'=>1])->field('ranking,deptid')->find();
            if($user){
                $ranking = $user['ranking'];
                $deptId = $user['deptid'];
            }else{
                return $id;
            }
        }
        //2杜欣 14李军
        if( $id==14  || $id == 2 || Tools::isAdministrator($id)){
            return 'super';//超级权限
        }

        $power = self::getRankingPower($ranking,$deptId);
        return $power !== false ? $power : $id;
    }
    /**
     * 通过职位获取权限
     * @param $d
     */
    public static function getRankingPower($ranking,$deptId)
    {
        if($ranking == '经理' || $ranking == '区总'|| $ranking == '总监' || $ranking == '副总经理' || $ranking == '总经理' ||  $ranking == '总裁' ||  $ranking == '区总' ||  $ranking == '副总裁' ||  $ranking == '总经办'){
                $role = self::where("instr(`deptpath`,'[{$deptId}]') and status=1")->value("GROUP_CONCAT(id)");
                if($role){
                    return  $role;
                }
        }
        return false;
    }


    /**
     * 判断用户是否有权限查询
     * @param $userId 当前登录会员id
     * @param $managerId  查询人id
     * @param $subordinates
     */
    public static function orderCheckPower($id, $managerId, $subordinates = 0) {
        $reg = '(\\\\[' . $id . '\\\\])';
        $count = self::where('superpath regexp \'' . $reg . '\' and id=' . $managerId)->count();
        if ($count <= 0)
            return false;
        return $subordinates == '1' ? self::getOrderPowerStr($managerId) : $managerId;
    }

    /**
     * 模糊获取所有理财经理和对应部门
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @param $where 查询条件
     * @author zhongjiaqi 4.25
     */
    public function getmanagernameList($where) {
        $res = $this->alias('su')
                ->field('su.id,su.name,su.deptname,su.deptid')
                ->join('__SYSTEM_POSITION__ sp', 'sp.id=su.position_id', 'LEFT')
                ->where($where)
                ->limit(10)
                ->select();
        return Tools::buildArrFromObj($res);
    }

    /**
     * 获取当前部门下面的所有人员
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @param $id 部门id
     * @author zhongjiaqi 5.17
     */
    public function getDeptpeopleList($id) {
        if ($id) {
            $where = ['deptid' => $id, 'status' => 1];
            $res = $this->where($where)->field('id,name')->select();
            return Tools::buildArrFromObj($res);
        }
        return false;
    }

    /**
     * 获取用户全路径
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param $id 用户id
     * @author zhongjiaqi 5.15
     */
    public function getFullpath($id, &$newarrays = []) {
        if ($id) {
            $superid = $this->where('id', $id)->value('superid');
            if ($superid != 0) {
                $newarrays[] = $superid;
                $this->getFullpath($superid, $newarrays);
            }
        }
        krsort($newarrays);
        return $newarrays;
    }

    /**
     * 检测是否用户是否满足添加或修改需求（相同名字数字化处理）
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param $name 姓名
     * @param $mobile 联系电话
     * @param $num 工号
     * @param $id 用户id(编辑的时候要除去自己再对比)
     * @author zhongjiaqi 5.21
     */
    public function checkSameuser($name, $mobile, $num, $id = '') {
        if (!empty($id)) {
            $where ['id'] = array('neq', $id);
        }
        $where['is_deleted'] = 0;
        $where['status'] = 1;
        if ($num) {
            $where['num'] = $num;
            if ($this->where($where)->count() > 0) {
                return $code = ['code' => 1, 'msg' => '存在相同工号的用户，请确认后重试！'];
            }
        }
        if ($mobile) {
            $where['mobile'] = $mobile;
            if ($this->where($where)->count() > 0) {
                return $code = ['code' => 1, 'msg' => '存在相同手机号的用户，请确认后重试！'];
            }
        }
        if ($name) {
            $where['name'] = $name;
            if ($this->where($where)->count() > 0) {
                $admin = $this->where($where)->order('id DESC')->field('name')->find();
                $number = (int) preg_replace('/\D/s', '', $admin['name']) + 1;
                empty($number) && $number = 1;
                preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $admin['name'], $matches); //匹配提取字符汉子
                $str = join('', $matches[0]);  //获取到汉子
                $name = $str . $number;
            }
            return $code = ['code' => 0, 'name' => $name];
        }
    }

    /**
     * 获取部门下的所有理财经理
     */
    public static function getDeptManager($condition, $field, $deptId, $groupid) {

        $condition['a.status'] = 1;
        $condition['a.is_deleted'] = 0;
        $condition['deptpath'] = ['like', '%[' . $deptId . ']%'];
        $condition['b.groupid'] = $groupid; //理财经理职位id
        $condition['b.system_id'] = 2;
        return self::alias('a')
                        ->field($field)
                        ->join('system_auth_group_access b', 'a.id=b.uid', 'left')
                        ->where($condition)
                        ->select();
    }

    /**
     * 获取赎楼员
     * @param $type 1
     * @param $group_id group_id
     * @param $where
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getRansomer($type, $group_id,$companyId=0, $name = '', $limit = 0) {

        if ($type == 1) {//查询赎楼员
            $where = ['a.status' => 1];
            if($companyId == 10){
                $where['a.companyid'] = 10;
            }else{
                $where['a.companyid'] = ['neq',10];
            }
            !empty($name) && $where['a.name'] = ['like', "%{$name}%"];
            return self::alias('a')->join('system_auth_group_access b', 'a.id=b.uid')->where($where)
                ->where('', 'exp', 'FIND_IN_SET(' . $group_id . ',b.groupid)')
                ->field('a.id,a.name')
                ->limit($limit)
                ->select();
        } else {
            //查询赎楼部
            if($companyId == 10){//武汉汇金
                $deptid = 63;
            }else{
                $deptid = 19;
            }
            $where = ['status' => 1, 'deptid' => $deptid];
            !empty($name) && $where['name'] = ['like', "%{$name}%"];
            return self::where($where)->field('id,name')->limit($limit)->select();
        }
    }

}
