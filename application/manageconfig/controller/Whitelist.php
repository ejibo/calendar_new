<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:25
 */

namespace app\manageconfig\controller;

use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\Db;
use app\common\controller\Common;
use app\logmanage\model\Log as LogModel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use think\Request;


class Whitelist extends Common
{
    public function index(){
        $whitelist = model('Whitelist');
        $info = $whitelist->getinfo();
        foreach($info as $key=>$value){
            if ($info[$key]['type_id'] == 0){
                $info[$key]['type'] = '普通用户';
            }elseif ($info[$key]['type_id'] == 1){
                $info[$key]['type'] = '院领导';
            }elseif ($info[$key]['type_id'] == 2){
                $info[$key]['type'] = '部门领导';
            }elseif ($info[$key]['type_id'] == 3){
                $info[$key]['type'] = '系领导';
            }
        }
        $this->assign('info',$info);

        //添加人员模态框传值
        $depart = $whitelist->getdepart();
        $this->assign('depart',$depart);
        $position = $whitelist->getposition();
        $this->assign('position',$position);
        return $this->fetch();
    }

    public function addwhitelist(){
        $data = input('post.');
        if (empty($data['name']) || empty($data['work_id'])){
            $this->error('输入不可为空');
        }

        $exist_work_id = Db::table('user_info')->where('work_id',$data['work_id'])->find();
        if ($exist_work_id){
            $this->error('该工号已存在');
        }
        $is_add = Db::table('user_info')->insert($data);

        if ($is_add){
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }
    }

    public function delwhitelist(){
        $whitelist = model('Whitelist');
        $data = input('post.');
        $is_delete = $whitelist->delwhitelist($data);
        if($is_delete){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }

    public function editwhitelist(){
        $whitelist = model('Whitelist');
        $data = input('post.');
        $is_add = $whitelist->editwhitelist($data);
        if ($is_add){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败！');
        }
    }

    /*
    创建： 翁嘉进
    功能： 实现清空白名单操作
    实现： 1.判断管理员是否登录 
           2.若无登录，则跳转至登录界面（通过继承Common类实现） 
           3.清空白名单 
           4.记录操作日志 
           5.返回结果
    */

    public function clearwhitelist(){
        $whitelist = model('Whitelist');                            // 调用白名单数据模型
        $is_clear = $whitelist->clearwhitelist();                   // 通过模型进行清空操作
        $logmodel = new LogModel();                                 // 调用操作日志数据模型
        $uid = ADMIN_ID;                                            // 管理员ID
        $type = 4;                                                  // 操作类型：删除（清空）
        $table = 'user_info';                                       // 操作数据表
        $field = ['All whitelist items, total:'.$is_clear];                           // 删除的主键列表, 不是学号
        $logmodel->recordLogApi ($uid, $type, $table, $field);      // 需要判断调用是否成功

        if ($is_clear >= 0){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败！');
        }
    }

    public function excelInput(){
        $reader = new Xlsx();
        try {
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            echo  $spreadsheet;
        } catch (Exception $e) {
            die($e->getMessage());
        }
        $sheet = $spreadsheet->getActiveSheet();
        $sqlData = array();

        $i = 0;

        $excelData = model("Whitelist");

        foreach ($sheet->getRowIterator(2) as $row) {
            $tmp = array();
            foreach ($row->getCellIterator() as $cell) {
                $tmp[] = $cell->getFormattedValue();
            }
            // 未被添加的用户信息才会被记录到数组里，最后批量添加
            if ($excelData->findUserByWorkId($tmp[1]) == null) {
                $tmp = ['name' => $tmp[0],
                    'work_id' => $tmp[1],
                    'type_id' => $tmp[2],
                    'depart_id' => $tmp[3],
                    'position_id' => $tmp[4]];
                $sqlData[$i++] = $tmp;
            }
        }

        $addFlag = $excelData->insertAllUser($sqlData);
        //echo  $sqlData[0];
//        if ($addFlag) {
//            $this->success('添加成功,自动跳转');
//        } else {
//            $this->error('添加失败');
//        }
    }
}
