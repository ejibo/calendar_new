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
        if($this->items == NULL)$this->items = $this->getAllScheduleItems();
        if($this->places== NULL)$this->places = $this->getAllSchedulePlaces();
        if($this->times == NULL)$this->times = $this->getAllScheduleTimes();
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
                'time' => $this->times[$sched['time_id']]['name'],
                'id' => $sched['id']
            ];
            array_push($cells, $cell);
        }
        return $cells;
    }
    protected function detail(){
        $this->assign('items', $this->getScheduleItems());
        $this->assign('times', $this->getScheduleTimes());
        $this->assign('places', $this->getSchedulePlaces());
        return $this->fetch("index/wx_detail");
    }
    public function updatePage($scheduleId){
        $this->assign('title', '修改日程');
        $this->assign('confirmid', 'update-btn');
        return $this->detail();
    }
    public function createPage(){
        $this->assign('title', '添加日程');
        $this->assign('confirmid', 'create-btn');
        return $this->detail();
    }
    public function postTest(){
        $data = [
            'user_id'       => $this->getUserId(),
            'date'          => input('post.date'),
            'time_id'       => input('post.time_id'),
            'place_id'      => input('post.place_id'),
            'item_id'       => input('post.item_id'),
            'note'          => input('post.note'),
            'is_delete'     => false,
            'create_time'   => time()
        ];
        var_dump($data);
    }
}