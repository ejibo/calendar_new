<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:24
 */

namespace app\manageconfig\controller;

use app\common\controller\Common;

class Item extends Common
{
    public function index(){
        $model = model('ScheduleItem');
        $scheduleItems = $model->getAllItems();
        $this->assign('scheduleItems',$scheduleItems);
        return $this->fetch();
    }
    //添加事项的方法
    public function  addScheduleItem()
    {
        $des = $_POST['des'];
        $model = model('ScheduleItem');
        $isHasSame = $model->getItemByName($des);
        if ($isHasSame == null) {
            $res = $model->insertScheduleItem($des);
            if($res ==1){
                $this->success("新增成功");
            }
            else{
                $this->error("添加失败，请重新尝试");
            }
        }
        else{
            $this->error("名称重复");
        }
    }
    //删除事项
    public function deleteScheduleItem(){
        $id = $_POST['id'];
        $model = model('ScheduleItem');
        $res = $model->deleteScheduleItem($id);
        if($res == 1){
            $this->success("删除成功");
        }
        else{
            $this->error(  "删除失败，请重新操作!");
        }
    }
    //编辑事项方法
    public function editScheduleItem(){
        $id = $_POST['id'];
        $des = $_POST['des'];
        $model = model('ScheduleItem');
        $isSame = $model->getItemByName($des);
        if($isSame ==null){
            $res = $model->updateScheduleItem($id,$des);
            if($res ==1){
                $this->success("编辑成功");
            }
            else{
                $this->error(  "编辑失败，请重新操作!");
            }
        }
        else{
            $this->error(  "修改事项名称与已有重复，请重新修改!");
        }
    }
}