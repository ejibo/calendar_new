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
    实现： 1.判断管理员是否登录 2.若无登录，则跳转至登录界面 3.清空白名单 4.记录操作日志 
    */
    public function clearwhitelist(){
        $whitelist = model('Whitelist');               //调用白名单数据模型
        $is_clear = $whitelist->clearwhitelist();      //通过模型进行清空操作
        $logmodel = new LogModel();                    //调用操作日志数据模型
        /*
        $uid = 110;                                    //管理员ID
        $type = 4;                                     //操作类型：删除（清空）
        $table = 'user_info';
        $field = ['All whitelist items'];              // 删除的主键列表, 不是学号
        $model->recordLogApi ($uid, $type, $table, $field); //需要判断调用是否成功
        */
        if ($is_clear){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败！');
        }
    }


}
