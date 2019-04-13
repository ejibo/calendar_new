<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:27
 */

namespace app\manageconfig\controller;


use app\common\controller\Common;

class Workday extends Common
{
    public function index(){
        return $this->fetch();
    }
}