<?php

namespace app\wxcampus\controller;

use think\Controller;

class WxCalendar extends Controller
{
    public function Index(){
        return $this->fetch("index/wx_calendar");
    }
}