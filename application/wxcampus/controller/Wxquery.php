<?php
/*
 * 查询用户日程
 * 第二组小刘
 * 2019-5-15
 */

namespace app\wxcampus\controller;
use app\common\controller\Common;
use think\Controller;
use think\Db;
use think\Request;
use app\wxcampus\model\Query as query;


class Wxquery extends controller
{
    public function Index()
    {
    	//按照部门、职务、姓名 查询用户日程
    	$query = new query();  // 实例化模型
        $list = $query->wx_query(); // 使用模型中的wx_query方法
        // dump($res);

        $this->assign('list', $list);
        return $this->fetch('index/wx_search');
    }
  
  	public function query()
    {
        //$query = new query();  // 实例化模型
        //$list = $query->wx_query(); // 使用模型中的wx_query方法
        // dump($res);

        //$this->assign('list', $list);
        // return $this->fetch('index/wx_search');
    }
}
