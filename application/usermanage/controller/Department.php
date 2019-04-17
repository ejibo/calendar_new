<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:36
 */

namespace app\usermanage\controller;
use app\common\controller\Common;

class Department extends Common
{
    public function index(){
        return $this->fetch();
    }
    /*
     *story:恢复删除的部门
     *负责人：李梦好
     */
    public function recover($id)
    {
        //调用model中的recover方法，保证MVC分离
        $department = model('Department');
        $department->recover($id);
    }
    /*
     *story:删除部门
     *负责人：张欣彤
     */
    public function delete($id)
    {
        $department = model('Department');
        $department->setDelete($id);
    }

    /*
     *story:
     *负责人：
     */
    public function loadDepartment()
    {
        $department = model('Department');
        $departments = $department->getAllDepartments();
        return $departments;
    }

    /*
     *story:添加部门
     *负责人：
     */
    public function addDepartment($userName)
    {
        // 接收用户的数据,部门描述
        $status = 1;
        $message = '用户名可用';

        if (Department::get(['name'=> $userName])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '部门已存在,请重新输入';
        }
        return ['status'=>$status, 'message'=>$message];

        $rule = [
            'name|部门名称' => "require|min:1|max:30",
        ];

        $result = $this -> validate($userName, $rule);

        //if ($result === true) {
        $department = new Department;
        $department->name = $userName;
        $department->create_time = time();
        $department->save();
        $status = 1;
        $message = '添加成功';
        //}else{
        //$status = 0;
        //$message = '部门描述长度须在3-30个字符之间，请重新添加';
        //}

        return ['status'=>$status, 'message'=>$message];
    }
    /*
     *story:修改部门
     *负责人：
     */
    public function change($id,$name)
    {
        $department = Department::get(['id' => $id]);//可以通过此种方式根据别的字段获取记录

        //通过ID值获取到数据表中的该条记录
        //$department = Department::get(1);
        //更新该记录的is_delete字段
        $department->name= $name;
        $department->save();//保存，也就是把更新提交到数据库表
        print_r($department);
    }
}