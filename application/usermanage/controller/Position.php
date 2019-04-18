<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:38
 */

namespace app\usermanage\controller;


use app\common\controller\Common;

class Position extends Common
{
  
 /**
  * 第05组 高裕欣
  * 功能：显示列表
  */
	public function index() {
        $position = model('Position');
        $list = $position->getUserPositionList();
        //dump($list);
        //exit;
        $this->assign("position_list", $list);
        return $this->fetch();

    }

 /**
  * 第05组 高裕欣
  * 功能：作废职位
  */
 public function invalid($user_id)
    {
        //调用model中的方法，保证MVC分离
        $position = model('Position');
        $position -> invalid($user_id);
        $this->redirect('/usermanage/position/index');
    }

public function restore($user_id)
    {
        //调用model中的方法，保证MVC分离
        $position = model('Position');
        $position -> restore($user_id);
        $this->redirect('/usermanage/position/index');
}
 /**
 * 第05组 张楚悦
 * 功能：添加职位
 */
public function add($name)
{
    $position = model('Position');
    $position ->add($name);
}
}


