<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:34
 */

namespace app\usermanage\controller;


use app\common\controller\Common;

class Userbasic extends Common
{
    public function index(){
        return $this->fetch();
    }
}