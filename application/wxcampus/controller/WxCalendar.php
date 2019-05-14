<?php

namespace app\wxcampus\controller;

use think\Controller;
use app\wx\controller\Personal;
class WxCalendar extends Personal
{
    protected $items;
    protected $places;
    protected $times;
    public function Index(){
        $this->items = $this->getScheduleItems();
        $this->places = $this->getSchedulePlaces();
        $this->times = $this->getScheduleTimes();
        $this->assign('date', date('Y-m-d'));
        $this->assign('cells', $this->getScheduleDisplayArray(strtotime('2019-04-09')));
        return $this->fetch("index/wx_calendar");
    }
    protected function getScheduleDisplayArray($timestamp){
        assert($this->items != NULL);
        assert($this->places!= NULL);
        assert($this->times != NULL);
        $cells = [];
        $schedules = $this->getOneDaySchedule($timestamp);
        foreach ($schedules as $sched){
            $cell = [
                'note' => $sched['note'],
                'item' => $this->items[$sched['item_id']]['name'],
                'place' => $this->places[$sched['place_id']]['name'],
                'time' => $this->times[$sched['time_id']]['name']
            ];
            array_push($cells, $cell);
        }
        var_dump($cells);
        return $cells;
    }
    public function add(){
        return $this->fetch("index/wx_add");
    }
}