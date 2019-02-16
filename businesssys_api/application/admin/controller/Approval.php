<?php
namespace app\admin\controller;

use app\util\ReturnCode;
use app\model\TrialFirst;
use app\model\Estate;
use app\model\TrialData;
use app\model\TrialProcess;
use app\model\SystemUser;
use app\model\WorkflowProc;
use think\Db;
use app\model\Attachment;
use app\model\Order;
use app\model\TrialProcessAttachment;
use app\util\OrderComponents;
use Workflow\Workflow;
use app\model\WorkflowFlow;
use app\model\OrderGuarantee;
use app\model\OrderWarrant;
use app\model\Dictionary;
use app\model\Message;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/*
 * @author 赵光帅
 * 审批列表审批流程类
 * */
class Approval extends Base {
            // @author 赵光帅
			/**
			 * @api {post} admin/Approval/showApprovalList 待审列表[admin/Approval/showApprovalList]
			 * @apiVersion 1.0.0
			 * @apiName showApprovalList
			 * @apiGroup Approval
			 * @apiSampleRequest admin/Approval/showApprovalList
			 *
			 *
			 * @apiParam {int} create_uid    人员id
             * @apiParam {int} subordinates    1含下属 0不含下属
			 * @apiParam {int} type    订单类型
			 * @apiParam {int} stage    订单状态
			 * @apiParam {int} estate_ecity    城市
			 * @apiParam {int} estate_district    城区
			 * @apiParam {int} search_text    关键字搜索
			 * @apiParam {int} page    页码
			 * @apiParam {int} limit    条数
			 *
             * @apiSuccessExample {json} 返回数据示例:
             * HTTP/1.1 200 OK
             * "data": {
             *       "total": 3,
             *       "per_page": 20,
             *       "current_page": 1,
             *       "last_page": 1,
             *       "data": [
             *           {
             *           "proc_id": 5,
             *           "id": 30,
             *           "order_sn": "JYDB2018010001",
             *           "create_time": "2018-04-20 14:23:51",
             *           "type": "JYDB",
             *           "money": "200.00",
             *           "stage": "待业务报单",
             *           "estate_name": "万达广场",
             *           "estate_ecity": "440300",
             *           "estate_district": "440304",
             *           "name": "管理员",
             *           "is_normal": -1
             *           "inspector_name": "邓丽君"    审查员名称
             *           }
             *       ]
             *   }
			 * @apiSuccess {int} total    总条数
			 * @apiSuccess {int} per_page    每页显示的条数
             * @apiSuccess {int} current_page    当前页
             * @apiSuccess {int} last_page    总页数
             * @apiSuccess {string} order_sn    业务单号
             * @apiSuccess {int} create_time    报单时间
             * @apiSuccess {int} type     订单类型
             * @apiSuccess {int} money    订单金额
			 * @apiSuccess {string} stage    订单状态
             * @apiSuccess {string} name    理财经理
             * @apiSuccess {string} estate_name    房产名称
             * @apiSuccess {string} estate_owner    业主姓名
             * @apiSuccess {string} estate_ecity     城市
             * @apiSuccess {string} estate_district    城区
             * @apiSuccess {int} is_normal    是否正常 -1未知 0正常 1异常
             * @apiSuccess {int} id    订单表主键id
             * @apiSuccess {int} proc_id    处理明细表主键id
			 */

	        public function showApprovalList(){
                $res = $this->approvalWhere(1);
                try{
                    return $this->buildSuccess(WorkflowProc::approval_list($res['map'],$res['page'],$res['pageSize']));
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
                }
	        }

            /**
             * @api {post} admin/Approval/haveOnlList 已审列表[admin/Approval/haveOnlList]
             * @apiVersion 1.0.0
             * @apiName haveOnlList
             * @apiGroup Approval
             * @apiSampleRequest admin/Approval/haveOnlList
             *
             * @apiParam {int} create_uid    人员id
             * @apiParam {int} subordinates    1含下属 0不含下属
             * @apiParam {int} type    订单类型
             * @apiParam {int} stage    订单状态
             * @apiParam {int} estate_ecity    城市
             * @apiParam {int} estate_district    城区
             * @apiParam {int} search_text    关键字搜索
             * @apiParam {int} page    页码
             * @apiParam {int} limit    条数
             */

            public function haveOnlList(){
                $res = $this->approvalWhere(2);
                try{
                    return $this->buildSuccess(WorkflowProc::approval_list($res['map'],$res['page'],$res['pageSize']));
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
                }
            }

            /**
             * @api {post} admin/Approval/getAlllList 所有列表[admin/Approval/getAlllList]
             * @apiVersion 1.0.0
             * @apiName getAlllList
             * @apiGroup Approval
             * @apiSampleRequest admin/Approval/getAlllList
             *
             * @apiParam {int} create_uid    人员id
             * @apiParam {int} subordinates    1含下属 0不含下属
             * @apiParam {int} type    订单类型
             * @apiParam {int} stage    订单状态
             * @apiParam {int} estate_ecity    城市
             * @apiParam {int} estate_district    城区
             * @apiParam {int}  date_type   日期类型（1分单日期 2出保日期）
             * @apiParam {int}  start_time   开始时间
             * @apiParam {int}  end_time   结束时间
             * @apiParam {int} search_text    关键字搜索
             * @apiParam {int} page    页码
             * @apiParam {int} limit    条数
             */

            public function getAlllList(){
                $createUid = input('create_uid')?:0;
                $subordinates = input('subordinates')?:0;
                $dateType = input('date_type');//1分单日期 2出保日期
                $startTime = strtotime($this->request->post('start_time'));
                $endTime = strtotime($this->request->post('end_time'));
                $type = input('type');
                $stage = input('stage');
                $estateEcity = input('estate_ecity');
                $estate_district= input('estate_district');
                $searchText = trim(input('search_text'));
                $page = input('page') ? input('page') : 1;
                $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
                $map = [];
                //用户判断//
                if ($createUid) {
                    if ($subordinates == 1) {
                        $userStr = SystemUser::getOrderPowerStr($createUid);
                    } else {
                        $userStr = $createUid;
                    }
                    $map['a.financing_manager_id'] = ['in', $userStr];
                }
                $whereTime = "";
                if($startTime && $endTime){

                    if($startTime > $endTime){
                        $startTime = $startTime+86399;
                        $whereTime = array(array('egt', $endTime), array('elt', $startTime));
                    }else{
                        $endTime = $endTime+86399;
                        $whereTime = array(array('egt', $startTime), array('elt', $endTime));
                    }
                }elseif($startTime){
                    $whereTime = ['egt',$startTime];
                }elseif($endTime){
                    $endTime = $endTime+86399;
                    $whereTime = ['elt',$endTime];
                }
                if(!empty($whereTime)){
                    if(!empty($dateType)&&$dateType==1){
                        $map['a.allot_time'] = $whereTime;
                    }elseif (!empty($dateType)&&$dateType==2) {
                        $map['a.guarantee_letter_outtime'] = $whereTime;
                    } else {
                        $map['a.create_time'] = $whereTime;
                    }
                }
                $type && $map['a.type'] = $type;
                $stage && $map['a.stage'] = $stage;
                $estateEcity && $map['b.estate_ecity'] = $estateEcity;
                $estate_district && $map['b.estate_district'] = $estate_district;
                $searchText && $map['b.estate_name|a.order_sn']=['like', "%{$searchText}%"];
                $map['a.delete_time'] = NULL;
                $map['a.status'] = ['in','1,2'];

                try{
                    $resultInfo = Order::allApplicationlist($map,$page,$pageSize);
                    if ($resultInfo === false)
                        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '订单读取失败!');
                    $result = Order::getoldOrderInfo($resultInfo);
                    return $this->buildSuccess($result);
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
                }
            }

            /*
             * @author 赵光帅
             * 组装审批列表的条件
             * @Param {int}  $typeList   1 待审批列表条件  2 已审批列表条件 3 所有审批
             * */
            protected function approvalWhere($typeList){
                $createUid = input('create_uid')?:0;
                $subordinates = input('subordinates')?:0;
                $dateType = input('date_type');//1分单日期 2出保日期
                $startTime = strtotime($this->request->post('start_time'));
                $endTime = strtotime($this->request->post('end_time'));
                $type = input('type');
                $stage = input('stage');
                $estateEcity = input('estate_ecity');
                $estate_district= input('estate_district');
                $searchText = trim(input('search_text'));
                $page = input('page') ? input('page') : 1;
                $pageSize = input('limit') ? input('limit') : config('apiBusiness.ADMIN_LIST_DEFAULT');
                $userId = $this->userInfo['id'];
                $map = [];
                //用户判断//
                if ($createUid) {
                    if ($subordinates == 1) {
                        $userStr = SystemUser::getOrderPowerStr($createUid);
                    } else {
                        $userStr = $createUid;
                    }
                    $map['a.financing_manager_id'] = ['in', $userStr];
                }
                $whereTime = "";
                if($startTime && $endTime){
                    
                    if($startTime > $endTime){
                        $startTime = $startTime+86399;
                        $whereTime = array(array('egt', $endTime), array('elt', $startTime));
                    }else{
                        $endTime = $endTime+86399;
                        $whereTime = array(array('egt', $startTime), array('elt', $endTime));
                    }
                }elseif($startTime){
                    $whereTime = ['egt',$startTime];
                }elseif($endTime){
                    $endTime = $endTime+86399;
                    $whereTime = ['elt',$endTime];
                }
                if(!empty($whereTime)){
                    if(!empty($dateType)&&$dateType==1){
                        $map['a.allot_time'] = $whereTime;
                    }elseif (!empty($dateType)&&$dateType==2) {
                        $map['a.guarantee_letter_outtime'] = $whereTime;
                    } else {
                        $map['d.create_time'] = $whereTime;
                    }
                }
                $type && $map['a.type'] = $type;
                $stage && $map['a.stage'] = $stage;
                $estateEcity && $map['b.estate_ecity'] = $estateEcity;
                $estate_district && $map['b.estate_district'] = $estate_district;
                $searchText && $map['b.estate_name|d.order_sn|b.estate_owner']=['like', "%{$searchText}%"];
                $map['a.delete_time'] = NULL;
                $map['a.status'] = 1;
                if($typeList === 1){
                    $map['d.status'] = 0;
                }elseif ($typeList === 2){
                    $map['d.status'] = 9;
                }else{
                    $map['d.status'] = ['in','0,9'];
                }

                $map['d.is_back'] = 0;
                $map['d.is_deleted'] = 1;
                $map['d.user_id']= $userId;
                $map['wf.flow_type']= 'risk';
                return ['map' => $map, 'page' => $page, 'pageSize' => $pageSize];
            }

             // @author 赵光帅
	        /**
	         * @api {post} admin/Approval/approvalRecords 审批页面信息[admin/Approval/approvalRecords]
	         * @apiVersion 1.0.0
	         * @apiName approvalRecords
	         * @apiGroup Approval
	         * @apiSampleRequest admin/Approval/approvalRecords
	         *
	         *
	         * @apiParam {string}  order_sn   订单编号
	         * @apiSuccessExample {json} 返回数据示例:
             * HTTP/1.1 200 OK
             * "data": {
             *  "approval_records": [
             *      {
             *       "order_sn": "JYDB2018010001",
             *       "create_time": "2018-05-08 15:24:57",
             *       "process_name": "待跟单员补齐资料",
             *       "auditor_name": "李依华",
             *       "auditor_dept": "财务部",
             *       "status": "通过",
             *       "content": "同意"
             *       }
             *   ],
             *   "other_information": [
             *     {
             *       "id": 1,
             *       "order_sn": "JYDB2018010001",
             *       "process_name": "收到公司的",
             *       "item": "啥打法是否",
             *       "fileinfo": [
             *           {
             *           "savename": "e259d9c4f11593187bf07f50418f6a22.jpg",
             *           "path": "D:\\wamp\\www\\businesssys_api\\public\\",
             *           "url": "\\uploads\\20180427\\e259d9c4f11593187bf07f50418f6a22.jpg",
             *           "name": "222.xlsx"
             *           },
             *           {
             *           "savename": "ad4091691f0f3995af2dcdb13bf5f5c6.jpg",
             *           "path": "D:\\wamp\\www\\businesssys_api\\public\\",
             *           "url": "\\uploads\\20180427\\ad4091691f0f3995af2dcdb13bf5f5c6.jpg",
             *            name": "222.xlsx"
             *           },
             *           {
             *           "savename": "515af20f54072b6804f25c1e18d234a4.jpg",
             *           "path": "D:\\wamp\\www\\businesssys_api\\public\\",
             *           "url": "\\uploads\\20180427\\515af20f54072b6804f25c1e18d234a4.jpg",
             *           "name": "222.xlsx"
             *           }
             *        ]
             *      },
             *    ]
             *  }
             *
	         * @apiSuccess {string} create_time    审批记录的时间
	         * @apiSuccess {string} process_name    审批节点
	         * @apiSuccess {string} auditor_name    操作人员名称
	         * @apiSuccess {string} auditor_dept    操作人员部门
	         * @apiSuccess {string} status    操作
             * @apiSuccess {string} content    审批意见
             *
             * @apiSuccess {string} proces_name    流程信息(审批信息来源)
             * @apiSuccess {string} item    注意事项
             * @apiSuccess {string} savename    文件名称
             * @apiSuccess {string} path    文件路径
             * @apiSuccess {string} url    文件链接地址
             * @apiSuccess {string} name    原始文件名称
	         */

	        public function approvalRecords(){
	        	$orderSn = input('order_sn');
	        	if(empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
                try{
                    //查询出审批记录
                    $jlField = 'wp.order_sn,wp.create_time,wp.process_name,wp.auditor_name,wp.auditor_dept,wp.status_desc status,wp.content';
                    //查询出审批记录
                    $appMap['wp.order_sn'] = $orderSn;
                    $appMap['wp.is_deleted'] = 1;
                    $appMap['wp.status'] = ['in','-1,9'];
                    $appMap['wf.type'] = get_approval_logo(substr($orderSn,0,4));
                    $jlList = Db::name('workflow_proc')->alias('wp')
                        ->join('workflow_flow wf', 'wp.flow_id = wf.id')
                        ->where($appMap)
                        ->field($jlField)
                        ->select();
                    foreach ($jlList as $k => $v){
                        $jlList[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                    }
                    $arrsInfo['approval_records'] = $jlList;

                    //查询出其他信息
                    $qtField = 'id,order_sn,process_name,item';
                    $qtList =TrialProcess::getAll(['order_sn' => $orderSn],$qtField);
                    //查询组合好的其他信息
                    $zhInfo = TrialProcess::show_Other_Information($qtList);


                    $arrsInfo['other_information'] = $zhInfo;
                    return $this->buildSuccess($arrsInfo);

                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
                }


	        }

            // @author 赵光帅
            /**
             * @api {post} admin/Approval/proceMaterialNode 待处理节点注意事项和附件材料[admin/Approval/proceMaterialNode]
             * @apiVersion 1.0.0
             * @apiName proceMaterialNode
             * @apiGroup Approval
             * @apiSampleRequest admin/Approval/proceMaterialNode
             *
             *
             * @apiParam {string}  order_sn   订单编号
             * @apiParam {int}  proc_id   处理明细表主键id
             *
             * @apiSuccessExample {json} 返回数据示例:
             * HTTP/1.1 200 OK
             * "data": {
             *       "proc_id":1
             *       "process_id": 14,
             *       "id": 1,
             *       "item": "啥打法是否",
             *       "process_name": "待部门经理审批",
             *       "next_process_name": "待审查主管审批",
             *       "attachInfo": [
             *         {
             *         "id": 2,
             *         "name": "40_2_full_res.jpg",
             *         "url": "\\uploads\\20180427\\e259d9c4f11593187bf07f50418f6a22.jpg"
             *         },
             *         {
             *         "id": 1,
             *         "name": "40_4_full_res.jpg",
             *         "url": "\\uploads\\20180427\\ad4091691f0f3995af2dcdb13bf5f5c6.jpg"
             *         },
             *       ]
             *    "preprocess": [
             *       {
             *       "id": 28,
             *       "entry_id": 1,
             *       "flow_id": 3,
             *       "process_id": 20,
             *       "process_name": "待出保函"
             *       },
             *       {
             *       "id": 24,
             *       "entry_id": 1,
             *       "flow_id": 3,
             *       "process_id": 16,
             *       "process_name": "待审查经理审批"
             *       },
             *     ]
             *     "nextprocess_user": [
             *           {
             *           "id": 345,
             *           "name": "李辉南1"
             *           },
             *          {
             *           "id": 346,
             *           "name": "李辉南2"
             *           },
             *       ]
             *       "is_next_user": 1,
             *       "stage": "1"
             *  }
             *
             * @apiSuccess {int} proc_id    处理明细表主键id
             * @apiSuccess {int} process_id    流程步骤表主键id
             * @apiSuccess {string} process_name    节点名称(当前步骤名称,审批节点)
             * @apiSuccess {string} next_process_name    下一个审批节点名称
             * @apiSuccess {int} is_next_user    是否需要选择下一步审查人员 0不需要 1需要
             * @apiSuccess {int} stage    订单状态
             * @apiSuccess {int} id    初审信息 注意事项表主键id
             * @apiSuccess {array} attachInfo 缺少的资料['id(int)'=>'附件表id','name（string）'=>'附件名称','url{string}'=>'文件链接地址']
             * @apiSuccess {array} preprocess 退回节点下拉信息['id(int)'=>'退回节点id','entry_id（int）'=>'流程实例id','flow_id（int）'=>'工作流定义表id','process_id（int）'=>'流程步骤id','process_name（string）'=>'返回节点名称']
             * @apiSuccess {array} nextprocess_user 审查员信息['id(int)'=>'下一步审批人员id','name（string）'=>'下一步审批人员名称']
             * @apiSuccess {string} needingAttention    处理审批里面的(注意事项)
             */

            public function proceMaterialNode(){
                $orderSn = input('order_sn');
                $proc_id = input('proc_id');
                if(empty($orderSn) || empty($proc_id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号或处理明细表主键id不能为空!');
                // 获取当前流程相关信息当前流程信息、可退回节点、下一步审批人员、下一步流程节点、审批记录
                $config = [
                    'user_id' => $this->userInfo['id'], // 用户id
                    'user_name' => $this->userInfo['name'], // 用户姓名
                    'proc_id' => $proc_id  // 当前步骤id
                ];
                $workflow = new Workflow($config);
                $resInfo = $workflow->workflowInfo();
                try{
                    //查询出节点名称和节点id
                    $resWork = WorkflowProc::getOne(['id' => $proc_id],'process_id,process_name');
                    //查询出注意事项和主键ID
                    $resProcess = TrialProcess::getOne(['order_sn' => $orderSn,'workflow_process_id' => $resWork['process_id']],'id,item');
                    //查询出订单状态
                    $orderStatus = Order::where(['order_sn' => $orderSn])->value('stage');
                    if(!empty($resProcess)){
                        //查询出所有的附件id
                        $resAttach = TrialProcessAttachment::getAll(['trial_process_id' => $resProcess['id'],'delete_time' => NULL],'attachment_id');
                        $attachInfo = [];
                        foreach ($resAttach as $k => $v){
                            //查询出附件信息
                            $resMent = Attachment::getOne(['id' => $v['attachment_id'],'delete_time' => NULL],'id,name,url,ext');
                            $resMent['url'] = config('uploadFile.url').$resMent['url'];
                            $attachInfo[] = $resMent;
                        }
                        //return json($resInfo);
                        //组装数据
                        $resProcess['proc_id'] = $proc_id;
                        $resProcess['process_id'] = $resWork['process_id'];
                        $resProcess['process_name'] = $resWork['process_name'];
                        $resProcess['next_process_name'] = $resInfo['nextprocess']['process_name'];
                        $resProcess['attachInfo'] = $attachInfo;
                        $resProcess['preprocess'] = $resInfo['preprocess'];
                        $resProcess['nextprocess_user'] = $resInfo['nextprocess_user'];
                        $resProcess['is_next_user'] = $resInfo['is_next_user'];
                        $resProcess['stage'] = $orderStatus;
                        $resProcess['needingAttention'] = $resProcess['item'];
                        return $this->buildSuccess($resProcess);
                    }else{
                        return $this->buildSuccess(['proc_id' => $proc_id,'next_process_name' => $resInfo['nextprocess']['process_name'],
                            'process_id' => $resWork['process_id'],'process_name' => $resWork['process_name'],'preprocess' => $resInfo['preprocess'],
                            'nextprocess_user' => $resInfo['nextprocess_user'],'is_next_user' => $resInfo['is_next_user'],'stage' => $orderStatus,'needingAttention' => $resProcess['item'],'attachInfo' => []]);
                    }
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
                }

            }


             // @author 赵光帅
	        /**
	         * @api {post} admin/Approval/subApproval 提交审批[admin/Approval/subApproval]
	         * @apiVersion 1.0.0
	         * @apiName subApproval
	         * @apiGroup Approval
	         * @apiSampleRequest admin/Approval/subApproval
	         *
	         * @apiParam {string}  order_sn   订单编号
             * @apiParam {int}  stage   订单状态
	         * @apiParam {int}  proc_id   处理明细表主键id
             * @apiParam {int}  is_approval   审批结果 1通过 2驳回
             * @apiParam {string}  content   审批意见
             * @apiParam {int}  next_user_id   下一步审批人员id
             * @apiParam {int}  backtoback   是否退回之后直接返回本节点 1 返回 不返回就不需要传值
             * @apiParam {int}  back_proc_id   退回节点id
             * @apiParam {string}  courierNumber   运单号
             * @apiParam {int} process_id    流程步骤表主键id
             * @apiParam {string} process_name    节点名称(当前步骤名称,审批节点)
	         * @apiParam {arr}  attachment_id_str   附件材料 [1,2,3]
             * @apiParam {string}  item   注意事项
             * @apiParam {string}  next_process_name   流向的审批节点名称
             * @apiParam {int} is_next_user    是否需要选择审查人员 0不需要 1需要
	         */

	        public function subApproval(){
                $orderSn = input('order_sn');
                $stage = input('stage');
                $is_approval = input('is_approval');
                $proc_id = input('proc_id');
                $content = input('content');
                $next_user_id = input('next_user_id');
                $backtoback = input('backtoback')?:'';
                $back_proc_id = input('back_proc_id');    //日志表下一步操作节点code
                $courierNumber = input('courierNumber')?:0; //运单号
                $process_id = input('process_id');
                $process_name = input('process_name');
                $attachment_id_str = input('attachment_id_str/a');
                $item = input('item');
                $next_process_name = input('next_process_name');
                $is_next_user = input('is_next_user');
                //判断上级只能查看下属的订单，不能审核，每个人只能审核属于自己审核的订单
                $procUserId = Db::name('workflow_proc')->where(['id' => $proc_id])->value('user_id');
                if($procUserId != $this->userInfo['id']) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '上级只能够查看属于下级审核的订单，不能够进行审核!');
                if($is_approval == 1 && empty($next_process_name)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '流向的节点名称不能为空!');
                if($is_approval == 2 && empty($back_proc_id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '驳回的节点id不能为空!');
                if($is_approval == 1 && $stage == 1010 && empty($courierNumber)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '运单号不能为空!');
                if($is_approval == 1 && $stage == 1003 && empty($next_user_id) && $is_next_user == 1) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '请选择审查员!');
                //更改之前的订单状态
                $orderInfo = Db::name('order')->where(['order_sn' => $orderSn])->field('type,stage,id,financing_manager_id,order_sn,status')->find();
                $orderZtStage = $orderInfo['stage'];
                //审查员审批的时候判断初审结果是否编辑提交了
                $firstInfo = Db::name('trial_first')->where(['order_sn' => $orderSn])->field('is_material,review_rating,risk_rating')->find();
                if($orderZtStage == 1004){
                    if(empty($firstInfo['review_rating']) || empty($firstInfo['risk_rating'])) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '初审结果没有编辑，不能提交审核!');
                }
                //审查员 审查主管 审查经理 审批通过的时候需要所有的问题已经解决
                if($is_approval == 1 && ($orderZtStage == 1004 || $orderZtStage == 1005 || $orderZtStage == 1006)){
                    $trialdataId = Db::name('trial_data')->where(['order_sn' => $orderSn,'type' => 'QUESTION','status' => 0,'delete_time' => null])->value('id');
                    if(!empty($trialdataId)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '有问题没有解决，不能提交审核!');
                }

                //跟单员补齐资料提交审核时判断资料是否补齐
                if($is_approval == 1 && $orderZtStage == 1011){
                    $trialdatasId = Db::name('trial_data')->where(['order_sn' => $orderSn,'type' => 'NODATA','status' => 0,'delete_time' => null])->value('id');
                    if(!empty($trialdatasId)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '有资料没有收齐，不能提交审核!');
                    //if($firstInfo['is_material'] == 1) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '有资料没有收齐,不能提交审核!');
                }
                //验证器验证参数
                $valiDate = validate('SubmitExamination');
                $data=['order_sn'=>$orderSn,'is_approval'=>$is_approval,'proc_id'=>$proc_id,'content'=>$content,'next_user_id'=>$next_user_id,'backtoback'=>$backtoback,
                    'back_proc_id'=>$back_proc_id,'item'=>$item,'process_id'=>$process_id,'process_name'=>$process_name,'stage'=>$stage,'next_process_name'=>$next_process_name,'is_next_user'=>$is_next_user];
                if(!$valiDate->check($data)){
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
                }

                if(!empty($courierNumber) && isset($courierNumber)) $content = $content."快递单号".$courierNumber;
                $config = [
                    'user_id' => $this->userInfo['id'], // 用户id
                    'user_name' => $this->userInfo['name'], // 用户姓名
                    'proc_id' => $proc_id,  // 当前步骤id
                    'content' => $content,  // 审批意见
                    'next_user_id' => $next_user_id,  // 下一步审批人员
                    'backtoback' => $backtoback,  //是否退回之后直接返回本节点
                    'back_proc_id' => $back_proc_id,  // 退回节点id
                    'order_sn' => $orderSn
                ];
                $operate = show_status_name($stage,'ORDER_JYDB_STATUS');   //上一步的操作节点名称
                $workflow = new Workflow($config);
                // 启动事务
                Db::startTrans();
                try{
                    if($is_approval == 1){
                        //待资料入架的时候判判断该订单是否需要派单，不需要派单则看担保费是否收齐
                        $guaranteeInfo = Db::name('order_guarantee')->where(['order_sn' => $orderSn])->field('is_dispatch,guarantee_fee_status,is_instruct,instruct_status')->find();
                        //if($orderZtStage == 1012 && $guaranteeInfo['is_dispatch'] == 0 && $guaranteeInfo['guarantee_fee_status'] == 1) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '该订单不需要进行派单，必须得先把担保费收齐才能进行资料入架!');
                        //if($orderZtStage == 1012 && $guaranteeInfo['is_dispatch'] == 0 && $guaranteeInfo['is_instruct'] == 1 && $guaranteeInfo['instruct_status'] != 3) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '该订单不需要派单，但需要发送指令，请先发送指令才能进行资料入架!');
                        // 审批通过 走审批流
                        $workflow->pass();
                        //将审查员和分单时间添加到订单中
                        if(!empty($next_user_id) && isset($next_user_id)){
                            if(Db::name('order')->where(['order_sn' => $orderSn])->update(['inspector_id' => $next_user_id, 'allot_time' => time()]) <= 0){
                                // 回滚事务
                                Db::rollback();
                                return $this->buildFailed(ReturnCode::ADD_FAILED, '审查员添加失败!');
                            }
                        }
                        //在订单表添加出保函状态和时间
                        if($orderInfo['stage'] == 1010){
                            $res = Db::name('order')->where(['order_sn' => $orderSn])->update(['guarantee_letter_status' => 1, 'guarantee_letter_outtime' => time()]);
                            if($res <= 0){
                                // 回滚事务
                                Db::rollback();
                                return $this->buildFailed(ReturnCode::ADD_FAILED, '修改保函状态失败!');
                            }
                        }
                        $ordeInfo = Db::name('order')->where(['order_sn' => $orderSn])->field('type,stage')->find();
                        //更改资料入架，添加权证
                        $flow_id = $workflow->getFlowId([$ordeInfo['type'],'RISK']);
                        self::addAuthority($orderSn,$flow_id,$orderInfo['type'],$guaranteeInfo['is_dispatch'],$orderInfo);
                        $operate_reason = '';  //原因 如驳回原因
                        $msg = "审批通过,下一节点为：".$next_process_name;
                        //根据流向的下一步节点名称查询出对应的code
                        $back_proc_id = $ordeInfo['stage'];
                    }else{
                        // 审批拒绝
                        $workflow->unpass();
                        $ordeInfo = Db::name('order')->where(['order_sn' => $orderSn])->field('type,stage')->find();
                        $back_proc_id = $ordeInfo['stage'];
                        $next_process_name = show_status_name($back_proc_id,'ORDER_JYDB_STATUS');
                        $msg = "审批驳回,下一节点为".$next_process_name;
                        $operate_reason = $content;  //原因 如驳回原因
                    }

                    //添加注意事项
                    self::addResultOf($orderSn,$process_id,$process_name,$item,$attachment_id_str,$proc_id);
                    /*添加订单操作记录*/
                    $stagestr = $next_process_name; //流向的下一步操作节点名称
                    $operate_node = "风控审批";  //当前操作描述
                    $operate_det = $msg;   //操作详情
                    $operate_table = 'order';  //操作表
                    $operate_table_id = ''; //操作表id
                    OrderComponents::addOrderLog($this->userInfo,$orderSn, $stagestr, $operate_node,$operate,$operate_det,$operate_reason,$back_proc_id,$operate_table,$operate_table_id);
                    // 提交事务
                    Db::commit();
                    return $this->buildSuccess('审批成功');
                }catch (\Exception $e){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::ADD_FAILED, $e->getMessage());
                }
	        }

    /*
     * 是否添加权证
     * @Param {string}  $orderSn   订单编号
     * @Param {int}  $flow_id   流程实例表id
     * @Param {string}  $type   订单类型
     * @Param {int}  $isDispatch   是否需要派单
     * */
    private function addAuthority($orderSn,$flow_id,$type,$isDispatch,$orderInfo){
        //查询该订单当前审核状态 更改资料是否入架状态
        $reviewStatus = Db::name('workflow_entry')->where(['order_sn' => $orderSn,'flow_id' => $flow_id])->value('status');
        if($reviewStatus == 9){
            //改订单资料入架状态 为以入架
            Order::where('order_sn',$orderSn)->update(['is_data_entry' => 1]);
            //JYDB类型，并且不需要派单则添加权证
            if($type == "JYDB" && empty($isDispatch)){
                $OrderWarrant = new OrderWarrant;
                $list = [
                    ['order_sn'=>$orderSn,'warrant_stage'=>1,'create_time' => time(),'update_time' => time()]
                ];
                $OrderWarrant->saveAll($list);
            }
            //风控审核完成，需要存一条发送短信的记录
            $messObj = new Message();
            $messObj->AddmessageRecord($orderInfo['financing_manager_id'], 2, 6, $orderInfo['id'], $orderInfo['order_sn'], $orderInfo['status'], '订单消息', '订单号'.$orderInfo['order_sn'].'已通过风控审批，点击查看详情', 1, 1, 0, 0, '', 'PC风控审批通过', 'order');
        }
    }

	        /*
	         * 添加初审结果注意事项和附件
	         * @Param {string}  $orderSn   订单编号
	         * @Param {int} process_id    流程步骤表主键id
             * @Param {string} process_name    节点名称(当前步骤名称,审批节点)
	         * @Param {string}  attachment_id_str   附件材料,用英文逗号分隔如[1,2,3]
             * @Param {string}  item   注意事项
	         * @Param {int} $proc_id    处理明细表主键id
	         * */
	        private function addResultOf($orderSn,$process_id,$process_name,$item,$attachment_id_str,$proc_id){
                //根据订单号查询出初审id
                $processInfo = [];
                //判断该注意事项是否存在，存在就更改
                $resWork = WorkflowProc::getOne(['id' => $proc_id],'process_id');
                //注意事项表主键id
                $needingAttention = Db::name('trial_process')->where(['order_sn' => $orderSn,'workflow_process_id' => $resWork['process_id']])->value('id');
                $csId = TrialFirst::where(['order_sn' => $orderSn])->value('id')?:0;
                if(empty($needingAttention)){  //不存在就添加
                    $processInfo['order_sn'] = $orderSn;
                    $processInfo['trial_first_id'] = $csId;
                    $processInfo['workflow_process_id'] = $process_id;
                    $processInfo['process_name'] = $process_name;
                    $processInfo['item'] = $item;
                    $processInfo['create_uid'] = $this->userInfo['id'];
                    $processInfo['create_time'] = time();
                    $processInfo['update_time'] = time();
                    $resInfo = TrialProcess::create($processInfo);
                    $trial_process_id = $resInfo->id;
                }else{  //修改
                    $processInfo['workflow_process_id'] = $process_id;
                    $processInfo['item'] = $item;
                    $processInfo['update_time'] = time();
                    //更新
                    Db::name('trial_process')->where(['id' => $needingAttention])->update($processInfo);
                    $trial_process_id = $needingAttention;
                }
                //将该条审批信息的所有附件删除
                Db::name('trial_process_attachment')->where(['order_sn' => $orderSn,'trial_process_id' => $trial_process_id])->update(['delete_time' => time()]);
                //添加注意事项附件表
                if($attachment_id_str) {
                    foreach ($attachment_id_str as $k => $v) {
                        //判断该附件是否已经存在
                        $processInfo = TrialProcessAttachment::get(['attachment_id' => $v]);
                        if (empty($processInfo) && !isset($processInfo)) {  //不存在就添加
                            $processAttachmentInfo['order_sn'] = $orderSn;
                            $processAttachmentInfo['trial_first_id'] = $csId;
                            $processAttachmentInfo['trial_process_id'] = $trial_process_id;
                            $processAttachmentInfo['workflow_process_id'] = $process_id;
                            $processAttachmentInfo['attachment_id'] = $v;
                            $processAttachmentInfo['status'] = 1;
                            $processAttachmentInfo['create_time'] = time();
                            $processAttachmentInfo['update_time'] = time();
                            TrialProcessAttachment::create($processAttachmentInfo);
                        }else{  //存在就将删除时间更改为null
                            Db::name('trial_process_attachment')->where(['attachment_id' => $v])->update(['delete_time' => null]);
                        }
                    }
                }
            }

             // @author 赵光帅
	        /**
	         * @api {post} admin/Approval/showResult 查询初审结果[admin/Approval/showResult]
	         * @apiVersion 1.0.0
	         * @apiName showResult
	         * @apiGroup Approval
	         * @apiSampleRequest admin/Approval/showResult
	         *
	         *
	         * @apiParam {string}  order_sn   订单编号
	         *
             * @apiSuccessExample {json} 返回数据示例:
             * HTTP/1.1 200 OK
             * {
             *   "code": 1,
             *   "msg": "操作成功",
             *   "data": {
             *   "order_sn": "666666666",
             *   "balance_per": 66,
             *   "is_normal": 66,
             *   "review_rating": 66,
             *   "risk_rating": 66,
             *   "is_material": 1,
             *   "is_guarantee": 1,
             *   "is_asset_prove": 0,
             *   "is_guarantee_estate": 0,
             *   "is_guarantee_money": 1,
             *   "is_guarantee_other": 1,
             *   "guarantee_money": "654.11",
             *   "other_way": "我是谁",
             *   "express_no": null,
             *   "problem": [
             *       {
             *      "id": 25,
             *       "describe": "呵呵1213",
             *       "status": 1
             *       },
             *       {
             *      "id": 26,
             *       "describe": "呵呵456",
             *       "status": 0
             *       },
             *       {
             *       "id": 27,
             *       "describe": "呵呵帅那个帅789",
             *       "status": 0
             *       }
             *   ],
             *   "data": [
             *       {
             *       "id": 28,
             *       "describe": "初审1213",
             *       "status": 1
             *       },
             *       {
             *       "id": 29,
             *       "describe": "别别别456",
             *       "status": 0
             *       },
             *       {
             *       "id": 30,
             *       "describe": "呵呵帅那个帅789",
             *       "status": 0
             *       }
             *     ]
             *     "houseinfo": [
             *           {
             *            "id": 26,
             *            "estate_owner": null,
             *            "estate_owner_type": null,
             *            "estate_name": "国际新城",
             *            "estate_certtype": 1,
             *            "estate_certnum": 123456789,
             *            "house_type": 1111,
             *            "estate_district": "440304"
             *           },
             *          {
             *           "id": 28,
             *           "estate_owner": null,
             *           "estate_owner_type": null,
             *           "estate_name": "万达广场",
             *           "estate_certtype": 1,
             *           "estate_certnum": 123456789,
             *           "house_type": 1111,
             *           "estate_district": "440304"
             *           }
             *        ],
             *       "assetproof": [
             *           {
             *           "id": 29,
             *           "estate_owner": null,
             *           "estate_owner_type": null,
             *           "estate_name": "万科",
             *           "estate_certtype": 1,
             *           "estate_certnum": 123456789,
             *           "house_type": 1111,
             *           "estate_district": "440304"
             *           },
             *           {
             *           "id": 30,
             *           "estate_owner": null,
             *           "estate_owner_type": null,
             *           "estate_name": "绿地",
             *           "estate_certtype": 1,
             *           "estate_certnum": 123456789,
             *           "house_type": 1111,
             *           "estate_district": "440304"
             *           }
             *        ]
             *     }
             *   }
	         * @apiSuccess {string}  order_sn   订单编号
             * @apiSuccess {float}  balance_per   负债成数
             * @apiSuccess {int}  is_normal   是否正常单 0正常 1异常
             * @apiSuccess {int}  review_rating   审查评级
             * @apiSuccess {int}  risk_rating   风险评级
             * @apiSuccess {int}  is_material   是否缺资料通过 0未选中,不缺资料   1选中,缺资料
             * @apiSuccess {int}  is_guarantee   是否提供反担保 0未选中,否   1选中,是
             * @apiSuccess {int}  is_asset_prove   是否提供资产证明 0未选中,否   1选中,是
             * @apiSuccess {int}  is_guarantee_estate   是否房产反担保 0未选中,否   1选中,是
             * @apiSuccess {int}  is_guarantee_money   是否保证金反担保 0未选中,否   1选中,是
             * @apiSuccess {int}  is_guarantee_other   是否其它方式反担保 0未选中,否   1选中,是
             * @apiSuccess {float}  guarantee_money   反担保 （保证金）
             * @apiSuccess {string}  other_way   其它方式
             * @apiSuccess {string}  express_no     订单号
             * @apiSuccess {array} problem  问题汇总['id(int)'=>'问题汇总id','describe（string）'=>'问题描述','status{int}'=>'问题状态 0未解决 1已解决']
             * @apiSuccess {array} data  缺少的资料['id(int)'=>'缺少资料id','describe（string）'=>'资料描述','status{int}'=>'资料状态 0未收 1已收']
             * @apiSuccess {array} houseifo 反担保房产信息['id(int)'=>'房产id','estate_owner(string)'=>'产权人姓名','estate_owner_type（int）'=>'产权人类型  1个人 2企业','estate_name{string}'=>'房产名称','estate_region（string）'=>所属城区,'estate_certtype（int）'=>'产证类型','estate_certnum{int}'=>'产证编码','house_type（int）'=>房屋类型 1分户 2分栋,
             * 'house_id（int）'=>房号id ,'estate_ecity（int）'=>城市简称,'estate_district（int）'=>城区简称,'estate_area'=>房产面积,'building_name'=>楼盘名称,'estate_alias'=>楼盘别名,'estate_unit'=>楼阁名称,'estate_unit_alias'=>楼阁别名,'estate_floor'=>楼层,'estate_floor_plusminus'=>楼层类型,'estate_house'=>房号]
             * @apiSuccess {array} assetproof 资产证明房产信息['id(int)'=>'房产id','estate_owner(string)'=>'产权人姓名','estate_owner_type（int）'=>'产权人类型  1个人 2企业','estate_name{string}'=>'房产名称','estate_region（string）'=>所属城区,'estate_certtype（int）'=>'产证类型','estate_certnum{int}'=>'产证编码','house_type（int）'=>房屋类型 1分户 2分栋,
             * 'house_id（int）'=>房号id ,'estate_ecity（int）'=>城市简称,'estate_district（int）'=>城区简称,'estate_area'=>房产面积,'building_name'=>楼盘名称,'estate_alias'=>楼盘别名,'estate_unit'=>楼阁名称,'estate_unit_alias'=>楼阁别名,'estate_floor'=>楼层,'estate_floor_plusminus'=>楼层类型,'estate_house'=>房号]
	         */

	        public function showResult(){
	        	$orderSn = input('order_sn');
	        	if(empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
                try{
                    $mapFirs['order_sn'] = $orderSn;
                    $mapFirs['delete_time'] = NULL;
                    $firsField = 'order_sn,balance_per,is_normal,review_rating,risk_rating,is_material,is_guarantee,is_asset_prove,is_guarantee_estate,is_guarantee_money,is_guarantee_other,guarantee_money,other_way,express_no';
                    $resTrial = TrialFirst::getOne($mapFirs,$firsField);
                    //查询出问题汇总
                    if(!empty($resTrial)){
                        $problemMap['order_sn'] = $orderSn;
                        $problemMap['type'] = 'QUESTION';
                        $problemMap['delete_time'] = NULL;
                        $resProblem = TrialData::getAll($problemMap,'id,describe,status');
                        if(!empty($resProblem)) $resTrial['problem'] = $resProblem;
                        //判断是否缺资料通过
                        //if($resTrial['is_material'] === 1){
                            $dataMap['order_sn'] = $orderSn;
                            $dataMap['type'] = 'NODATA';
                            $dataMap['delete_time'] = NULL;
                            $resData = TrialData::getAll($dataMap,'id,describe,status');
                            if(!empty($resData)) $resTrial['data'] = $resData;
                        //}
                        $houseType =  dictionary_reset((new Dictionary)->getDictionaryByType('ORDER_HOUSE_TYPE'));
                        $propertyType =  dictionary_reset((new Dictionary)->getDictionaryByType('PROPERTY_TYPE'));
                        //判断是否提供房产反担保
                        if($resTrial['is_guarantee'] === 1 && $resTrial['is_guarantee_estate'] === 1){
                            $houseMap['delete_time'] = NULL;
                            $houseMap['status'] = 1;
                            $houseMap['estate_usage'] = 'FDB';
                            $houseMap['order_sn'] = $orderSn;
                            $houseField = 'id,estate_owner,estate_owner_type,estate_name,estate_certtype,estate_certnum,house_type,estate_district,house_id,estate_region,estate_ecity,estate_area,building_name,estate_alias,estate_unit,estate_unit_alias,estate_floor,estate_floor_plusminus,estate_house';
                            $houseInfo = Estate::getAll($houseMap,$houseField);
                            if($houseInfo){
                                foreach($houseInfo as $key => $val){
                                    if($val['house_type']){
                                        $houseInfo[$key]['house_type'] = $houseType[$val['house_type']] ? $houseType[$val['house_type']]:'';
                                    }
                                    if($val['estate_certtype']){
                                        $houseInfo[$key]['estate_certtype'] = $propertyType[$val['estate_certtype']] ? $propertyType[$val['estate_certtype']]:'';
                                    }

                                }
                            }
                            if(!empty($houseInfo)) $resTrial['houseinfo'] = $houseInfo;
                        }
                        //判断是否提供资产证明
                        if($resTrial['is_asset_prove'] === 1){
                            $assetMap['delete_time'] = NULL;
                            $assetMap['status'] = 1;
                            $assetMap['estate_usage'] = 'ZCZM';
                            $assetMap['order_sn'] = $orderSn;
                            $houseField = 'id,estate_owner,estate_owner_type,estate_name,estate_certtype,estate_certnum,house_type,estate_district,house_id,estate_region,estate_ecity,estate_area,building_name,estate_alias,estate_unit,estate_unit_alias,estate_floor,estate_floor_plusminus,estate_house';
                            $assetProof = Estate::getAll($assetMap,$houseField);
                            if($assetProof){
                                foreach($assetProof as $key => $val){
                                    if($val['house_type']){
                                        $assetProof[$key]['house_type'] = $houseType[$val['house_type']] ? $houseType[$val['house_type']]:'';
                                    }
                                    if($val['estate_certtype']){
                                        $assetProof[$key]['estate_certtype'] = $propertyType[$val['estate_certtype']] ? $propertyType[$val['estate_certtype']]:'';
                                    }

                                }
                            }
                            if(!empty($assetProof)) $resTrial['assetproof'] = $assetProof;
                        }
                    }
                    //根据订单号查询出订单状态
                    $resTrial['stage'] = Db::name('order')->where(['order_sn' => $orderSn])->value('stage');
                    return $this->buildSuccess($resTrial);
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
                }
       }

        // @author 赵光帅
       /**
        * @api {post} admin/Approval/addResult 初审结果提交[admin/Approval/addResult]
        * @apiVersion 1.0.0
        * @apiName addResult
        * @apiGroup Approval
        * @apiSampleRequest admin/Approval/addResult
        *
        * @apiParam {string}  order_sn   订单编号
        * @apiParam {float}  balance_per   负债成数
        * @apiParam {int}  is_normal   是否正常单 0正常 1异常单
        * @apiParam {int}  review_rating   审查评级
        * @apiParam {int}  risk_rating   风险评级
        * @apiParam {int}  fund_source   资金来源  1自由资金 2渠道资金 3自有+渠道
        * @apiParam {string}  contract   合同资料  1 (华安) 2 (365)  选择多个用英文“,”分隔
        * @apiParam {float}  own_fund   自有资金
        * @apiParam {int}  is_material   是否缺资料通过 0未选中,不缺资料   1选中,缺资料
        * @apiParam {int}  is_guarantee   是否提供反担保 0未选中,否   1选中,是
        * @apiParam {int}  is_asset_prove   是否提供资产证明 0未选中,否   1选中,是
        * @apiParam {int}  is_guarantee_estate   是否房产反担保 0未选中,否   1选中,是
        * @apiParam {int}  is_guarantee_money   是否保证金反担保 0未选中,否   1选中,是
        * @apiParam {int}  is_guarantee_other   是否其它方式反担保 0未选中,否   1选中,是
        * @apiParam {float}  guarantee_money   反担保 （保证金）
        * @apiParam {string}  other_way   其它方式
        * @apiParam {array} problem  问题汇总['id(int)'=>'问题汇总信息id,新增问题,则这个id可以为空','problem_describe（string）'=>'问题描述','problem_status{int}'=>'问题状态 0未解决 1已解决']
        * @apiParam {array} data  缺少的资料['id(int)'=>'缺少的资料id,新增资料,则这个id为空','problem_describe（string）'=>'资料描述','problem_status{int}'=>'资料状态 0未收 1已收']
        * @apiParam {array} houseinfo  反担保房产信息['id(int)'=>'房产id,新增房产,则这个id为空','estate_owner(string)'=>'产权人姓名','estate_owner_type（int）'=>'产权人类型  1个人 2企业','estate_name{string}'=>'房产名称',
        * 'estate_region（string）'=>所属城区,'estate_certtype（int）'=>'产证类型','estate_certnum{int}'=>'产证编码','house_type（int）'=>房屋类型 1分户 2分栋,
        * 'house_id（int）'=>房号id ,'estate_ecity（int）'=>城市简称,'estate_district（int）'=>城区简称,'estate_area'=>房产面积,'building_name'=>楼盘名称,'estate_alias'=>楼盘别名,'estate_unit'=>楼阁名称,'estate_unit_alias'=>楼阁别名,'estate_floor'=>楼层,'estate_floor_plusminus'=>楼层类型,'estate_house'=>房号]
        * @apiParam {array} assetproof 资产证明房产信息['id(int)'=>'房产id,新增房产,则这个id为空','estate_owner(string)'=>'产权人姓名','estate_owner_type（int）'=>'产权人类型  1个人 2企业','estate_name{string}'=>'房产名称','estate_region（string）'=>所属城区,'estate_certtype（int）'=>'产证类型','estate_certnum{int}'=>'产证编码','house_type（int）'=>房屋类型 1分户 2分栋,'house_id（int）'=>房号id，
        *  'estate_ecity（int）'=>城市简称,'estate_district（int）'=>城区简称,'estate_area'=>房产面积,'building_name'=>楼盘名称,'estate_alias'=>楼盘别名,'estate_unit'=>楼阁名称,'estate_unit_alias'=>楼阁别名,'estate_floor'=>楼层,'estate_floor_plusminus'=>楼层类型,'estate_house'=>房号]
        */

	        public function addResult(){
	        	$orderSn = input('order_sn');
                $balancePer = input('balance_per');
                $isNormal = input('is_normal')?:0;
                $reviewRating = input('review_rating');
                $riskRating = input('risk_rating');
                $isMaterial = input('is_material')?:0;
                $isGuarantee = input('is_guarantee')?:0;
                $isassetProve = input('is_asset_prove')?:0;
                $isguaranteeEstate = input('is_guarantee_estate')?:0;
                $isGuaranteemoney = input('is_guarantee_money')?:0;
                $isGuaranteeother = input('is_guarantee_other')?:0;
                $guaranteeMoney = input('guarantee_money');
                $otherWay = input('other_way');
                $proBlem = input('problem/a');
                $daTa = input('data/a');
                $houId = input('houseinfo/a');
                $assetProof = input('assetproof/a');
                if($isMaterial == 1 && empty($daTa[0]['problem_describe'])){
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '资料描述不能为空');
                }
                if($isGuarantee == 1 && $isguaranteeEstate == 1 && empty($houId[0]['estate_name'])){
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '反担保房产选择不能为空');
                }
                if($isGuarantee == 1 && $isGuaranteemoney == 1 && empty($guaranteeMoney)){
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '反担保保证金不能为空');
                }
                if($isGuarantee == 1 && $isGuaranteeother == 1 && empty($otherWay)){
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '反担保其他方式不能为空');
                }
                if($isassetProve == 1 && empty($assetProof[0]['estate_name'])){
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '资产证明不能为空');
                }

                //验证器验证参数
                $valiDate = validate('TrialPrel');
                $data=['order_sn'=>$orderSn,'balance_per'=>$balancePer,'is_normal'=>$isNormal,'review_rating'=>$reviewRating,'risk_rating'=>$riskRating,
                    'is_material'=>$isMaterial,'is_guarantee'=>$isGuarantee,'is_asset_prove'=>$isassetProve,'is_guarantee_estate'=>$isguaranteeEstate,
                    'is_guarantee_money'=>$isGuaranteemoney,'is_guarantee_other'=>$isGuaranteeother,
                    'guarantee_money'=>$guaranteeMoney,'other_way'=>$otherWay];
                if(!$valiDate->check($data)){
                    return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
                }

                // 启动事务
                Db::startTrans();
                try{
                    $firstInfo = TrialFirst::get(['order_sn' => $orderSn]);
                    //判断该订单号对应的初审信息是否已经存在
                    if(!empty($firstInfo)){   //已经存在 编辑记录
                        $firstInfo->balance_per = $balancePer;
                        $firstInfo->is_normal = $isNormal;
                        $firstInfo->review_rating = $reviewRating;
                        $firstInfo->risk_rating = $riskRating;
                        $firstInfo->is_material = $isMaterial;
                        $firstInfo->is_guarantee = $isGuarantee;
                        $firstInfo->is_asset_prove = $isassetProve;
                        $firstInfo->is_guarantee_estate = $isguaranteeEstate;
                        $firstInfo->is_guarantee_money = $isGuaranteemoney;
                        $firstInfo->is_guarantee_other = $isGuaranteeother;
                        $firstInfo->update_time = time();
                        if($isGuaranteemoney == 1){
                            $firstInfo->guarantee_money = $guaranteeMoney;
                        }else{
                            $firstInfo->guarantee_money = '';
                        }
                        if($isGuaranteeother == 1){
                            $firstInfo->other_way = $otherWay;
                        }else{
                            $firstInfo->other_way = '';
                        }

                        //更新初审信息表数据
                        $firstInfo->save();

                        //添加或更新问题记录
                        if(!empty($proBlem)){
                            $resInfo = self::updateProblemData($proBlem,1,$orderSn,$firstInfo->id);
                            if($resInfo) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $resInfo);
                        }

                        //添加或更新缺少的资料
                        if(!empty($daTa) && $isMaterial == 1){
                            self::updateProblemData($daTa,2,$orderSn,$firstInfo->id);
                        }
                    }else{ //不存在，添加记录
                        $chuShen = [];
                        $chuShen['order_sn'] = $orderSn;
                        $chuShen['balance_per'] = $balancePer;
                        $chuShen['is_normal'] = $isNormal;
                        $chuShen['review_rating'] = $reviewRating;
                        $chuShen['risk_rating'] = $riskRating;
                        $chuShen['is_material'] = $isMaterial;
                        $chuShen['is_guarantee'] = $isGuarantee;
                        $chuShen['is_asset_prove'] = $isassetProve;
                        $chuShen['is_guarantee_estate'] = $isguaranteeEstate;
                        $chuShen['is_guarantee_money'] = $isGuaranteemoney;
                        $chuShen['is_guarantee_other'] = $isGuaranteeother;
                        $chuShen['create_uid'] = $this->userInfo['id'];
                        $chuShen['create_time'] = time();
                        if($isGuaranteemoney == 1){
                            $chuShen['guarantee_money'] = $guaranteeMoney;
                        }
                        if($isGuaranteeother == 1){
                            $chuShen['other_way'] = $otherWay;
                        }
                        //添加初审信息
                        $addChus = TrialFirst::create($chuShen);

                        //添加问题记录
                        if(!empty($proBlem)){
                              $resInfo = self::addProblemData($proBlem,'QUESTION',$orderSn,$addChus->id);
                              if($resInfo){
                                  // 回滚事务
                                  Db::rollback();
                                  return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $resInfo);
                              }
                        }
                        //添加缺少的资料
                        if(!empty($daTa) && $isMaterial == 1){
                              self::addProblemData($daTa,'NODATA',$orderSn,$addChus->id);
                        }
                    }
                    //添加反担保
                    if(!empty($houId) && $isGuarantee == 1 && $isguaranteeEstate == 1){
                        $fdbInfo = self::addFansDanbao($houId,$orderSn,'FDB');
                        if($fdbInfo){
                            // 回滚事务
                            Db::rollback();
                            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $fdbInfo);
                        }
                    }
                    //添加资产证明
                    if(!empty($assetProof) && $isassetProve == 1){
                        $zczmInfo = self::addFansDanbao($assetProof,$orderSn,'ZCZM');
                        if($zczmInfo){
                            // 回滚事务
                            Db::rollback();
                            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $zczmInfo);
                        }
                    }
                    /*添加订单操作记录*/
                    $stage = "待审查员审批";
                    $operate = "待审查员审批";
                    $operate_node = "提交初审结果";
                    $operate_det = $this->userInfo['name']."提交初审结果";
                    $operate_reason = '';
                    $stage_code = 1004;
                    $operate_table = 'order';
                    OrderComponents::addOrderLog($this->userInfo,$orderSn, $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
                    // 提交事务
                    Db::commit();
                    return $this->buildSuccess('编辑成功');

                }catch (\Exception $e){
                    // 回滚事务
                    Db::rollback();
                    return $this->buildFailed(ReturnCode::ADD_FAILED, '编辑失败'.$e->getMessage());
                }

	        }

            /*
             * @author 赵光帅
             * 更新初审信息时,添加或更新反担保和资产证明的方法
             * @Param {array}  $dataInfo   数据
             * @Param {string}  $orderSn   订单号
             * @Param {string}  $type   FDB 添加反担保房产信息 ZCZM 添加资产证明房产信息
             * */
            protected function addFansDanbao($dataInfo,$orderSn,$type){
                foreach ($dataInfo as $k => $v){
                    if($type == "FDB" && empty($v['estate_owner'])) return '请填写反担保产权人姓名';
                    if($type == "FDB" && empty($v['estate_name'])) return '请填写反担保房产名称';
                    if($type == "FDB" && empty($v['estate_certnum'])) return '请填写反担保产证编码';
                    if($type == "FDB" && empty($v['estate_certtype'])) return '请选择反担保房产产证类型';
                    if($type == "FDB" && empty($v['house_type'])) return '请选择反担保房产房屋类型';
                    if($type == "ZCZM" && empty($v['estate_owner'])) return '请填写资产证明产权人姓名';
                    if($type == "ZCZM" && empty($v['estate_name'])) return '请填写资产证明房产名称';
                    if($type == "ZCZM" && empty($v['estate_certnum'])) return '请填写资产证明产证编码';
                    if($type == "ZCZM" && empty($v['estate_certtype'])) return '请选择资产证明产证类型';
                    if($type == "ZCZM" && empty($v['house_type'])) return '请选择资产证明房屋类型';
                    //判断该订单的该条反担保是否存在
                    if(empty($v['id'])){
                            $addDatapross['estate_owner'] = $v['estate_owner'];
                            $addDatapross['estate_owner_type'] = $v['estate_owner_type'];
                            $addDatapross['estate_name'] = $v['estate_name'];
                            $addDatapross['estate_region'] = $v['estate_region'];
                            $addDatapross['estate_certtype'] = $v['estate_certtype'];
                            $addDatapross['estate_certnum'] = $v['estate_certnum'];
                            $addDatapross['house_type'] = $v['house_type'];
                            $addDatapross['house_id'] = $v['house_id'];
                            $addDatapross['estate_usage'] = $type;
                            $addDatapross['order_sn'] = $orderSn;
                            $addDatapross['create_time'] = time();
                            $addDatapross['estate_ecity'] = $v['estate_ecity'];
                            $addDatapross['estate_area'] = $v['estate_area'];
                            $addDatapross['building_name'] = $v['building_name'];
                            $addDatapross['estate_district'] = $v['estate_district'];
                            $addDatapross['estate_alias'] = $v['estate_alias'];
                            $addDatapross['estate_unit'] = $v['estate_unit'];
                            $addDatapross['estate_unit_alias'] = $v['estate_unit_alias'];
                            $addDatapross['estate_floor'] = $v['estate_floor'];
                            $addDatapross['estate_floor_plusminus'] = $v['estate_floor_plusminus'];
                            $addDatapross['estate_house'] = $v['estate_house'];
                            //添加
                            Estate::create($addDatapross);
                    }else{ //更新
                        $estateInfos = Estate::get(['id' => $v['id']]);
                        $estateInfos->estate_owner = $v['estate_owner'];
                        $estateInfos->estate_owner_type = $v['estate_owner_type'];
                        $estateInfos->estate_name = $v['estate_name'];
                        $estateInfos->estate_region = $v['estate_region'];
                        $estateInfos->estate_certtype = $v['estate_certtype'];
                        $estateInfos->estate_certnum = $v['estate_certnum'];
                        $estateInfos->house_type = $v['house_type'];
                        $estateInfos->house_id = $v['house_id'];
                        $estateInfos->estate_usage = $type;
                        $estateInfos->update_time = time();
                        $estateInfos->estate_ecity = $v['estate_ecity'];
                        $estateInfos->estate_area = $v['estate_area'];
                        $estateInfos->building_name = $v['building_name'];
                        $estateInfos->estate_district = $v['estate_district'];
                        $estateInfos->estate_alias = $v['estate_alias'];
                        $estateInfos->estate_unit = $v['estate_unit'];
                        $estateInfos->estate_unit_alias = $v['estate_unit_alias'];
                        $estateInfos->estate_floor = $v['estate_floor'];
                        $estateInfos->estate_floor_plusminus = $v['estate_floor_plusminus'];
                        $estateInfos->estate_house = $v['estate_house'];
                        //更新反担保和资产证明
                        $estateInfos->save();
                    }
                }
            }

            /*
             * @author 赵光帅
             * 更新订单问题和缺少资料的方法
             * @Param {array}  $dataInfo   数据
             * @Param {int}  $type   ；1 更新问题 2 更新缺少资料
             * @Param {string}  $orderSn   订单号
             * @Param {int}  $id   订单初审表主键id
             * */
            protected function updateProblemData($datasInfo,$type,$orderSn,$id){
                if($type === 1){
                    foreach ($datasInfo as $k => $v){
                        if(empty($v['problem_describe']) && ($v['problem_status'] !== '')) return '问题描述不能为空';
                        if(($v['problem_status'] === '') && !empty($v['problem_describe'])) return '请选择问题是否解决';
                        if(isset($v['problem_describe']) && !empty($v['problem_describe'])){
                            if(!empty($v['id'])){  //更新问题
                                $firstsInfos = TrialData::get(['id' => $v['id']]);
                                $firstsInfos->status = $v['problem_status'];
                                $firstsInfos->describe = $v['problem_describe'];
                                $firstsInfos->update_time = time();
                                //更新问题
                                $firstsInfos->save();
                            }else{  //添加问题
                                $addDatapross['describe'] = $v['problem_describe'];
                                $addDatapross['status'] = $v['problem_status'];
                                $addDatapross['order_sn'] = $orderSn;
                                $addDatapross['type'] = 'QUESTION';
                                $addDatapross['create_time'] = time();
                                $addDatapross['trial_first_id'] = $id;
                                //添加
                                TrialData::create($addDatapross);
                            }
                        }
                    }
                }else{
                    foreach ($datasInfo as $k => $v){
                        if(isset($v['problem_describe']) && !empty($v['problem_describe'])){
                            if(!empty($v['id'])){ //更新资料
                                $firstsInfos = TrialData::get(['id' => $v['id']]);
                                $firstsInfos->status = $v['problem_status'];
                                $firstsInfos->describe = $v['problem_describe'];
                                $firstsInfos->update_time = time();
                                //更新缺少资料
                                $firstsInfos->save();
                            }else{ //添加资料
                                $addDatapross['describe'] = $v['problem_describe'];
                                $addDatapross['status'] = $v['problem_status'];
                                $addDatapross['order_sn'] = $orderSn;
                                $addDatapross['type'] = 'NODATA';
                                $addDatapross['create_time'] = time();
                                $addDatapross['trial_first_id'] = $id;
                                //添加
                                TrialData::create($addDatapross);
                            }
                        }
                    }
                }
            }

	        /*
	         * @author 赵光帅
	         * 添加问题和缺少资料的方法
	         * @Param {array}  $dataInfo   数据
	         * @Param {string}  $type   QUESTION 添加问题 NODATA 添加缺少资料
	         * @Param {string}  $orderSn   订单号
	         * @Param {int}  $id   订单初审表主键id
	         * */
	         protected function addProblemData($dataInfo,$type,$orderSn,$id){
                 foreach ($dataInfo as $k => $v){
                     if($type == "QUESTION" && empty($v['problem_describe']) && ($v['problem_status'] !== '')) return '问题描述不能为空';
                     if($type == "QUESTION" && ($v['problem_status'] === '') && !empty($v['problem_describe'])) return '请选择问题是否解决';
                     if(isset($v['problem_describe']) && !empty($v['problem_describe'])){
                         $addDatapross['describe'] = $v['problem_describe'];
                         $addDatapross['status'] = $v['problem_status'];
                         $addDatapross['order_sn'] = $orderSn;
                         $addDatapross['type'] = $type;
                         $addDatapross['create_time'] = time();
                         $addDatapross['trial_first_id'] = $id;
                         //添加
                         TrialData::create($addDatapross);
                     }
                 }
             }

            // @author 赵光帅
            /**
             * @api {post} admin/Approval/delProblem 删除问题汇总与缺少资料[admin/Approval/delProblem]
             * @apiVersion 1.0.0
             * @apiName delProblem
             * @apiGroup Approval
             * @apiSampleRequest admin/Approval/delProblem
             *
             *
             * @apiParam {int}  id   数据的id
             *
             */

            public function delProblem(){
                $id = input('id');
                if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');
                $user = TrialData::get($id);
                $user->status = -1;
                $user->delete_time =time();
                $orderSn = $user->order_sn;
                $res = $user->save();
                if($res){
                    /*添加订单操作记录*/
                    $stage = "待审查员审批";
                    $operate = "待审查员审批";
                    $operate_node = "删除问题汇总或者缺少资料";
                    $operate_det = $this->userInfo['deptname'].'=>'.$this->userInfo['name']."删除问题汇总或者缺少资料，对应trial_data表主键ID=> ".$id;
                    $operate_reason = '';
                    $stage_code = 1004;
                    $operate_table = 'order';
                    OrderComponents::addOrderLog($this->userInfo,$orderSn, $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
                    return $this->buildSuccess("删除成功");
                }else{
                    return $this->buildFailed(ReturnCode::DELETE_FAILED, '删除失败');
                }




            }

            // @author 赵光帅
            /**
             * @api {post} admin/Approval/delGuarantee 删除房产担保与资产证明[admin/Approval/delGuarantee]
             * @apiVersion 1.0.0
             * @apiName delGuarantee
             * @apiGroup Approval
             * @apiSampleRequest admin/Approval/delGuarantee
             *
             *
             * @apiParam {int}  id   房产表id
             *
             *
             */

            public function delGuarantee(){
                $id = input('id');
                if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'id不能为空!');
                $user = Estate::get($id);
                $user->status = -1;
                $user->delete_time =time();
                $orderSn = $user->order_sn;
                $res = $user->save();
                if($res){
                    /*添加订单操作记录*/
                    $stage = "待审查员审批";
                    $operate = "待审查员审批";
                    $operate_node = "删除房产担保与资产证明";
                    $operate_det = $this->userInfo['deptname'].'=>'.$this->userInfo['name']."删除房产担保与资产证明，对应estate表主键ID=> ".$id;
                    $operate_reason = '';
                    $stage_code = 1004;
                    $operate_table = 'order';
                    OrderComponents::addOrderLog($this->userInfo,$orderSn, $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
                    return $this->buildSuccess("删除成功");
                }else{
                    return $this->buildFailed(ReturnCode::DELETE_FAILED, '删除失败');
                }


            }

	        // @author 赵光帅
	        /**
	         * @api {post} admin/Approval/dataList 缺少的资料列表[admin/Approval/dataList]
	         * @apiVersion 1.0.0
	         * @apiName dataList
	         * @apiGroup Approval
	         * @apiSampleRequest admin/Approval/dataList
	         *
	         *
	         * @apiParam {string}  order_sn   订单编号
	         *
	         * @apiSuccess {string}  describe    资料描述
             * @apiSuccess {int}  status    0未收 1已收
	         */

	        public function dataList(){
	        	$orderSn = input('order_sn');
	        	if(empty($orderSn)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '订单编号不能为空!');
                $trialField = 'id,describe,status';
                $trMap['order_sn'] = $orderSn;
                $trMap['type'] = 'NODATA';
                $trMap['delete_time'] = NULL;
                try{
                    $trialInfo = TrialData::getAll($trMap,$trialField);
                    return $this->buildSuccess($trialInfo);
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::DATA_EXISTS, '查询资料列表失败');
                }


	        }

	        // @author 赵光帅
	        /**
	         * @api {post} admin/Approval/addData 提交资料[admin/Approval/addData]
	         * @apiVersion 1.0.0
	         * @apiName addData
	         * @apiGroup Approval
	         * @apiSampleRequest admin/Approval/addData
	         *
	         * @apiParam {array}  datainfo    缺少的资料['id(int)'=>'缺少的资料id','describe（string）'=>'资料描述','status{int}'=>'资料状态 0未收 1已收']
	         *
	         */

	        public function addData(){
	        	$zlInfo = input('datainfo/a');
	        	if(empty($zlInfo)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '提交信息不能为空!');
	        	try{
                    foreach ($zlInfo as $k => $v){
                        $trialData = TrialData::get($v['id']);
                        $trialData ->describe = $v['describe'];
                        $trialData ->status = $v['status'];
                        $trialData->save();
                        $order_sn = $trialData['order_sn'];
                    }
                    /*添加订单操作记录*/
                    $stage = "待跟单员补齐资料";
                    $operate = "待跟单员补齐资料";
                    $operate_node = "补齐缺少的资料";
                    $operate_det = $this->userInfo['name']."补齐缺少的资料";
                    $operate_reason = '';
                    $stage_code = 1011;
                    $operate_table = 'order';
                    OrderComponents::addOrderLog($this->userInfo,$order_sn, $stage, $operate_node,$operate,$operate_det,$operate_reason,$stage_code,$operate_table);
                    return $this->buildSuccess('修改成功');
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::UPDATE_FAILED, '修改资料列表失败');
                }
	        }

            /**
             * @api {post} admin/Approval/exportDistribute 导出风控管理-分单表[admin/Approval/exportDistribute]
             * @apiVersion 1.0.0
             * @apiName exportDistribute
             * @apiGroup Approval
             * @apiSampleRequest admin/Approval/exportDistribute
             *
             * @apiParam {int} create_uid    人员id
             * @apiParam {int} subordinates    1含下属 0不含下属
             * @apiParam {int} date_type     1分单日期 2出保日期
             * @apiParam {int}  start_time   开始时间
             * @apiParam {int}  end_time   结束时间
             * @apiParam {int} type    订单类型
             * @apiParam {int} stage    订单状态
             * @apiParam {int} estate_ecity    城市
             * @apiParam {int} estate_district    城区
             * @apiParam {int} search_text    关键字搜索
             *
             */
	        public function exportDistribute(){
                $createUid = $this->request->post('create_uid');
                $subordinates = $this->request->post('subordinates');
                $startTime = strtotime($this->request->post('start_time'));
                $endTime = strtotime($this->request->post('end_time'));
                $dateType = $this->request->post('date_type');//1分单日期 2出保日期
                $type = $this->request->post('type');
                $stage = $this->request->post('stage');
                $estateEcity = $this->request->post('estate_ecity');
                $estate_district= $this->request->post('estate_district');
                $searchText = $this->request->post('search_text','','trim');
                $userId = $this->userInfo['id'];
                $map = [];
                //用户判断//
                if ($createUid) {
                    if ($subordinates == 1) {
                        $userStr = SystemUser::getOrderPowerStr($createUid);
                    } else {
                        $userStr = $createUid;
                    }
                    $map['a.financing_manager_id'] = ['in', $userStr];
                }
                $whereTime = "";
                if($startTime && $endTime){

                    if($startTime > $endTime){
                        $startTime = $startTime+86399;
                        $whereTime = array(array('egt', $endTime), array('elt', $startTime));
                    }else{
                        $endTime = $endTime+86399;
                        $whereTime = array(array('egt', $startTime), array('elt', $endTime));
                    }
                }elseif($startTime){
                    $whereTime = ['egt',$startTime];
                }elseif($endTime){
                    $endTime = $endTime+86399;
                    $whereTime = ['elt',$endTime];
                }
                if(!empty($whereTime)){
                    if(!empty($dateType)&&$dateType==1){
                        $map['a.allot_time'] = $whereTime;
                    }elseif (!empty($dateType)&&$dateType==2) {
                        $map['a.guarantee_letter_outtime'] = $whereTime;
                    } else {
                        $map['a.create_time'] = $whereTime;
                    }
                }
                $type && $map['a.type'] = $type;
                $stage && $map['a.stage'] = $stage;
                $estateEcity && $map['b.estate_ecity'] = $estateEcity;
                $estate_district && $map['b.estate_district'] = $estate_district;
                $searchText && $map['b.estate_name|a.order_sn']=['like', "%{$searchText}%"];
                $map['a.delete_time'] = NULL;
                $map['a.status'] = ['in','1,2'];
    
                $list =WorkflowProc::distribute_list($map);
                //return json($list);
                try{
                    $spreadsheet = new Spreadsheet();
                    $resInfo = $list;
                    $head = ['0' => '序号','1' => '业务单号','2' => '业务类型', '3' => '担保金额/元','4' => '分单日期' ,
                        '5' => '审查员', '6' => '理财经理','7' => '所属部门','8' => '部门经理'];
                    array_unshift($resInfo,$head);
                    //$fileName = iconv("UTF-8", "GB2312//IGNORE", '风控管理-分单表' . date('Y-m-dHis'));
                    $fileName = ''.date('Y-m-dHis');//Distribute
                    //$fileName = '风控管理-分单表'.date('Y-m-d').mt_rand(1111,9999);
                    
                    $spreadsheet->getActiveSheet()->fromArray($resInfo);
                    $spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true)->setName('Arial')->setSize(12);
                    $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $styleArray = [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ];
                    $worksheet->getStyle('A1:I1')->applyFromArray($styleArray);
                    // $spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
                    $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $Path = ROOT_PATH . 'public' . DS . 'uploads'.DS.'download'.DS.date('Ymd');
                    if(!file_exists($Path))
                    {
                        //检查是否有该文件夹，如果没有就创建，并给予最高权限
                        mkdir($Path, 0700);
                    }
                    $pathName = $Path.DS .$fileName.'.Xlsx';
                    $objWriter->save($pathName);
                    //$retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS . iconv("GB2312", "UTF-8", $fileName) . '.Xlsx';
                    $retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS .  $fileName . '.Xlsx';
                    return $this->buildSuccess(['url' => $retuurl]);
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '导出失败!'.$e->getMessage());
                }
            }
            /**
             * @api {post} admin/Approval/exportGuaranteeLetterOut 导出风控管理-出保函表[admin/Approval/exportGuaranteeLetterOut]
             * @apiVersion 1.0.0
             * @apiName exportGuaranteeLetterOut
             * @apiGroup Approval
             * @apiSampleRequest admin/Approval/exportGuaranteeLetterOut
             *
             * @apiParam {int} create_uid    人员id
             * @apiParam {int} subordinates    1含下属 0不含下属
             * @apiParam {int} date_type     1分单日期 2出保日期
             * @apiParam {int}  start_time   开始时间
             * @apiParam {int}  end_time   结束时间
             * @apiParam {int} type    订单类型
             * @apiParam {int} stage    订单状态
             * @apiParam {int} estate_ecity    城市
             * @apiParam {int} estate_district    城区
             * @apiParam {int} search_text    关键字搜索
             *
             */
            public function exportGuaranteeLetterOut(){
                $createUid = $this->request->post('create_uid');
                $subordinates = $this->request->post('subordinates');
                $startTime = strtotime($this->request->post('start_time'));
                $endTime = strtotime($this->request->post('end_time'));
                $dateType = $this->request->post('date_type');//1分单日期 2出保日期
                $type = $this->request->post('type');
                $stage = $this->request->post('stage');
                $estateEcity = $this->request->post('estate_ecity');
                $estate_district= $this->request->post('estate_district');
                $searchText = $this->request->post('search_text','','trim');
                $userId = $this->userInfo['id'];
                $map = [];
                //用户判断//
                if ($createUid) {
                    if ($subordinates == 1) {
                        $userStr = SystemUser::getOrderPowerStr($createUid);
                    } else {
                        $userStr = $createUid;
                    }
                    $map['a.financing_manager_id'] = ['in', $userStr];
                }
                $whereTime = "";
                if($startTime && $endTime){
                    if($startTime > $endTime){
                        $startTime = $startTime+86399;
                        $whereTime = array(array('egt', $endTime), array('elt', $startTime));
                    }else{
                        $endTime = $endTime+86399;
                        $whereTime = array(array('egt', $startTime), array('elt', $endTime));
                    }
                }elseif($startTime){
                    $whereTime = ['egt',$startTime];
                }elseif($endTime){
                    $endTime = $endTime+86399;
                    $whereTime = ['elt',$endTime];
                }
                if(!empty($whereTime)){
                    if (!empty($dateType)&&$dateType==2) {
                        $map['a.guarantee_letter_outtime'] = $whereTime;
                    } else {
                        $map['a.create_time'] = $whereTime;
                    }
                }
                $type && $map['a.type'] = $type;
                $stage && $map['a.stage'] = $stage;
                $estateEcity && $map['e.estate_ecity'] = $estateEcity;
                $estate_district && $map['e.estate_district'] = $estate_district;
                $searchText && $map['e.estate_name|a.order_sn']=['like', "%{$searchText}%"];
                $map['a.delete_time'] = NULL;
                $map['a.status'] = ['in','1,2'];
                $map['d.is_deleted']=1;
                $map['d.process_id']=20;
                $list =WorkflowProc::guaranteeLetterOut_list($map);
                //return json($list);
                try{
                    $spreadsheet = new Spreadsheet();
                    $resInfo = $list;
                    $head = ['0' => '序号','1' => '业务单号','2' => '业务类型', '3' => '担保金额/元','4' => '出保函日' ,
                        '5' => '审查员', '6' => '审查主管','7' => '审查经理', '8' => '理财经理','9' => '所属部门','10' => '部门经理','11' => '快递单号'];
                    array_unshift($resInfo,$head);
                    //$fileName = iconv("UTF-8", "GB2312//IGNORE", '风控管理-出保函' . date('Y-m-d') . mt_rand(1111, 9999));
                    $fileName = ''.date('Y-m-dHis');//GuaranteeLetterOut
                    $spreadsheet->getActiveSheet()->fromArray($resInfo);
                    $spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true)->setName('Arial')->setSize(12);
                    $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $styleArray = [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ];
                    $worksheet->getStyle('A1:L1')->applyFromArray($styleArray);
                    // $spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
                    $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $Path = ROOT_PATH . 'public' . DS . 'uploads'.DS.'download'.DS.date('Ymd');
                    if(!file_exists($Path))
                    {
                        //检查是否有该文件夹，如果没有就创建，并给予最高权限
                        mkdir($Path, 0700);
                    }
                    $pathName = $Path.DS .$fileName.'.Xlsx';
                    $objWriter->save($pathName);
                    $retuurl = config('uploadFile.url').DS.'uploads'.DS.'download'.DS.date('Ymd').DS .$fileName.'.Xlsx';
                    //$retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS . iconv("GB2312", "UTF-8", $fileName) . '.xlsx';
                    return $this->buildSuccess(['url' => $retuurl]);
                }catch (\Exception $e){
                    return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '导出失败!'.$e->getMessage());
                }
            }


}




