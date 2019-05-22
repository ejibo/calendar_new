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
        $query_name = input('post.query_name');
        $work_id = input('post.work_id');
    	//按照部门、职务、姓名 查询用户日程
    	$query = new query();  // 实例化模型
        
      	$list = array();
        $list = $query->wx_query(); // 使用模型中的wx_query方法
        // dump($list);
      
        $depart_list = array();
        $depart_list = Db::table('user_depart')
          ->where('is_delete', 0)
          ->select();
      
        $position_list = array();
        $position_list = Db::table('user_position')
          ->where('is_delete', 0)
          ->select();
      
        $this->assign('depart', $depart_list);
      	$this->assign('pos', $position_list);
        if ($list==NULL){echo '检索结果为空'} else{$this->assign('list', $list);};          
        return $this->fetch('index/wx_search');
    }
  
}
