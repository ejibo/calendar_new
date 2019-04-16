<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:47
 */

namespace app\logmanage\controller;


use app\common\controller\Common;
use app\logmanage\model\Log as LogModel;


class Selflog extends Common
{
    public function index(){
        $number = "测试======<br /> 学号 姓名 用户类型 部门 职位 注册日期 最后更新";
        $this->assign('number', $number);
        return $this->fetch();
    }
}