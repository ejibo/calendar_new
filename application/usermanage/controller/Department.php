<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:36
 */

namespace app\usermanage\controller;


use app\common\controller\Common;

class Department extends Common
{
    public function index(){
        return $this->fetch();
    }

}