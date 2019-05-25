<?php

namespace app\wxcampus\controller;

use app\logmanage\model\Log;
use think\Controller;
use app\manageconfig\model\ScheduleDefault as ScheduleDefaultModel;
use think\Exception;
use think\Request;
use think\Db;

class ScheduleDefault extends Controller
{

    /**管理默认日程的界面*/
    public function index($uid, $wxcode)
    {
        $this->assign("uid", $uid);
        $this->assign("userid", $uid);
        $this->assign("wxcode", $wxcode);
        $this->assign("defaultSchedules", ScheduleDefaultModel::getDefaultSchedules($uid));
        return $this->fetch();
    }

    /**
     *添加默认日程界面
     */
    public function wx_add_schedule_default($uid, $wxcode)
    {
        $this->assign("uid", $uid);
        $this->assign("userid", $uid);
        $this->assign("wxcode", $wxcode);
        $this->assign("title", "添加默认日程");
        return $this->fetch();
    }

    /**
     * 添加默认日程动作
     */
    public function addDefaultSchedule($uid)
    {
        $param = Request::instance()->post();
        $res = $this->validate($param, 'app\manageconfig\validate\ScheduleDefault');//验证是否符合规范
        if (true !== $res) {
            return json(['code' => 403, 'msg' => '参数不符合规则：' . $res]);
        }
        $schedule = new ScheduleDefaultModel();
        try {
            $schedule->setUserId($uid);
            $schedule->setDay($param['day']);
            $schedule->setTime($param['time']);
            $schedule->checkSameTimeDefaultSchedule();
            $schedule->setPlace($param['place']);
            $schedule->setItem($param['item']);
            $schedule->setNote($param['note']);
        } catch (\InvalidArgumentException $e) {
            return json(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }
        $schedule->is_delete = 0;
        $schedule->update_time = date("Y-m-d H:i:s");
        if ($schedule->save()) {
            $log = new Log();
            $log->recordLogApi($uid, 2, 0, "schedule_default", [$schedule->id]);
            return json(['code' => 1, 'msg' => 'success']);
        } else {
            return json(['code' => -1, 'msg' => '添加失败，发生未知错误']);
        }
    }

    /**
     *修改默认日程界面
     */
    public function wx_update_schedule_default($uid, $item_id, $day, $time, $place, $item, $note, $wxcode)
    {
        $this->assign("uid", $uid);
        $this->assign("userid", $uid);
        $this->assign("item_id", $item_id);//默认事项id
        $this->assign("time", $time);//待更改默认事项时间
        $this->assign("place", $place);//待更改默认事项地点
        $this->assign("item", $item);//待更改默认事项内容
        $this->assign("day", $day);
        $this->assign("note", $note);//待更改默认事项备注
        $this->assign("wxcode", $wxcode);
        $this->assign("title", "更新默认日程");
        return $this->fetch();
    }
    public function updateDefaultSchedule($uid)
    {
        $param = Request::instance()->post();

        $res = $this->validate($param, 'app\manageconfig\validate\ScheduleDefault');//验证是否符合规范
        if (true !== $res) {
            return json(['code' => 403, 'msg' => '参数不符合规则：' . $res]);
        }
        $item_id = $param['item_id'];//需删除的id
        $res = $this->wx_delete_schedule_default($uid, $item_id);//将其删除
        if (true !== $res) {
            return json(['code' => 403, 'msg' => '更新出错：' . $res]);
        }
        $schedule = new ScheduleDefaultModel();
        try {
            $schedule->setUserId($uid);
            $schedule->setDay($param['day']);
            $schedule->setTime($param['time']);
            $schedule->checkSameTimeDefaultSchedule();
            $schedule->setPlace($param['place']);
            $schedule->setItem($param['item']);
            $schedule->setNote($param['note']);
        } catch (\InvalidArgumentException $e) {
            return json(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }
        $schedule->is_delete = 0;
        $schedule->update_time = date("Y-m-d H:i:s");
        if ($schedule->save()) {
            $log = new Log();
            $log->recordLogApi($uid, 2, 0, "schedule_default", [$schedule->id]);
            return json(['code' => 1, 'msg' => 'success']);
        } else {
            return json(['code' => -1, 'msg' => '添加失败，发生未知错误']);
        }
    }

    /**
     *删除默认日程界面
     */
    public function wx_delete_schedule_default($uid, $item_id, $id)
    {
        //执行删除的操作
        $result = Db::name("schedule_default")->where('id', $id)->update(['is_delete' => 1, "delete_time" => date("Y-m-d H:i:s")]);
        if ($result){
            return true;
        }else{
            return false;
        }

    }
    /**
     * 修改默认日程界面
     */
    public function wx_edit_schedule_default($uid, $wxcode, $schedule){
        $this->assign("uid", $uid);
        $this->assign("userid", $uid);
        $this->assign("wxcode", $wxcode);
        $this->assign("note", $schedule['note']);
        $this->assign("title", "修改默认日程");
        return $this->fetch();
    }


}


