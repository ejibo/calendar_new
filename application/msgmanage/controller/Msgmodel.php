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
        $des = $_POST['des'];
        $con = $_POST['con'];
        $model = model('Template');
        $isHasSame = $model->getItemByTitle($des);
        if ($isHasSame == null) {
            $res = $model->insertTemplate($des, $con);
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

}