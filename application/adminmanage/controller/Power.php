<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:42
 */

namespace app\adminmanage\controller;


use app\common\controller\Common;

class Power extends Common
{
    public function index(){
        return $this->fetch();
    }

}