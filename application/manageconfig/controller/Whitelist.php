<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:25
 */

namespace app\manageconfig\controller;


use app\common\controller\Common;

class Whitelist extends Common
{
    public function index(){
        return $this->fetch();
    }
}