<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/17
 * Time: 14:31
 */

namespace app\msgmanage\controller;


use app\common\controller\Common;

class Policy extends Common
{
    public function index(){
        return $this->fetch();
    }

}