<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:55
 */

namespace app\login\controller;


use think\Controller;
use app\logmanage\model\Log as LogModel;


class Namelogin extends Controller
{
    public function index(){
        return $this->fetch();
    }
}