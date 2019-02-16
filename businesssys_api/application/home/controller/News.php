<?php
/**
 * Created by PhpStorm.
 * User: 赵光帅
 * Date: 2018/5/31
 * Time: 15:20
 */
namespace app\home\controller;

use app\admin\controller\Base;
use app\util\ReturnCode;
use app\home\model\News as modelNews;
use app\home\model\BannerItem;

class News extends Base {
    /**
     * @api {post} home/News/newList 新闻列表[home/News/newList]
     * @apiVersion 1.0.0
     * @apiName newList
     * @apiGroup News
     * @apiSampleRequest home/News/newList
     *
     * @apiParam {int}  type   新闻类型 1公司新闻 2行业新闻
     * @apiParam {string}  search_text   新闻标题关键字
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *  "data": [
            {
                "id": 2,                       新闻表主键id
                "type": 1,                     新闻类型 1公司新闻  2行业新闻
                "title": "中美贸易战",         新闻标题
                "summary": "经济",             摘要
                "img1": "images/xh829.png",    封面图面1
                "img2": "images/xh829.png",    封面图片2
                "img3": null,                  封面图片3
                "source": "新浪新闻",          新闻来源
                "author": "李四",              作者
                "content": "阿萨德噶哒很高的合同人头还有人同行一人头",   新闻内容
                "newsdate": "2017-10-10",                    新闻时间
                "create_time": "1971-09-11 16:22:05",        创建时间
                "name": "许小球"                             创建人(作者)
                "deptname": "权证部"                          创建人所属部门(来源)
            },
            {
                "id": 3,
                "type": 1,
                "title": "中国航母下水",
                "summary": "军事",
                "img1": "images/xh829.png",
                "img2": "images/xh829.png",
                "img3": null,
                "source": "网易新闻",
                "author": "王五",
                "content": "阿嘎多嘎的说法噶是的发送到",
                "newsdate": "2016-10-06",
                "create_time": "1971-06-13 15:20:45",
                "name": "许小球"
            }
        ]
     */

    public function newList(){
        $type = input('type')?:0;
        $searchText = trim(input('search_text'));
        $page = input('page') ? input('page') : 1;
        $pageSize = input('limit') ? input('limit') : 10;
        $map = [];
        $map['a.status'] = 1;
        $type && $map['a.type'] = $type;
        $searchText && $map['a.title']=['like', "%{$searchText}%"];
        //return json($map);
        try{
            $resInfo = modelNews::newList($map,$page,$pageSize);
            return $this->buildSuccess($resInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }

    }

    /**
     * @api {post} home/News/addNew 添加或编辑新闻[home/News/addNew ]
     * @apiVersion 1.0.0
     * @apiName addNew
     * @apiGroup News
     * @apiSampleRequest home/News/addNew
     *
     * @apiParam {int}  id 新闻表主键id(编辑新闻时需传该参数)
     * @apiParam {int}  type 新闻类型 1公司新闻 2行业新闻
     * @apiParam {arr}  img1  封面图片地址 images/xh829.png
     * @apiParam {string}  title   新闻标题
     * @apiParam {string}  summary   新闻摘要
     * @apiParam {string}  source   新闻来源
     * @apiParam {string}  author   新闻作者
     * @apiParam {string}  content   新闻内容
     */

    public function addNew()
    {
        $id = input('id');
        $data['type'] = input('type');
        $data['img1'] = input('img1');
        $data['title'] = input('title');
        $data['summary'] = input('summary');
        $data['source'] = input('source');
        $data['author'] = input('author');
        $data['content'] = input('content');
        $valiDate = validate('NewsValidation');
        if(!$valiDate->check($data)){
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, $valiDate->getError());
        }

        try{
            if(!empty($id) && isset($id)){
                $data['update_time'] = time();
                $modelNews = new modelNews;
                $modelNews->save($data,['id' => $id]);
                return $this->buildSuccess('修改成功');
            }else{
                $data['newsdate'] = date('Y-m-d');
                $data['create_uid'] = $this->userInfo['id'];
                $data['create_time'] = time();
                $modelNews = new modelNews($data);
                $modelNews->add($data);
                return $this->buildSuccess('添加成功');
            }

        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '操作失败!');
        }
    }

    /**
     * @api {post} home/News/newDetail 新闻详情[home/News/newDetail]
     * @apiVersion 1.0.0
     * @apiName newDetail
     * @apiGroup News
     * @apiSampleRequest home/News/newDetail
     *
     * @apiParam {int}  id   新闻表主键id
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *  "data":
            {
            "id": 2,                       新闻表主键id
            "type": 1,                     新闻类型 1公司新闻  2行业新闻
            "title": "中美贸易战",         新闻标题
            "summary": "经济",             摘要
            "img1": "images/xh829.png",    封面图面
            "source": "新浪新闻",          新闻来源
            "author": "李四",              作者
            "content": "阿萨德噶哒很高的合同人头还有人同行一人头",   新闻内容
            "newsdate": "2017-10-10",                    新闻时间
            "create_time": "1971-09-11 16:22:05",        创建时间
            "name": "许小球" ,                            创建人(作者)
            "deptname": "权证部"                          创建人所属部门(来源)
            }
     */

    public function newDetail(){
        $id = input('id');
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '新闻表主键id不能为空!');
        try{
            return $this->buildSuccess(modelNews::newDetail($id));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }

    }

    /**
     * @api {post} home/News/delNew 删除新闻[home/News/delNew]
     * @apiVersion 1.0.0
     * @apiName delNew
     * @apiGroup News
     * @apiSampleRequest home/News/delNew
     *
     * @apiParam {int}  id   新闻表主键id
     */
    public function delNew(){
        $id = input('id');
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '新闻表主键id不能为空!');
        try{
            $data['status'] = -1;
            $data['delete_time'] = time();
            $modelNews = new modelNews;
            $modelNews->save($data,['id' => $id]);
            return $this->buildSuccess('删除成功');
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '删除失败!');
        }

    }

    /**
     * @api {post} home/News/processList 待处理列表[home/News/processList]
     * @apiVersion 1.0.0
     * @apiName processList
     * @apiGroup News
     * @apiSampleRequest home/News/processList
     *
     * @apiParam {int} page    页码
     * @apiParam {int} limit    条数
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *   "data": [
            {
            "proc_id": 633,                 处理明细表主键id
            "type": "JYDB",                 业务类型简称
            "stage": "1002",                订单状态
            "estate_name": "信心341011",    房产名称
            "id": 147,                      赎楼派单表主键id
            "order_sn": "JYDB2018050382",   订单编号
            "create_time": 1527815794,      时间
            "user_name": "管理员",          审批人名称
            "dept_name": "财务部"           审批人所在的部门
            "type_text": "交易担保"          业务类型
            "is_wealth_managers": "1"            是否是赎楼经理 1 是赎楼经理  2不是赎楼经理
            },
            {
            "type": "JYDB",
            "estate_name": "信心341011",
            "order_sn": "JYDB2018050382",
            "user_name": "管理员",
            "dept_name": "财务部"
            }
        ]
     */

    public function processList(){
        try{
            $resInfo = modelNews::processList($this->userInfo['id'],$this->userInfo['group'],$this->userInfo['deptid']);
            //$resInfo = modelNews::processList($this->userInfo['id'],[],17);
            return $this->buildSuccess($resInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!'.$e->getMessage());
        }

    }

    /**
     * @api {post} home/News/addBanner 添加和编辑广告位[home/News/addBanner]
     * @apiVersion 1.0.0
     * @apiName addBanner
     * @apiGroup News
     * @apiSampleRequest home/News/addBanner
     *
     * @apiParam {int}  id   banner表主键id(编辑广告位时需传该参数)
     * @apiParam {int}  status  状态 0：停用；1：正常（默认）
     * @apiParam {string}  img_url  封面图片url
     * @apiParam {string}  key_word  封面图片跳转url
     */
    public function addBanner(){
        $id = input('id');
        $data['status'] = input('status')?:0;
        $data['img_url'] = input('img_url');
        $data['key_word'] = input('key_word');
        if(empty($data['img_url'])) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '图片url不能为空!');
        try{
            if(!empty($id) && isset($id)){
                //判断该张图片原先是否已经启用
                if($data['status'] == 1){  //更新后的状态为启用，就需要查询原先的状态
                    //编辑广告位启用之前，判断六张正常是否已经添加完
                    $map['banner_id'] = 1;
                    $map['status'] = 1;
                    $map['id'] = ['<>',$id];
                    $num = BannerItem::where($map)->count();
                    if($num >= 6) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '正常状态的轮播图只能有六张!');
                    $oldStatus = BannerItem::where(['id' => $id])->value('status');
                    if($oldStatus == 0){ //原先的状态如果为0,则需要更改排序
                        $sortNum = BannerItem::where(['banner_id' => 1])->order('sort desc')->value('sort');
                        $data['sort'] = $sortNum + 1;
                    }
                }
                $data['update_time'] = time();
                $modelNews = new BannerItem;
                $modelNews->save($data,['id' => $id]);
                return $this->buildSuccess('修改成功');
            }else{
                if($data['status'] == 1){
                    //启用状态的广告位轮播图只能添加六张
                    $num = BannerItem::where(['banner_id' => 1,'status' => 1])->count();
                    if($num >= 6 && $data['status'] == 1) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '正常状态的轮播图只能添加六张!');
                }else{
                    $num = BannerItem::where(['banner_id' => 1])->order('sort desc')->value('sort');
                }
                $data['sort'] = $num + 1;
                $data['banner_id'] = 1;
                $data['create_uid'] = $this->userInfo['id'];
                $data['create_time'] = time();
                $modelNews = new BannerItem($data);
                $modelNews->add($data);
                return $this->buildSuccess('添加成功');
            }
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '操作失败!');
        }

    }

    /**
     * @api {post} home/News/bannerList 广告位轮播图列表[home/News/bannerList]
     * @apiVersion 1.0.0
     * @apiName bannerList
     * @apiGroup News
     * @apiSampleRequest home/News/bannerList
     *
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *   "data": [
            {
            "id": 35,                    banner信息表主键id
            "img_url": "www.img.png",      图片url
            "key_word": "www.baidu.com",   图片链接url
            "status": 1,                   状态 0：禁用；1：正常
            "create_time": "2018-06-01 17:52:47",    创建时间
            "name": "马特"                           创建人
            },
            {
            "id": 36,
            "img_url": "www.imsd123dsg.png",
            "key_word": "www.bai333sdsddu.com",
            "status": 1,
            "create_time": "2018-06-01 17:53:09",
            "name": "马特"
            }
        ]
     */

    public function bannerList(){
        try{
            $resInfo = BannerItem::bannerList();
            return $this->buildSuccess($resInfo);
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }
    }

    /**
     * @api {post} home/News/bannerDetail 轮播图详情[home/News/bannerDetail]
     * @apiVersion 1.0.0
     * @apiName bannerDetail
     * @apiGroup News
     * @apiSampleRequest home/News/bannerDetail
     *
     * @apiParam {int}  id   banner信息表主键id
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *  "data": {
        "id": 38,                        banner信息表主键id
        "img_url": "www.i444g.png",      图片地址
        "status": 1,                     状态 0：禁用；1：启用
        "key_word": "www.bad44du.com"    URL链接
        }
     */

    public function bannerDetail(){
        $id = input('id');
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'banner信息表主键id不能为空!');
        try{
            return $this->buildSuccess(BannerItem::getOne(['id' => $id],'id,img_url,status,key_word'));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }

    }

    /**
     * @api {post} home/News/bannerOrderBy 拖动调整轮播图排序[home/News/bannerOrderBy]
     * @apiVersion 1.0.0
     * @apiName bannerOrderBy
     * @apiGroup News
     * @apiSampleRequest home/News/bannerOrderBy
     *
     * @apiParam {arr}  id_arr   拖动后将,banner信息表主键id,按照由上到下重新排序后,组装成的数组
     */

    public function bannerOrderBy(){
        $idArr = input('id_arr/a');
        if(empty($idArr)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '参数不能为空!');
        try{
            //查询出所有的的排序
            $sortArr = BannerItem::where(['banner_id' => 1,'status'=> 1,'delete_time' => NULL])->order('sort asc')->column('sort');
            $list = [];
            foreach ($idArr as $k=>$v){
                $list[]= ['id' => $v,'sort' => $sortArr[$k]];
            }
            $BannerItem = new BannerItem;
            $res = $BannerItem->saveAll($list);
            if($res){
                return $this->buildSuccess('排序成功');
            }else{
                return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '排序失败!');
            }

        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '排序失败!');
        }

    }

    /**
     * @api {post} home/News/indexBannerInfo 首页banner图展示信息[home/News/indexBannerInfo]
     * @apiVersion 1.0.0
     * @apiName indexBannerInfo
     * @apiGroup News
     * @apiSampleRequest home/News/indexBannerInfo
     *
     * @apiSuccessExample {json} 返回数据示例:
     * HTTP/1.1 200 OK
     *   "data": [
            {
            "img_url": "http://119.23.24.187\\businesssys_api\\public\\uploads\\20180607\\2b8197d496f4625d79ed1c9c3562c0c4.jpg",
            "key_word": "www.bai333sdsddu.com"
            },
            {
            "img_url": "http://119.23.24.187/businesssys_web/dist/b27deb860137e1d916dfecf71dc3ecd5.png",
            "key_word": "www.baddu.com"
            }
         }
     *
     * @apiSuccess {string} img_url    图片地址
     * @apiSuccess {string} key_word    图片链接地址
     */

    public function indexBannerInfo(){
        try{
            $map['status'] = 1;
            $map['delete_time'] = NULL;
            return $this->buildSuccess(BannerItem::getAll($map,'img_url,key_word','sort asc'));
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '查询失败!');
        }

    }

    /**
     * @api {post} home/News/delIndexBanner 删除首页banner图[home/News/delIndexBanner]
     * @apiVersion 1.0.0
     * @apiName delIndexBanner
     * @apiGroup News
     * @apiSampleRequest home/News/delIndexBanner
     *
     * @apiParam {int}  id   banner信息表主键id
     */
    public function delIndexBanner(){
        $id = input('id');
        if(empty($id)) return $this->buildFailed(ReturnCode::EMPTY_PARAMS, 'banner信息表主键id不能为空!');
        try{

            $data['delete_time'] = time();
            $modelNews = new BannerItem;
            $res = $modelNews->save($data,['id' => $id]);
            if(empty($res))return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '删除失败!');
            return $this->buildSuccess('删除成功');
        }catch (\Exception $e){
            return $this->buildFailed(ReturnCode::RECORD_NOT_FOUND, '删除失败!');
        }

    }





}