<?php

namespace app\wxcampus\controller;

use think\Controller;
use app\logmanage\model\Log as LogModel;
use think\Validate;
use think\Request;
use think\Db;

class CalendarValidator extends Validate
{
    protected $rule =[
        'pagenum' => 'require|number|>=:0',
        'id' => 'require|number|checkTable:schedule_info,id',
        'user_id' => 'require|number',
        'date' => 'require|date|checkDate',
        'time_id' => 'require|number|checkTable:schedule_time,id',
        'place_id' => 'require|number|checkTable:schedule_place,id',
        'item_id' => 'require|number|checkTable:schedule_item,id',
    ];
    protected $scene = [
        'getSchedule' => [
            'pagenum'
        ],
        'create' => [
            'date',
            'time_id',
            'place_id',
            'item_id'
        ],
        'update' => [
            'id',
            'date',
            'time_id',
            'place_id',
            'item_id'
        ],
        'delete' => [
            'id'
        ]
    ];
    protected function checkTable($value, $rule, $data, $field){
        $params = explode(',', $rule);
        return Db::name($params[0])->where($params[1], $value)->count() == 1;
    }
    private function getDdlTimestamp(){
        //TODO
        return time() + 5*24*60*60;
    }
    protected function checkDate($value, $rule, $data, $field){
        $given = strtotime($value);
        $now = strtotime(date('Y-m-d'));
        $ddl = $this->getDdlTimestamp();
        return $now <= $given && $given <= $ddl;
    }
}

class WxCalendar extends Controller
{
    //apis
    protected function getUserId(){
        //TODO
        return 1;
    }
    private function getDdl(){
        //TODO
        return date('Y-m-d', time() + 24*60*60);
    }
    protected function getOneDaySchedule($timestamp){
        $page = Db::name('schedule_info')
            ->where('user_id', $this->getUserId())
            ->where('date', date('Y-m-d', $timestamp))
            ->where('is_delete', 0)
            ->select();
        return $page;
    }
    protected function getSchedule($scheduleId){
        return Db::name('schedule_info')
            ->where('user_id', $this->getUserId())
            ->where('id', $scheduleId)
            ->find();
    }
    //返回所有相关字段, 保证当一个项被删除后, 依然可以显示.
    protected function getAllScheduleItems(){
        return Db::name('schedule_item')
            ->select();
    }
    protected function getAllSchedulePlaces(){
        return Db::name('schedule_place')
            ->select();
    }
    protected function getAllScheduleTimes(){
        return Db::name('schedule_time')
            ->select();
    }
    protected function getScheduleItems(){
        return Db::name('schedule_item')
        ->where('is_delete', 0)
        ->select();
    }
    protected function getSchedulePlaces(){
        return Db::name('schedule_place')
        ->where('is_delete', 0)
        ->select();
    }
    protected function getScheduleTimes(){
        return Db::name('schedule_time')
        ->where('is_delete', 0)
        ->select();
    }
    protected function json($method, $success, $message){
        return json([
            'method' => $method,
            'success'=> $success,
            'message'=> $message
        ]);
    }

    public function create(){
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
        //检查输入是否有效
        $valid = $this->validate($data, 'app\wxcampus\controller\CalendarValidator.create');
        if($valid !== true){//验证失败
            return $this->json('create', false, dump($valid, false));
        }
        //插入
        $id = Db::name('schedule_info')->insertGetId($data);
        //记录
        $logRec = new LogModel;
        $logRec->recordLogApi($this->getUserId(), 2, 'schedule_info', $id);
        return $this->json('create', true, 'success');
    }
    public function update(){
        $data = [
            'id'            => input('post.id'),
            'user_id'       => $this->getUserId(),
            'date'          => input('post.date'),
            'time_id'       => input('post.time_id'),
            'place_id'      => input('post.place_id'),
            'item_id'       => input('post.item_id'),
            'note'          => input('post.note'),
            'update_time'   => time()
        ];
        $valid = $this->validate($data, 'app\wxcampus\controller\CalendarValidator.update');

        if($valid !== true){//验证失败
            return $this->json('update', false, dump($valid,false));;
        }
        //找到修改了的参数
        $origin = Db::name('schedule_info')
            ->where('id', $data['id'])
            ->where('user_id', $data['user_id'])
            ->find();
        if($origin == NULL){
            return $this->json('update', false, '找不到要修改的参数');;
        }
        $diff = [];
        foreach($data as $key=>$val){
            if($origin[$key] !== $val){
                $diff[$key] = [$origin[$key], $val];
            }
        }
        //更新
        $success = Db::name('schedule_info')
            ->where('id', $data['id'])
            ->where('user_id', $data['user_id'])
            ->update($data);
        if($success !== 1){//更新失败
            return $this->json('update', false, '数据库插入失败!');
        }
        //记录日志
        $logRec = new LogModel;
        $logRec->recordLogApi($this->getUserId(), 3, 'schedule_info', [$data['id'] => $diff]);
        return $this->json('update', true, 'success');
    }

    public function delete($id){
        $uid = getUserId();
        //检查是否有效
        $valid = $this->validate($data, 'app\wxcampus\controller\CalendarValidator.delete');
        if($valid !== true){
            return $this->json('delete', false, dump($valid, false));
        }
        //删除
        $success = Db::name('schedule_info')
            ->where('id', $id)
            ->where('user_id', $uid)
            ->update([
                'is_delete'     => true,
                'delete_time'   => time()
            ]);
        if($success != 1){//删除失败
            return $this->json('delete', false, '数据库删除失败!');
        }
        //记录日志
        $logRec = new LogModel;
        $logRec->recordLogApi($uid, 4, 'schedule_info', [$id]);
        return $this->json('delete', true, 'success');;
    }
    //Views
    protected $items;
    protected $places;
    protected $times;
    public function Index($date = NULL){
        if($date == NULL)$date = date('Y-m-d');
        if($this->items == NULL)$this->items = $this->getAllScheduleItems();
        if($this->places== NULL)$this->places = $this->getAllSchedulePlaces();
        if($this->times == NULL)$this->times = $this->getAllScheduleTimes();
        $this->assign('date', date('Y-m-d',strtotime($date)));
        $this->assign('cells', $this->getScheduleDisplayArray(strtotime($date)));
        return $this->fetch("index/wx_calendar");
    }
    public function getScheduleDisplayArray($timestamp){
        assert($this->items != NULL);
        assert($this->places!= NULL);
        assert($this->times != NULL);
        $cells = [];
        $schedules = $this->getOneDaySchedule($timestamp);
        foreach ($schedules as $sched){
            $time = $this->times[$sched['time_id']]['name'];
            if(!array_key_exists($time, $cells)){
                $cell = [
                    'time' => $time,
                    'data' => []
                ];
                $cells[$time] = $cell;
            }
            $dataItem = [
                'item' => $this->items[$sched['item_id']]['name'],
                'note' => $sched['note'],
                'place'=> $this->places[$sched['place_id']]['name'],
                'id'   => $sched['id']
            ];
            array_push($cells[$time]['data'], $dataItem);
        }
        return $cells;
    }
    protected function detail(){
        $this->assign('items', $this->getScheduleItems());
        $this->assign('times', $this->getScheduleTimes());
        $this->assign('places', $this->getSchedulePlaces());
        $this->assign('maxlength', 200);
        return $this->fetch("index/wx_detail");
    }
    public function updatePage($id){
        $sched = $this->getSchedule($id);
        $this->assign('scheduleid', $id);
        $this->assign('date', $sched['date']);
        $this->assign('note', $sched['note']);
        $this->assign('title', '修改日程');
        $this->assign('confirmid', 'update-btn');
        return $this->detail();
    }
    public function createPage(){
        $this->assign('scheduleid', -1);
        $this->assign('date', '');
        $this->assign('note', '');
        $this->assign('title', '添加日程');
        $this->assign('confirmid', 'create-btn');
        return $this->detail();
    }
    public function postTest(){
        $data = [
            'user_id'       => $this->getUserId(),
            'method'        => input('post.method'),
            'date'          => input('post.date'),
            'time_id'       => input('post.time_id'),
            'place_id'      => input('post.place_id'),
            'item_id'       => input('post.item_id'),
            'note'          => input('post.note'),
            'create_time'   => time()
        ];
        var_dump($data);
    }
}