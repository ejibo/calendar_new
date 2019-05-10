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
     *story:
     *负责人：
     */
    public function loadTemplate()
    {
        $template = model('Template');
        $templates = $template->getAllTemplates();
        return $templates;
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
}