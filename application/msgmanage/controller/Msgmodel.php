<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:50
 */

namespace app\msgmanage\controller;


use app\common\controller\Common;

class Msgmodel extends Common
{
    public function index(){
        $model = model('Template');
        $templateItems = $model->getAllTemplates();
        $this->assign('templateItems',$templateItems);
        return $this->fetch();
    }

    /*
    *story:查询消息模板
    *负责人：吴珏
    */

    public function loadTemplate()
    {
        $template = model('Template');
        $templates = $template->getAllTemplates();
        return $templates;
    }
    
    public function searchTemplate()
    {
        $status = $_POST['status'];
        $search = $_POST['search'];
        $model = model('Template');
        $isHasTitle = $model->getItemByTitle($search);
        $isHasContent = $model->getItemByContent($search);
        if ($isHasTitle == null) {
            $this->error("搜索项不存在，请重新尝试");
        }
        else{
            var_dump($isHasTitle);
            // $this->success();
            // echo $templateItems['title'];
            // $templateItems = $model->getAllTemplates();
            $this->assign('templateItems',$isHasTitle);
            // var_dump($templateItems);
            // $this->assign('templateItems',$isHasTitle);
            // echo $isHasTitle;
            // console.log($templateItems);
            // var_dump($templateItems);
            return $this->fetch('index');
        }
    }

    /*
     *story:添加消息模板
     *负责人：佟起
     */
    public function  addTemplate()
    {
        $tit = $_POST['tit'];
        $con = $_POST['con'];
        /* var_dump($des);
        var_dump($con); */
        $model = model('Template');
        $isHasSame = $model->getItemByTitle($tit);
        if ($isHasSame == null) {
            $res = $model->insertTemplate($tit, $con);
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
    /*
     *story:删除消息模板
     *负责人：张骁雄
     */
    public function  deleteTemplate(){
        $id = $_POST['id'];
        $model = model('Template');
        $res = $model->clearTemplate($id);
        if($res == 1)
            $this->success("删除成功");
        else
            $this->success("删除失败");
    }
    /*
    *story:修改消息模板
    *负责人：张骁雄
    */
    public function modifyTemplate(){
        $id = $_POST['id'];
        $des = $_POST['des'];
        $model = model('Template');
        $res = $model->updateTemplate($id,$des);
        if($res==1)
            $this->success("修改成功");
        else
            $this->success("修改失败");
    }

    /*
    *story:根据消息模板向用户发送提醒消息（刘玄）
    细分story：发送消息提醒
    *负责人：刘玄
    */
    public function remind($user_id)
    {
        $position = model('Template');
        $position->remind($user_id);
        $this->redirect('msgmanage/msgmodel/index');
    }
     /*
    *story:根据消息模板向用户发送提醒消息（刘玄）
    细分story：取消发送消息提醒
    *负责人：刘玄
    */
    public function cancelremind($user_id)
    {
        
        $position = model('Template');
        $position->cancelremind($user_id);
        $this->redirect('msgmanage/msgmodel/index');
    }

}