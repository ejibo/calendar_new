<?php
/**
 * Created by 03_group.
 */

namespace app\logmanage\model;

use think\Model;
use think\Db;

use think\Request;

class Log extends Model
{
    /**
     * 杨宇 董亚聪 苏恒杰
     * 功能：记录web端和企业微信端的登录/增加/修改/删除日志
     * @param
     * @uid：操作人的主键id，非学号
     * @type: 1登陆 2增加 3修改 4删除
     * @table ：操作的数据表名，如操作的数据表为'user_info'，则 $table = 'user_info'
     * @field ：操作的数据表的内容数组
     * @return int
     * 1登陆: 只需要传入$uid, $type
     * 2增加：需要传入$uid, $type, $table, $field(该字段传入你增加的所有数据的主键，如 $field = ['11'，'12'])
     * 3修改：假如同时操作了数据表中主键为22和23的两条数据的field1和field2字段, 则 $field = ['22'=>['field1'=> ['before value', 'after value'], 'field2'=> ['before value', 'after value']],'23'=>['field1'=> ['before value', 'after value'], 'field2'=> ['before value', 'after value']]]
     * 4删除：需要传入$uid, $type, $table, $field(该字段传入你删除的所有数据的主键，如 $field = ['11'，'12'])
     */
    public function recordLogApi($uid, $type, $table = '', $field = ''){
        $agent = Request::instance()->header('user-agent');
        $ip = Request()->ip();
        if($type == 2 || $type == 4) {
            $action = [
                'table' => $table,
                'primary_key' => $field,
            ];
        }else if($type == 3) {
            $action = [
                'table' => $table,
                'primary_key' => $field,
            ];
        }
        if($type == 1) {
            $data = ['user_id' => $uid, 'operate_type' => $type, 'operate_time' => date('Y-m-d H:i:s', time()), 'user_agent' => $agent, 'ip' => $ip];
        }else{
            $data = ['user_id' => $uid, 'operate_type' => $type, 'operate_time' => date('Y-m-d H:i:s', time()), 'operate_action' => json_encode($action), 'user_agent' => $agent, 'ip' => $ip];
        }
        $res = Db::name('log_user')->insert($data);
        return $res;
    }

    /**
     * 贺文鑫
     * 功能：根据本人的工号或学号或管理员id号来查询日志
     * @param $uid
     * @return list
     */
    public function getLogByUid($uid){
        $nameItem = Db::name('log_user')
            ->where('user_id',$uid)
            ->order("log_user.operate_time desc")
            ->select();
        return $nameItem;
    }

    /**
     * 杨宇
     * 功能：查询所有非管理员日志
     * @return array
     */
    public function getAllUserLog(){
        $list = Db::table('log_user')
            ->alias('l')
            ->join('user_info u', 'l.user_id = u.work_id')
            ->where("u.is_delete=0")
            ->order("l.operate_time desc")
            ->select();
        return $list;
    }

    /**
     * 徐辉
     * 功能：查询所有管理员日志
     * @return array
     */
    public function getAllManagerLog(){
        $list = Db::table('log_user')
            ->alias('l')
            ->join('manage_info m', 'l.user_id = m.id')
            ->where("m.is_delete=0")
            ->order("l.operate_time desc")
            ->select();
        return $list;
    }
}
