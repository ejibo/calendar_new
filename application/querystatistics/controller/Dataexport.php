<?php

namespace app\querystatistics\controller;
use app\common\controller\Common;
use think\Controller;
use think\Request;
use think\Db;


class Dataexport extends Common
{
    public function index(){
        return $this->fetch('index');
    }
    public function export(){
        //1.从数据库中取出数据
        echo "ddd";
        $list = Db::name('schedule_info')->select();
        $list_user = Db::name('user_info')->select();
        $list_depart = Db::name('user_depart')->select();
        $list_position = Db::name('user_position')->select();
        $list_time = Db::name('schedule_time')->select();
        $list_place = Db::name('schedule_place')->select();
        $list_item = Db::name('schedule_item')->select();
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
            ->setCellValue('J1', '删除时间')
            ->setCellValue('K1', '用户姓名')
            ->setCellValue('L1', '用户学号')
            ->setCellValue('M1', '用户部门')
            ->setCellValue('N1', '用户职位')
            ->setCellValue('O1', '日程时间')
            ->setCellValue('P1', '日程地点')
            ->setCellValue('Q1', '日程事项');
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

            $user_name = $list_user->where(["id"=>$list[$i]['user_id'],"is_delete"=>0])->value('name');
            $user_workid = $list_user->where(["id"=>$list[$i]['user_id'],"is_delete"=>0])->value('work_id');
            $user_departid = $list_user->where(["id"=>$list[$i]['user_id'],"is_delete"=>0])->value('depart_id');
            $user_positionid = $list_user->where(["id"=>$list[$i]['user_id'],"is_delete"=>0])->value('position_id');
            $user_depart = $list_depart->where(["id"=>$user_departid,"is_delete"=>0])->value('name');
            $user_position = $list_position->where(["id"=>$user_positionid,"is_delete"=>0])->value('name');
            $user_time = $list_time->where(["id"=>$list[$i]['time_id'],"is_delete"=>0])->value('name');
            $user_place = $list_place->where(["id"=>$list[$i]['place_id'],"is_delete"=>0])->value('name');
            $user_item = $list_item->where(["id"=>$list[$i]['item_id'],"is_delete"=>0])->value('name');
            $objPHPExcel->getActiveSheet()->setCellValue('K'.($i+2),$user_name);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.($i+2),$user_workid);
            $objPHPExcel->getActiveSheet()->setCellValue('M'.($i+2),$user_depart);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.($i+2),$user_position);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.($i+2),$user_time);
            $objPHPExcel->getActiveSheet()->setCellValue('P'.($i+2),$user_place);
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.($i+2),$user_item);
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
