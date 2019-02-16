<?php

namespace app\common\controller;

use think\Db;
use app\util\Tools;

/**
 * OA系统数据同步
 */
class OASystem extends Base {

    //同步OA系统用户
    public function syncSystemUser() {
        try {
            $oaDB = Db::connect(config('thirdDb.oa_db'));
            $userinfo = $oaDB->query("select a.state,a.jobfamily,a.email,a.mobile,a.ranking,a.jobname,a.sex,b.user,b.pass,b.status,b.deptname,b.superman,b.deptid,b.deptallname,b.superid,b.superman,b.deptpath,b.id,b.name,b.num,b.superpath,b.adddt,b.companyid from oa_userinfo a left join oa_admin b on a.id=b.id");
            unset($oaDB);
            if ($userinfo) {
                foreach ($userinfo as $val) {
                    $data = array();
                    $data['username'] = $val['user']; //用户名
                    $data['password'] = Tools::userMd5($val['pass'], '', false); //密码//$data['password'] = '2bb13a8d67e4b23b98f102c79abe53a2'; //测试时密码置为123456
                    $data['num'] = $val['num']; //工号
                    $data['name'] = $val['name']; //姓名
                    //name oa没有用户昵称
                    $data['state'] = $val['state']; //状态(来自userstate) 0试用 1转正 2实习 3兼职 4临时工 5离职 6外协 7社会分配
                    $data['jobfamily'] = $val['jobfamily']; // 职系
                    $data['mobile'] = $val['mobile']; //电话
                    $data['email'] = $val['email']; //邮箱
                    //$data['position_id'] = Db::name('system_position')->where(['name'=>$val['ranking']])->value('id');
                    //position_id
                    $data['ranking'] = $val['ranking']; //职级
                    $data['gender'] = $this->getSex($val['sex']); //性别
                    $data['position'] = $val['jobname']; //岗位
                    $data['deptid'] = $val['deptid']; //部门id
                    $data['deptname'] = $val['deptname']; //部门名称
                    $data['deptallname'] = $val['deptallname']; //部门全路径
                    $data['superid'] = $val['superid']; //上级主管id
                    $data['superman'] = $val['superman']; //上级主管名称
                    $data['deptpath'] = $val['deptpath']; //部门路径
                    $data['superpath'] = $val['superpath']; //上级主管路径
                    $data['companyid'] = $val['companyid']; //公司id
                    //remark备注说明
                    $data['status'] = $val['status'];
                    $data['sync_time'] = time();
                    $result = Db::name('system_user')->where(['oa_user_id' => $val['id']])->find();
                    if ($result) {
                        Db::name('system_user')->where('oa_user_id', $val['id'])->update($data);
                    } else {
                        if ($val['id']) {
                            $data['oa_user_id'] = $val['id'];
                            $data['create_time'] = $val['adddt'] ? strtotime($val['adddt']) : time();
                            Db::name('system_user')->insert($data);
                        }
                    }
                }
                return $this->buildSuccess('', 'sync user success!');
            }
        } catch (\Exception $e) {
            return $this->buildFailed(0, "sync user fail message:{$e->getMessage()}");
        }
    }

    //同步部门
    public function syncDept() {
        try {
            $oaDB = Db::connect(config('thirdDb.oa_db'));
            $deptInfo = $oaDB->query("select * from oa_dept");
            unset($oaDB);
            if ($deptInfo) {
                foreach ($deptInfo as $val) {
                    $data['name'] = $val['name'];
                    $data['parentid'] = $val['pid'];
                    $data['sort'] = $val['sort'];
                    $result = Db::name('system_dept')->where(['id' => $val['id']])->find();
                    if ($result) {
                        $data['update_time'] = time();
                        Db::name('system_dept')->where('id', $val['id'])->update($data);
                    } else {
                        $data['id'] = $val['id'];
                        $data['create_time'] = $val['optdt'] ? strtotime($val['optdt']) : time();
                        Db::name('system_dept')->insert($data);
                    }
                }
                return $this->buildSuccess('', 'sync dept success!');
            }
        } catch (\Exception $e) {
            return $this->buildFailed(0, "sync dept fail message:{$e->getMessage()}");
        }
    }

    private function getSex($sex) {
        switch ($sex) {
            case '男':
                return 1;
                break;
            case '女':
                return 2;
                break;
            default:
                return 0;
        }
    }

    //同步公司id  可删除
    public function syncCompanyId() {
        exit;
        $oaDB = Db::connect(config('thirdDb.oa_db'));
        $userinfo = $oaDB->query("select companyid,num from oa_userinfo");
        //更新公司id
        if ($userinfo) {
            foreach ($userinfo as $val) {
                Db::name('system_user')->where('num', $val['num'])->setField('companyid', $val['companyid']);
            }
        }
        echo 'success';
    }

    //同步订单公司ID  可删除

    public function syncOrderCompanyId() {
        exit;
        $orderUser = Db::name('order')->field('id,financing_manager_id')->select();
        if ($orderUser) {
            foreach ($orderUser as $val) {
                $companyId = Db::name('system_user')->where(['id' => $val['financing_manager_id']])->value('companyid');
                if ($companyId) {
                    Db::name('order')->where(['id' => $val['id']])->setField('companyid', $companyId);
                }
            }
        }

        echo 'success';
    }

}
