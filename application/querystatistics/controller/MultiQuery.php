<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/13
 * Time: 22:50
 */

namespace app\querystatistics\controller;


use app\common\controller\Common;

class MultiQuery extends Common
{
    public function index(){

        return $this->fetch();
    }
}