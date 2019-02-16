<?php
/**
 * 后台操作日志管理
 * @since   2018-02-06
 * @author
 */

namespace app\admin\controller;

use app\model\SystemUserLog;
use app\util\OrderComponents;
use app\util\ReturnCode;
use think\Db;

class Log extends Base
{

    /**
     * 获取操作日志列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author
     */
    public function index()
    {

        $limit = $this->request->get('size', config('apiBusiness.ADMIN_LIST_DEFAULT'));
        $start = $this->request->get('page', 1);
        $type = $this->request->get('type', '');
        $keywords = $this->request->get('keywords', '');

        $where = [];
        if ($type) {
            switch ($type) {
                case 1:
                    $where['url'] = ['like', "%{$keywords}%"];
                    break;
                case 2:
                    $where['name'] = ['like', "%{$keywords}%"];
                    break;
                case 3:
                    $where['uid'] = $keywords;
                    break;
            }
        }

        $listObj = (new SystemUserLog())->where($where)->order('addtime DESC')
            ->paginate($limit, false, ['page' => $start])->toArray();
        foreach ($listObj['data'] as $k => $v) {
            $listObj['data'][$k]['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
        }

        return $this->buildSuccess([
            'list' => $listObj['data'],
            'count' => $listObj['total'],
        ]);
    }

    /**
     * 删除日志
     * @return array
     * @author
     */
    public function del()
    {
        $id = $this->request->get('id');
        if (!$id) {
            return $this->buildFailed(ReturnCode::EMPTY_PARAMS, '缺少必要参数');
        }
        SystemUserLog::destroy($id);

        return $this->buildSuccess([]);

    }

    /**
     * @api {post} admin/Log/orderLog 查询订单日志[admin/Log/orderLog]
     * @apiVersion 1.0.0
     * @apiName orderLog
     * @apiGroup Log
     * @apiSampleRequest admin/Log/orderLog
     * @apiParam {int}  type   订单主状态(非必填)
     * @apiParam {string}  orderSn   订单编号
     * @apiParam {string}  tag   (非必填)
     * @apiParam {int}  tableId   (非必填)
     * @apiSuccess create_time 时间
     * @apiSuccess operate_node    操作节点
     * @apiSuccess operate_det    操作详情
     * @apiSuccess operate    操作
     * @apiSuccess name    操作人
     */
    public function orderLog()
    {
        $type = input('type', 0, 'int');
        $orderSn = input('orderSn', '');
        $tag = input('tag', '');
        $table_id = input('tableId', 0, 'int');
        if (empty($orderSn)) {
            return $this->buildFailed(ReturnCode::DB_READ_ERROR, '缺少参数!');
        }

        $result = OrderComponents::showLog($orderSn, $type, $tag, $table_id);
        if ($result !== false) {
            foreach ($result as $key => $val) {
                $val['operate_reason'] && $result[$key]['operate_det'] = $val['operate_det'] . '(原因：' . $val['operate_reason'] . ')';
            }
            return $this->buildSuccess($result);
        }

        return $this->buildFailed(ReturnCode::DB_READ_ERROR, '日志读取失败!');
    }
}