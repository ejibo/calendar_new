<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/13
 * Time: 19:36
 */

namespace app\common\controller;


use think\Controller;

class Common extends Controller
{
    //页面初始化
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        //实例化Common模型类
        $model_common = model('Common');
        //得到所有menu的信息
        $menu_info = $model_common->get_menu_info();
        dump($menu_info);
        $this->assign('menu_info',$menu_info);

        //获取当前页面的模块、控制器、方法
        $module = request()->module();
        $controller = request()->controller();
        $action = request()->action();
        //得到当前页面对应的menu_id
        $menu_id = $model_common->get_menu_id($module,$controller,$action);
        dump($menu_id);
        $this->assign('menu_id_now',$menu_id);
    }

}