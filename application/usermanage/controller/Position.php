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
    /*
    *story:修改职务
     *负责人：张君兰
    */
    public function change($id,$name)
    {
        $position = model('Position');
        $position ->change($id,$name);

    }
}