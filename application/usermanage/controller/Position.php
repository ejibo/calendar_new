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
    public function index(){
        return $this->fetch();
    }
	
 /**
  * 第05组 高裕欣
  * 功能：显示列表
  */
	public function user_position_list() {
        $position = model('Position');
        $departments = $position->getUserPositionList();
        $this->assign("list", $list);
        return $this->fetch();

    }

    /**
     *第05组 张君兰
     * 功能：修改职位
    */
    public function change($id,$name)
    {
        $position = model('Position');
        $position ->change($id,$name);

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
	
}