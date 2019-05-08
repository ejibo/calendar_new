<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/13
 * Time: 22:51
 */

namespace app\querystatistics\controller;


use app\common\controller\Common;

class Statistics extends Common
{
    public function index(){
        return $this->fetch();
    }
}