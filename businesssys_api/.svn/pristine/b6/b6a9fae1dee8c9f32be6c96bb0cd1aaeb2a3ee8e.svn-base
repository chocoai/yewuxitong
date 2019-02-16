<?php

namespace app\model;

use think\Db;

class OrderStaff extends Base {

    /**
     * 加订单归属表数据
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @param 
     * @author zhongjiaqi 8.14
     */
    public function addOrderstaff($order_sn, $uid) {
        $where = ['state' => ['neq', 5], 'status' => 1, 'is_deleted' => 0, 'id' => $uid];
        $userinfo = DB::name('system_user')->where($where)->field('id,name,deptid')->find();
        if ($userinfo) {
            $this->order_sn = $order_sn;
            $deptInfo = $this->getFullpath($userinfo['deptid']);
            foreach ($deptInfo as $value) {
                $ids [] = '[' . $value['id'] . ']';
                $names [] = $value['name'];
            }
            $deptpath = !empty($ids) ? implode(',', $ids) : null;
            $deptallname = !empty($names) ? implode('/', $names) : null;
            $adddata = ['order_sn' => $this->order_sn, 'role_type' => 'FM', 'name' => $userinfo['name'], 'uid' => $uid, 'dept_id' => $userinfo['deptid'], 'dept_path' => $deptpath, 'dept_allname' => $deptallname];
            if (Db::name('order_staff')->insert($adddata)) {
                $upDeptids = array_column($deptInfo, 'id'); //获取当前理财经理的所有上级部门
                krsort($upDeptids);
                $upDeptids = array_values($upDeptids);
                $rankArray = ['经理', '区总', '总监'];
                $rankcodeArray = ['DM', 'AM', 'CI'];
                foreach ($upDeptids as $key => $value) {
                    if (!$this->saveUporderstaff($value, $rankArray[$key], $rankcodeArray[$key])) {
                        return '该理财经理组织架构存在问题，请联系系统管理员';
                    }
                }
                return 1;
            }
            return '理财经理订单归属表添加失败';
        } else {
            return '当前理财经理已经不存在';
        }
    }

    /**
     * 编辑归属表数据
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @param 
     * @author zhongjiaqi 8.14
     */
    public function editOrderstaff($order_sn, $uid) {
        if ($this->where(['status' => 1, 'order_sn' => $order_sn, 'role_type' => 'FM', 'uid' => $uid])->count() > 0) {
            return 1;
        } else {
            $this->where(['order_sn' => $order_sn])->update(['status' => -1]);
            if ($this->addOrderstaff($order_sn, $uid)) {
                return 1;
            }
            return '编辑订单归属表失败';
        }
    }

    /**
     * 获取上级部门并保存记录
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param $id 部门id
     * @author zhongjiaqi 8.14
     */
    public function saveUporderstaff($id, $ranking, $role_type) {
        if ($id && $ranking && $role_type) {
            $where = ['state' => ['neq', 5], 'status' => 1, 'is_deleted' => 0, 'deptid' => $id, 'ranking' => $ranking];
            $userinfo = DB::name('system_user')->where($where)->field('id,name')->select();
            if (count($userinfo) != 1) {//如果组织架构中除去理财经理以上级别存在两个上级  或者  没有上级  返回错误
                return false;
            }
            $deptInfo = $this->getFullpath($id);
            foreach ($deptInfo as $value) {
                $ids [] = '[' . $value['id'] . ']';
                $names [] = $value['name'];
            }
            $deptpath = !empty($ids) ? implode(',', $ids) : null;
            $deptallname = !empty($names) ? implode('/', $names) : null;
            $adddata = ['order_sn' => $this->order_sn, 'role_type' => $role_type, 'name' => $userinfo[0]['name'], 'uid' => $userinfo[0]['id'], 'dept_id' => $id, 'dept_path' => $deptpath, 'dept_allname' => $deptallname];
            if (!Db::name('order_staff')->insert($adddata)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取当前部门
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param $id 部门id
     * @author zhongjiaqi 5.15
     */
    public function getNowdept($id) {
        if ($id) {
            $where = ['status' => 1, 'type' => 1, 'id' => $id];
            $data = DB::name('system_dept')->where($where)->field('id,name,parentid')->find();
            return $data;
        }
        return false;
    }

    /**
     * 获取部门全路径
     * @return 数据集
     * @throws \think\db\exception\DataNotFoundException
     * @param $id 部门id
     * @author zhongjiaqi 5.15
     */
    public function getFullpath($id, &$newarrays = []) {
        if ($id) {
            $newarray = $this->getNowdept($id);
            $newarrays[] = $newarray;
            if ($newarray['parentid'] != 0 && $newarray['parentid'] != 1 && $newarray['parentid'] != 5) {
                $this->getFullpath($newarray['parentid'], $newarrays);
            }
        }
        krsort($newarrays);
        return $newarrays;
    }

}
