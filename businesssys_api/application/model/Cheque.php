<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/25
 * Time: 16:57
 */
namespace app\model;
use think\Db;
use app\model\ChequeLog;
use app\model\SystemUser;

class Cheque extends Base {

    /* @author 赵光帅
     * 支票列表查询
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     * */

    public static function checkList($map,$page,$pageSize){
           $res = self::alias('a')
                ->field('id,order_sn,create_time,cheque_num,bankname,status,use_time,user,owner,owner_time,estate_json')
                ->where($map)
                ->order('create_time desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
           if(!empty($res['data']) && isset($res['data'])){
               $i = 1;
               foreach ($res['data'] as $k => $v){
                   if($v['estate_json']){
                       $strName = '';
                       foreach (json_decode($v['estate_json'],true) as $ks => $vs){
                           $strName .= $vs['estate_name'].',';
                       }
                       $res['data'][$k]['estate_json'] = rtrim($strName,',');
                   }else{
                       $res['data'][$k]['estate_json'] = '';
                   }
                   $res['data'][$k]['i'] = $i++;
                   //领取人
                   $res['data'][$k]['owner'] = self::getUserName($v['owner']);
                   if(!empty($v['owner_time'])){
                       $res['data'][$k]['owner_time'] = date('Y-m-d H:i:s', $v['owner_time']);
                   }else{
                       $res['data'][$k]['owner_time'] = '';
                   }
                   if(!empty($v['use_time'])){
                       $res['data'][$k]['use_time'] = date('Y-m-d H:i:s', $v['use_time']);
                   }else{
                       $res['data'][$k]['use_time'] = '';
                   }
                   //使用人
                   $res['data'][$k]['user'] = self::getUserName($v['user']);
               }
           }
           return $res;
    }

    /* @author 赵光帅
     * 模糊查询人员及其部门列表
     * @Param {int} $peopleGroup    0未选择 1领取人 2使用人
     * @apiParam {string} $searchText    人员名称
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     * */

    public static function showUserinfo($peopleGroup,$searchText,$page,$pageSize){
        if($peopleGroup == 1){
            $res = self::alias('a')
                ->join('system_user b','a.owner=b.id','LEFT')
                ->field('b.id,b.name,b.deptname')
                ->where('a.owner','<>','not null')
                ->where('b.name','like',"%{$searchText}%")
                ->order('a.create_time desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
                //->fetchSql(true)
               // ->select();
            $res = unique($res['data']);
        }elseif ($peopleGroup == 2){
            $res = self::alias('a')
                ->join('system_user b','a.user=b.id','LEFT')
                ->field('b.id,b.name,b.deptname')
                ->where('a.user','NEQ','not null')
                ->where('b.name','like',"%{$searchText}%")
                ->order('a.create_time desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            $res = unique($res['data']);
        }else{
            $res1 = self::alias('a')
                ->join('system_user b','a.owner=b.id','LEFT')
                ->field('b.id,b.name,b.deptname')
                ->where('a.owner','<>','not null')
                ->where('b.name','like',"%{$searchText}%")
                ->order('a.create_time desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();

            $res2 = self::alias('a')
                ->join('system_user b','a.user=b.id','LEFT')
                ->field('b.id,b.name,b.deptname')
                ->where('a.user','NEQ','not null')
                ->where('b.name','like',"%{$searchText}%")
                ->order('a.create_time desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            $res = unique(array_merge($res1['data'],$res2['data']));
        }


        return $res;
    }


    /*
     * @author 赵光帅
     * 根据用户id获取用户名称
     * @Param {int} $id    用户id
     * */
    public static function getUserName($id)
    {
        return Db::name('system_user')->where('id',$id)->value('name');

    }

    /*
     * @author 赵光帅
     * 添加对支票操作的日志
     * @Param {int} $operate_uid    操作人id
     * @Param {int} $cheque_id    关联支票表id
     * @Param {int} $cheque_num    支票号
     * @Param {string} $remark    操作
     * @Param {string} $operateDet    操作详情
     * @Param {string} $note    操作详情
     *
     * */
    public static function addCheckLog($operate_uid,$cheque_id,$cheque_num,$remark,$operateDet,$note)
    {
        $arrDeptid = SystemUser::getOne(['id' => $operate_uid],'name,deptid,deptname');
        //添加操作日志
        $logData['cheque_id'] = $cheque_id;
        $logData['cheque_num'] = $cheque_num;
        $logData['status'] = 0;
        $logData['operate_uid'] = $operate_uid;
        $logData['remark'] = $remark;
        $logData['operate_name'] = $arrDeptid['name'];
        $logData['operate_det'] = $operateDet;
        $logData['note'] = $note;
        $logData['operate_deptid'] = $arrDeptid['deptid'];
        $logData['operate_deptname'] = $arrDeptid['deptname'];
        $logData['create_time'] = time();
        ChequeLog::create($logData);

    }

    /*
     * @author 赵光帅
     * 根据用户id查询出对应的支票
     * @Param {int} $user_id   用户id
     *
     * */
    public static function getCheckinfo($user_id)
    {
        $bankInfo = Db::name('cheque')->field('bankname')->where(['status' => 2,'owner' => $user_id])->group('bankname')->select();
        if(!empty($bankInfo)){
            foreach ($bankInfo as &$val){
                $val['cheque_num'] = Db::name('cheque')->where(['bankname' => $val['bankname'],'status' => 2,'owner' => $user_id])->field('id,cheque_num')->select();
            }
        }
        return $bankInfo;


    }

    /*
     * @author 赵光帅
     * 支票的使用
     * @Param {string} $order_sn   订单号
     * @Param {int} $user_id   使用人id
     * @Param {int} $cheque_id   支票id
     * */
    public static function dealwithCheque($order_sn,$user_id,$cheque_id)
    {
        $map['order_sn'] = $order_sn;
        $map['status'] = 1;
        $map['delete_time'] = null;
        $estateInfo = Db::name('estate')->where($map)->field('id,estate_name')->select();
        $updaInfo['order_sn'] = $order_sn;
        $updaInfo['status'] = 4;
        $updaInfo['use_time'] = time();
        $updaInfo['user'] = $user_id;
        $updaInfo['estate_json'] = json_encode($estateInfo);
        $updaInfo['update_time'] = time();
        Db::name('cheque')->where(['id' => $cheque_id])->update($updaInfo);
    }



    /*
     * @author 赵光帅
     * 获取器 对status(支票状态)字段的转化
     * 支票状态-1删除 1库存中 2领取待使用 3转让待确认 4使用待核销 5作废待核销 6使用已核销 7作废已核销
     * */
    /*public function getStatusAttr($value)
    {
        $status = [-1=>'删除',1=>'库存中',2=>'领取待使用',3=>'转让待确认',4=>'使用待核销',5=>'作废待核销',6=>'使用已核销',7=>'作废已核销'];
        return $status[$value];
    }*/


}