<?php
/*
 * 查询用户日程
 * 第二组小刘
 * 2019-5-15
 */

namespace app\wx\controller;
use app\common\controller\Common;
use think\Controller;
use think\Db;
use think\Request;

class Wxquery extends controller
{
    //按照部门、职务、姓名 查询用户日程
    public function index($query_name, $work_id)
    {
        // qname 前端传输回来的查询词--用户名
        //$name = Request::instance()->param('query_name');
        //$work_id = Request::instance()->param('query_name');
      
        $res = Db::table('schedule_info')
            ->alias(['schedule_info' => 'a', 'user_info' => 'b', 'user_position' => 'c', 'schedule_time' => 'd', 'schedule_place' => 'e', 'schedule_item' => 'f'])
            ->where('a.is_delete', 0)
            ->where('b.name', $query_name)
            ->where('b.work_id', $work_id)
            ->join('b', 'a.user_id = b.id')
            ->join('c', 'b.position_id = c.id')
            ->join('d', 'a.time_id = d.id')
            ->join('e', 'a.place_id = e.id')
            ->join('f', 'a.item_id = f.id')
            ->field('b.name as name, d.name as time, e.name as place, f.name as item, b.id as id')
            ->select();

        $this->assign('res',$res);
        return $this->fetch("index/wx_search");
    }
}
