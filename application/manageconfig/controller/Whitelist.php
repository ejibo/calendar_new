<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:25
 */

namespace app\manageconfig\controller;

use think\Db;
use app\common\controller\Common;
use app\logmanage\model\Log as LogModel;


use think\Loader;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Writer_Excel5;
use PHPExcel_Writer_Excel2007;


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

    function excelInput(){
        if(request()->isPost()) {
            Loader::import('PHPExcel.PHPExcel');
            Loader::import('PHPExcel.PHPExcel.PHPExcel_IOFactory');
            Loader::import('PHPExcel.PHPExcel.PHPExcel_Cell');
            //实例化PHPExcel
            $objPHPExcel = new \PHPExcel();
            $file = request()->file('excel');
            if ($file) {
                $file_types = explode(".", $_FILES ['excel'] ['name']); // ["name"] => string(25) "excel文件名.xls"
                $file_type = $file_types [count($file_types) - 1];//xls后缀
                $file_name = $file_types [count($file_types) - 2];//xls去后缀的文件名
                /*判别是不是.xls文件，判别是不是excel文件*/
                if (strtolower($file_type) != "xls" && strtolower($file_type) != "xlsx") {
                    echo '不是Excel文件，重新上传';
                    die;
                }

                $info = $file->move(ROOT_PATH . 'public' . DS . 'excel');//上传位置
                $path = ROOT_PATH . 'public' . DS . 'excel' . DS;
                $file_path = $path . $info->getSaveName();//上传后的EXCEL路径
                //echo $file_path;//文件路径

                //获取上传的excel表格的数据，形成数组
                $re = $this->actionRead($file_path, 'utf-8');
                array_splice($re, 1, 0);
                unset($re[0]);

                /*将数组的键改为自定义名称*/
                $keys = array('name', 'work_id', 'type_id', 'depart_id', 'position_id');
                foreach ($re as $i => $vals) {
                    $re[$i] = array_combine($keys, $vals);

                }
                echo '上传成功';
                //    dump($re); 查看数组

                dump($re[1]);

                //遍历数组写入数据库
                for ($i = 1; $i <= count($re); $i++) {
                    $data = $re[$i];
                    dump($data);
                    Db::table('user_info')->insert($re[$i]);

                }
            }
        }

    }
}
