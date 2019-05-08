<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:41
 */

namespace app\adminmanage\controller;


use app\common\controller\Common;

class Role extends Common
{
    public function index(){
        return $this->fetch();
    }
}