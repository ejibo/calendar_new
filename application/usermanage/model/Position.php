<?php

namespace app\usermanage\model;
use think\Model;
use think\Db;

class Position extends Model
{

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
        $position = Position::get($id);
        //更新数据库中的职位名称
        /*$position->name = $name;
        $position->save();//将更新提交至数据库表*/
        $position->save(['name' => $name],['id' => $id]);
        return $position->name;
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
    public function addPosition($name)
    {
        // 接收职务数据

        if (Position::get(['name'=> $name])) {
            //查找是否重复
            $status = 0;
            $message = '职务已存在,请重新输入';
            return ['status'=>$status, 'message'=>$message];
        }

        $user = model('Position');
        //添加职务
        $user->data([
            'name'  =>  $name,
            'is_delete' =>  0
        ]);
        $user->save();
        $status = 1;
        $message = '添加成功';
        return ['status'=>$status, 'message'=>$message];
    }

}




