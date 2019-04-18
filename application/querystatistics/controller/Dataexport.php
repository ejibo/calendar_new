<?php
/**
 * Created by PhpStorm.
 * User: Ejibo
 * Date: 2019/4/18
 * Time: 14:23
 */
namespace app\querystatistics\controller;
use app\common\controller\Common;
use think\Controller;
use think\Request;
use think\Db;
use PHPExcel;

class Dataexport extends Common
{
    public function index(){
        return $this->fetch('index');
    }
    public function export(){
        //1.从数据库中取出数据
        echo "ddd";
        $list = Db::name('schedule_info')->select();
        echo "aaa";
        //2.加载PHPExcle类库
        vendor('PHPExcel.PHPExcel');
        //3.实例化PHPExcel类
        $objPHPExcel = new \PHPExcel();
        //4.激活当前的sheet表
        $objPHPExcel->setActiveSheetIndex(0);
        //5.设置表格头（即excel表格的第一行）
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', '用户ID')
            ->setCellValue('C1', '时间ID')
            ->setCellValue('D1', '地点ID')
            ->setCellValue('E1', '事项ID')
            ->setCellValue('F1', '删除标志')
            ->setCellValue('G1', '事项描述')
            ->setCellValue('H1', '创建时间')
            ->setCellValue('I1', '更新时间')
            ->setCellValue('J1', '删除时间');
        //设置F列水平居中
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置单元格宽度
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(30);
        //6.循环刚取出来的数组，将数据逐一添加到excel表格。
        echo "aaa";
        for($i=0;$i<count($list);$i++){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2),$list[$i]['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($i+2),$list[$i]['user_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.($i+2),$list[$i]['time_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.($i+2),$list[$i]['place_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.($i+2),$list[$i]['item_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.($i+2),$list[$i]['note']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.($i+2),$list[$i]['is_delete']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.($i+2),$list[$i]['create_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.($i+2),$list[$i]['update_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.($i+2),$list[$i]['delete_time']);
        }
        //7.设置保存的Excel表格名称
        $filename = '日程信息'.date('ymd',time()).'.xls';
        //8.设置当前激活的sheet表格名称；
        $objPHPExcel->getActiveSheet()->setTitle('日程信息');
        //9.设置浏览器窗口下载表格
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        ob_end_clean();
        ob_start();
        //生成excel文件
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        //下载文件在浏览器窗口
        $objWriter->save('php://output');
        exit;
    }
}
