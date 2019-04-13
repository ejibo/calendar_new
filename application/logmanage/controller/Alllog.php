<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:48
 */

namespace app\logmanage\controller;


use app\common\controller\Common;

class Alllog extends Common
{
    public function index(){
        return $this->fetch();
    }

}