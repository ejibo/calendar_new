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
    //绑定表名
    protected $table = 'user_depart';
    protected $pk = 'id';

    /*
     *story:恢复删除的部门
     *负责人：李梦好
     */
    public function recover($id){
        $department = Department::get($id);
        //更新该记录的is_delete字段
        $department->is_delete= '0';
        $department->save();//保存，也就是把更新提交到数据库表*/
    }

    /*
     *story:删除部门
     *负责人：
     */
    public function setDelete($id){
        $department = Department::get($id);
        //更新该记录的is_delete字段
        $department->is_delete= '1';
        $department->save();//保存，也就是把更新提交到数据库表*/
    }

    /*
     *story:
     *负责人：
     */
    public function getAllDepartments(){
        $departs = Db::query('select id,name,is_delete from user_depart order by is_delete');
        return $departs;
    }
}