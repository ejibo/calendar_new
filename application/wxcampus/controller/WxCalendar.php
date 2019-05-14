<?php

namespace app\wxcampus\controller;

use think\Controller;
use app\wx\controller\Personal;
class WxCalendar extends Personal
{
    protected $cells;
    public function Index(){
        $this->assign($cells, $this->getOneDaySchedule(strtotime('2019-04-08')));
        return $this->fetch("index/wx_calendar");
    }
    public function click(){
        return $this->fetch("index/wx_add");
    }
}