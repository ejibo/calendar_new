<?php

namespace app\usermanage\model;

use think\Model;
use think\Db;

class Position extends Model
{
    //绑定表名
    protected $table = 'user_positions';
    protected $pk = 'id';
    protected $name = 'name';

    /**
     * 第05组 高裕欣
     * 功能：显示列表
     */
    public function getUserPositionList()
    {
        $list = Db::table('user_position')
            ->select();
        return $list;
    }

    /**
     *第05组 张君兰
     * 功能：修改职位
     */
    public function change($id, $name)
    {
        Db::table('user_position')
            ->where('id', $id)
            ->update(['name' => $name]);

        return $name;
    }

    /**
     * 第05组 高裕欣
     * 功能：作废职位
     */
    function invalid($user_id)
    {
        $data = array();
        $data['is_delete'] = 1;
        $data['delete_time'] = Db::raw('now()');
        Db::table('user_position')->where('id', $user_id)->update($data);
    }

    function restore($user_id)
    {
        $data = array();
        $data['is_delete'] = 0;
        $data['delete_time'] = Db::raw('now()');
        Db::table('user_position')->where('id', $user_id)->update($data);
    }

    /**
     * 第05组 张楚悦
     * 功能：添加职位
     */
    //添加职位
    public function insertPosition($name)
    {
        $data = ['name' => $name, 'is_delete' => 0];
        $result = Db::name('user_position')->insert($data);
        return $result;
    }
    //获取已有职位信息
    public function getPosition($name){
        $namePosition = Db::name('user_position')
            ->where('name',$name)
            ->where('is_delete',0)
            ->find();
        return $namePosition;
    }

    /*public function isexist($name){
        $exist = Db::table('user_position')->where('status',1)->where('name',$name)->find();
        if ($exist){
            return true;
        }else{
            return false;
        }
    }
    public static function addPosition($name)
    {
        // 接收用户的数据,部门描述
        $position = ['name'=>$name,'status'=>1];
        $ok = Db::table('user_position')->insert($position);
        if ($ok){
            return true;
        }else{
            return false;
        }
    }*/
    /*
    public function addPosition($name)
    {
        // 接收用户的数据,部门描述

        if (Position::get(['name' => $name])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '职位已存在,请重新输入';
            return ['status' => $status, 'message' => $message];
        }

        $user = model('Position');
        // 模块实例化
        $user->data([
            'name' => $name,
            'is_delete' => 0
        ]);
        $user->save();
        $status = 1;
        $message = '添加成功';
        return ['status' => $status, 'message' => $message];
    }*/
}




