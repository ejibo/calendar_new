<?php
/**
 * Created by PhpStorm.
 * User: 李梦好
 * Date: 2019/4/9
 * Time: 19:55
 */

namespace app\usermanage\model;
use think\Model;
use think\Db;

class Department extends Model
{
    /*
    *story:修改职务
    *负责人：张君兰
    */
    public function change($id, $name)
    {
        $position = Position::get($id);//获取用户id
        //更新数据库中的职务名称
        $position->name = $name;
        $position->save();//将更新提交至数据库表
    }
}