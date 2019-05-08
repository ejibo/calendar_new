<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:47
 */

namespace app\logmanage\controller;


use app\common\controller\Common;

class Selflog extends Common
{
    public function index(){
        return $this->fetch();
    }

}