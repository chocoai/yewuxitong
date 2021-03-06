<?php

/**
 * 工程基类
 * @since   2017/02/28 创建
 * @author
 */

namespace app\admin\controller;

use app\util\ReturnCode;
use think\Controller;
use think\Db;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Base extends Controller {

    private $debug = [];
    protected $userInfo;
    protected $auth_group;

    /**
     * [业务分类]
     * @var [string]
     */
    protected $category;

    public function _initialize() {
        $ApiAuth = $this->request->header('ApiAuth');
        $this->category = $this->request->header('Category');
        $this->auth_group = model('app\model\SystemAuthGroup')->getAuthGroup();
        if ($ApiAuth) {
            $userInfo = cache('Login:' . $ApiAuth);
            $this->userInfo = json_decode($userInfo, true);
        }
        $this->userInfo = Db::name('system_user')->where(['id'=>1])->find();
    }

    public function buildSuccess($data = '', $msg = '操作成功', $code = ReturnCode::SUCCESS) {
        $return = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        if ($this->debug) {
            $return['debug'] = $this->debug;
        }
        return $return;
    }

    public function buildFailed($code, $msg, $data = []) {
        $return = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        if ($this->debug) {
            $return['debug'] = $this->debug;
        }

        return $return;
    }

    protected function debug($data) {
        if ($data) {
            $this->debug[] = $data;
        }
    }

    /**
     * 生成楼盘id
     */
    protected function _building_id() {
        return date('Ymd', time()) . time() . mt_rand(1000, 9999);
    }

    /* 生产系统序号 */

    protected function _systemSequence($type) {
        $modelSequen = model('app\model\SystemSequence');
        $sequen = $modelSequen->lock(true)->where(['type' => $type])->order('id asc')->field('id,sequence')->find();
        $curDate = date('Ym');
        if ($sequen) {
            $dateNum = substr($sequen['sequence'], 0, 6);
            if ($dateNum === $curDate) {/* 判断日期 */
                $intNum = intval(substr($sequen['sequence'], 6)) + 1;
                $length = strlen($intNum);
                if ($length < 4) {
                    for ($z = 0; $z < 4 - $length; $z++) {
                        $dateNum .= 0;
                    }
                    $sequenceCode = $dateNum . ($intNum);
                } else {
                    $sequenceCode = substr($sequen['sequence'], 0, 6) . ($intNum);
                }
            } else {
                $sequenceCode = $curDate . '0001'; //更新月编号
            }
            if ($modelSequen->where(['id' => $sequen['id'], 'sequence' => $sequen['sequence']])->update(['sequence' => $sequenceCode]) === 1)
                return $type . $sequenceCode;
            return false;
        }else {
            $sequenceCode = $curDate . '0001'; //新建新类型系统编号
            if ($modelSequen->save(['type' => $type, 'sequence' => $sequenceCode]) > 0)
                return $type . $sequenceCode;
            return false;
        }
    }

    /**
     * Excel导出
     * @param {array}  $data   数据
     * @param {array}  $head   excel表头
     * @param {string} $name   文件名
     */
    protected function exportExcel($data, $head, $name) {
        $spreadsheet = new Spreadsheet();
        array_unshift($data, $head);
        $fileName = iconv("UTF-8", "GB2312//IGNORE", $name . date('Y-m-dHis'));
        //ob_end_clean();
        //header('Content-Type: application/vnd.ms-excel');
        //header("Content-Disposition: attachment;filename=\"$fileName\"");
        //header('Cache-Control: max-age=0');
        $spreadsheet->getActiveSheet()->fromArray($data);
        $spreadsheet->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true)->setName('Arial')->setSize(12);
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
        $worksheet = $spreadsheet->getActiveSheet();
        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $worksheet->getStyle('A1:O1')->applyFromArray($styleArray);
        $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $Path = ROOT_PATH . 'public' . DS . 'uploads' . DS . 'download' . DS . date('Ymd');
        if (!file_exists($Path)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($Path, 0700);
        }
        $pathName = $Path . DS . $fileName . '.Xlsx';
        $objWriter->save($pathName);
        $retuurl = config('uploadFile.url') . DS . 'uploads' . DS . 'download' . DS . date('Ymd') . DS . iconv("GB2312", "UTF-8", $fileName) . '.Xlsx';
        return $retuurl;
    }

}
