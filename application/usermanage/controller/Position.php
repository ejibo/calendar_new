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
        $this->redirect('usermanage/position/index');
    }

public function restore($user_id)
    {
        //调用model中的方法，保证MVC分离
        $position = model('Position');
        $position -> restore($user_id);
        $this->redirect('usermanage/position/index');
}
 /**
 * 第05组 张楚悦
 * 功能：添加职位
 */
    public function addPosition()
    {
        $name = $_POST['name'];
        $model = model('Position');
        $result = $model->insertPosition($name);
        if($result==1){
            //设置成功后跳转页面的地址
            $this->success('新增成功', 'usermanage/position/index');
        } else {

            $this->error('新增失败');
        }
    }
    /**
     * 第05组 张君兰
     * 功能：修改职位
     */
    public function change($id,$name){
    //调用model里的方法，保证MVC分离
        $position = model('Position');
        return $position->change($id,$name);
    }
}


