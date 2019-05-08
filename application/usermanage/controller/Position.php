<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:38
 */

namespace app\usermanage\controller;


use app\common\controller\Common;

class Position extends Common
{
    public function index(){
        return $this->fetch();
    }

}