<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 9:43
 */

namespace app\wx\controller;


use app\wx\common\Common;
use app\logmanage\model\Log as LogModel;
use think\Db;

class Personal extends Common
{
    protected function getUserId(){

    }
    protected function validate($table, $field, $id){
        return Db::name($table)->where($field, $id)->find() != NULL;
    }
    public function create($date, $timeid, $placeid, $itemid, $note){
        $uid = getUserId();
        if($uid == NULL){
            //TODO
        }
        //检查输入是否有效
        if( !validate('schedule_time', 'id', $timeid)||!validate('schedule_place','id', $placeid)||!validate('schedule_item','id', $itemid)){ //出错的情况
            //TODO
        }

        //插入
        $success = Db::name('schedule_info')->insert([
            'user_id'       => $uid,
            'date'          => $date,
            'time_id'       => $timeid,
            'place_id'      => $placeid,
            'item_id'       => $itemid,
            'note'          => $note,
            'is_delete'     => false,
            'create_time'   => time()
        ]);
        $id = Db::name('schedule_info')->getLastInsID();
        if($success != 1){//插入失败
            //TODO
        }
        //记录
        $logRec = new LogModel;
        $logRec->recordLogApi($uid, 2, 'schedule_info', $id);
    }
    public function update($date, $id, $timeid, $placeid, $itemid, $note){
        $uid = getUserId();
        if($uid == NULL){
            //TODO
        }
        //检查是否有效
        //TODO

        $origin = Db::name('schedule_info')
            ->where('user_id', $uid)
            ->where('id', $id)
            ->find();
        if(origin == NULL){
            //TODO
        }
        //比较

        $diff;
        //更新
        $success = Db::name('schedule_info')
            ->where('user_id', $uid)
            ->where('id', $id)
            ->update([
                'date'          => $date,
                'time_id'       => $timeid,
                'place_id'      => $placeid,
                'item_id'       => $itemid,
                'note'          => $note,
                'update_time'   => time()
            ]);
        $id = Db::name('schedule_info')->getLastInsID();
        if($success != 1){//更新失败
            
        }
        //记录日志
        $logRec = new LogModel;
        $logRec->recordLogApi($uid, 3, 'schedule_info', [$id => $diff]);
    }

    public function delete($id){
        $uid = getUserId();
        if($uid == NULL){
            //TODO
        }
        //检查是否有效
        //删除
        $success = Db::name('schedule_info')
            ->where('id', $id)
            ->where('user_id', $uid)
            ->update([
                'is_delete'     => true,
                'delete_time'   => time()
            ]);
        if($success != 1){//删除失败

        }
        //记录日志
        $logRec = new LogModel;
        $logRec->recordLogApi($uid, 4, 'schedule_info', [$id]);
    }
}