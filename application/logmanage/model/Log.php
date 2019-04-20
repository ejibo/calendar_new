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
     * 杨宇 董亚聪
     * 功能：记录企业微信端的登陆和操作日志
     * @param 
     * @uid：操作人的工号或学号
     * @type: 1登陆 2增加 3修改 4删除
     * @table ：操作的数据表名
     * @field ：操作的数据表中的字段数组, 如果同时操作了多个字段，字段之间用下划线'_'连接
     * @fron ：操作前的字段值，各个字段操作前的内容之间用下划线'_'连接
     * @to ：操作后的字段值，各个字段操作后的内容之间用下划线'_'连接
     * @return int
     * 1. 登陆：只需要传入$uid, $type
     * 2. 增加：只需要传入$uid, $type, $table, $field(该字段传入你增加的那条数据的主键，如果是批量添加，传入主键数组)
     * 3. 修改：需要传入全部形参，参看形参解释
     * 4. 删除：只需要传入$uid, $type, $table, $field(该字段传入你删除那条数据的主键，如果是批量删除，传入主键数组如['11'，'12'])
     */
     public function addWechatLog($uid, $type, $table = '', $field = '', $from = '', $to =''){
         // Db::name('user')->insertGetId($data);
         $agent = Request::instance()->header('user-agent');
         $ip = Request()->ip();
         $action = [
             'table'=> $table,
             'field'=> $field,
             'from'=> $from,
             'to'=> $to
         ];
         dump($action);

         $data = ['user_id' => $uid, 'operate_type'=> $type, 'operate_time'=> date('Y-m-d H:i:s',time()),'operate_action' => $action, 'user_agent'=> $agent,'ip' => $ip];
         $res = Db::name('log_user')->insert($data);
         return $res;
     }
 
    /**
     * 苏恒杰
     * 功能：记录管理员的所有操作日志
     * @param
     * @uid：操作人的管理员id
     * @type: 1登陆 2增加 3修改 4删除
     * @table ：操作的数据表名
     * @field ：操作的数据表中的字段, 如果同时操作了多个字段，字段之间用下划线'_'连接
     * @fron ：操作前的字段值，各个字段操作前的内容之间用下划线'_'连接
     * @to ：操作后的字段值，各个字段操作后的内容之间用下划线'_'连接
     * @return int
     * 1. 登陆：只需要传入$uid, $type
     * 2. 增加：只需要传入$uid, $type, $table, $field(该字段传入你增加的那条数据的主键，如果是批量添加，传入主键数组)
     * 3. 修改：需要传入全部形参，参看形参解释
     * 4. 删除：只需要传入$uid, $type, $table, $field(该字段传入你删除那条数据的主键，如果是批量删除，传入主键数组)
     */
     public function addMangerLog($uid, $type, $table = '', $field = '', $from = '', $to =''){
         $agent = Request::instance()->header('user-agent');
         $ip = Request()->ip();
         $action = [
             'table'=> $table,
             'field'=> $field,
             'from'=> $from,
             'to'=> $to
         ];
         dump($action);

         $data = ['user_id' => $uid,'operate_type'=>$type,'operate_time'=> date('Y-m-d H:i:s',time()),'operate_action'=>$action,'user_agent'=>$agent,'ip'=>$ip];
         $res = Db::name('log_user')->insert($data);
         return $res;
     }

    /**
     * 贺文鑫
     * 功能：根据本人的id号来查询日志
     * @param $uid
     * @return list
     */
     public function getLogByUid($uid){
         $nameItem = Db::name('log_user')
             ->where('user_id',$uid)
              ->order("log_user.operate_time desc")
             ->find();
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