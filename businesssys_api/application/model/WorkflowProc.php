<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/4/21
 * Time: 14:38
 */
namespace app\model;
use think\Db;
use app\model\TrialFirst;
use app\model\Estate;
use app\model\Dictionary;

class WorkflowProc extends Base {
    /*
     * @author 赵光帅
     * 获取器 对status(审批结果)字段的转化
     *
     * */
    /*public function getStatusAttr($value)
    {
        $status = [-1=>'驳回',0=>'待处理',9=>'通过',-2=>'撤回'];
        return $status[$value];
    }*/

    /* @author 赵光帅
     * 审批列表查询
     *
     * @Param {arr} $map    搜索条件
     * @Param {int} $page    页码
     * @Param {int} $pageSize    每页数量
     *
     * */

    public static function approval_list($map,$page,$pageSize){
        $res = self::alias('d')
            ->field('d.id as proc_id,a.inspector_id,a.id,a.order_sn,a.create_time,a.type,a.money,a.stage,b.estate_name,b.estate_owner,b.estate_region,c.name,a.allot_time,a.guarantee_letter_outtime')
            ->join('order a','d.order_sn=a.order_sn')
            ->join('workflow_flow wf','d.flow_id = wf.id')
            ->join('system_user c','a.financing_manager_id = c.id')
            ->join('estate b','d.order_sn=b.order_sn','LEFT')
            ->where($map)
            ->order('d.create_time asc')
            ->group('a.id')
            ->paginate(array('list_rows' => $pageSize, 'page' => $page))
            ->toArray();

        //将订单状态和订单类型更改为中文
        $newStageArr =  dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_JYDB_STATUS'));
        $newTypeArr =  dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        //到初审信息表去查询该订单是否是正常单
        foreach ($res['data'] as $k => $v){
            $ectryDistrict = explode('|',$v['estate_region']);
            $res['data'][$k]['estate_ecity'] = $ectryDistrict[0];
            if(isset($ectryDistrict[1])){
                $res['data'][$k]['estate_district'] = $ectryDistrict[1];
            }else{
                $res['data'][$k]['estate_district'] = '';
            }
            $isNormal = TrialFirst::getOne(['order_sn' => $v['order_sn'],'delete_time' => NULL],'is_normal');
            if(empty($isNormal)){
                $res['data'][$k]['is_normal'] = -1;
            }else{
                $res['data'][$k]['is_normal'] = $isNormal['is_normal'];
            }
            $res['data'][$k]['order_type'] = $v['type'];
            $res['data'][$k]['stage'] = $newStageArr[$v['stage']] ? $newStageArr[$v['stage']]:'';
            $res['data'][$k]['type_str'] = $newTypeArr[$v['type']] ? $newTypeArr[$v['type']]:'';
            //查询出改订单所有的房产
            $res['data'][$k]['sumname'] = Db::name('estate')->where(['order_sn' => $v['order_sn'],'status' => 1,'delete_time' => null,'estate_usage' => 'DB'])->column('estate_name');
            //时间只需要年月日
            $res['data'][$k]['create_time'] = substr($v['create_time'],0,10);
            //查询出审查员
            $res['data'][$k]['inspector_name'] = Db::name('system_user')->where(['id' => $v['inspector_id'],'status' => 1])->value('name');
        }
        return $res;
    }

    /* @author 
     * 风控管理-分单列表查询
     *
     * @Param {arr} $map    搜索条件  
     * */
    public static function distribute_list($map){
        $res = self::alias('d')
        ->field('d.id as proc_id,a.id,a.order_sn,a.type,b.money,a.stage,c.name,c.deptname,d.user_name,d.finish_time,a.dept_manager_id,e.estate_name,e.estate_region,d.create_time,a.inspector_id,a.allot_time')
            //->field('d.id as pid,a.id,a.order_sn,a.create_time,a.type,a.money,a.stage,c.name')
            ->join('order a','d.order_sn=a.order_sn')
            ->join('workflow_flow wf','d.flow_id = wf.id')
            ->join('system_user c','a.financing_manager_id = c.id')
            ->join('bs_order_guarantee b','d.order_sn=b.order_sn')
            ->join('estate e','d.order_sn=e.order_sn','left')
            ->where($map)
            ->order('d.create_time asc')
            ->group('a.id')
            //->paginate(array('list_rows' => $pageSize, 'page' => $page))
            //->fetchSql(true)
            ->select();
        $num = 1;
        $list=array();
        $newTypeArr =  dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        foreach($res as $key=>$v){
            $list[$key]['nod']=$num;//序号
            $num++;
            $list[$key]['order_sn']=$v['order_sn'];//订单号
            //$list[$key]['type']=$v['type'];
            $list[$key]['type'] = $newTypeArr[$v['type']] ? $newTypeArr[$v['type']]:'';//业务类型
            $list[$key]['money']=$v['money'];//担保金额
            $list[$key]['allot_time']=$v['allot_time'] ? date('Y-m-d H:i',$v['allot_time']):'';//分单日期
            $list[$key]['user_name']=Db::name('system_user')->where(['id' => $v['inspector_id'],'status' => 1])->value('name');//审查员
            $list[$key]['name']=$v['name'];//理财经理
            $list[$key]['deptname']=$v['deptname'];//所属部门
            $manager_name=Db::name('system_user')->field('name')->where(['id'=>$v['dept_manager_id']])->find();
            $list[$key]['manager_name']=$manager_name['name'];//部门经理
        }   
        return $list;
    }
    
    /* @author 
     * 风控管理-出保函列表查询
     *
     * @Param {arr} $map    搜索条件  
     * */
    public static function guaranteeLetterOut_list($map){
        $res = self::alias('d')
        ->field('d.id as proc_id,a.id,a.order_sn,a.type,b.money,a.stage,c.name,c.deptname,d.user_name,d.finish_time,a.dept_manager_id,e.estate_name,e.estate_region,d.create_time,a.inspector_id,a.guarantee_letter_outtime,d.content,d.dept_name')
            //->field('d.id as pid,a.id,a.order_sn,a.create_time,a.type,a.money,a.stage,c.name')
            ->join('order a','d.order_sn=a.order_sn')
            ->join('workflow_flow wf','d.flow_id = wf.id')
            ->join('system_user c','a.financing_manager_id = c.id')
            ->join('bs_order_guarantee b','d.order_sn=b.order_sn')
            ->join('estate e','d.order_sn=e.order_sn','left')
            ->where($map)
            ->order('d.create_time asc')
            ->group('a.id')
            //->paginate(array('list_rows' => $pageSize, 'page' => $page))
            //->fetchSql(true)
            ->select();
        //return $res;
        $num = 1;
        $list=array();
        $newTypeArr =  dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_TYPE'));
        foreach($res as $key=>$v){
            $list[$key]['nod']=$num;//序号
            $num++;
            $list[$key]['order_sn']="\t".$v['order_sn']."\t";//订单号
            $list[$key]['type'] = $newTypeArr[$v['type']] ? $newTypeArr[$v['type']]:'';//业务类型
            $list[$key]['money']=$v['money'];//担保金额
            $list[$key]['guarantee_letter_outtime']=$v['guarantee_letter_outtime'] ? date('Y-m-d H:i',$v['guarantee_letter_outtime']):'';//出保函日期
            $list[$key]['user_name']=Db::name('system_user')->where(['id' => $v['inspector_id'],'status' => 1])->value('name');//审查员
            //审查主管（经理）查询
            $where2['wp.order_sn'] = $v['order_sn'];
            $where2['wp.process_id']=['in','15,16'];
            $where2['wf.type']='JYDB_RISK';
            $where2['wp.status']=9;
            $where2['wp.is_back']=0;
            $where2['wp.is_deleted']=1;
            $whwhere2ere['wf.status']=1;
            $inspectorInfo=Db::name('workflow_proc')->alias('wp')
                    ->join('workflow_flow wf', 'wp.flow_id = wf.id')
                    ->where($where2)
                    ->field('wp.user_name,wp.process_id')
                    ->find();
            $list[$key]['inspectorA']=$inspectorInfo['process_id']==15?$inspectorInfo['user_name']:'';//审查主管
            $list[$key]['inspectorB']=$inspectorInfo['process_id']==16?$inspectorInfo['user_name']:'';//审查经理
            $list[$key]['name']=$v['name'];//理财经理
            $list[$key]['deptname']=$v['deptname'];//所属部门
            $manager_name=Db::name('system_user')->field('name')->where(['id'=>$v['dept_manager_id']])->find();
            $list[$key]['manager_name']=$manager_name['name'];//部门经理
            /*截取订单快递单号*/
            $expnum=strpos($v['content'], '快递单号');
            $len=mb_strlen("快递单号",'utf-8');
            //$list[$key]['expressNum']=substr($v['content'],$expnum);
            $list[$key]['expressNum']="\t".mb_substr($v['content'],$len+$expnum,20,'utf-8')."\t";//快递单号"\t".
        }   
        return $list;
    }

}