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
	protected $name = 'name';
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
     *负责人：张欣童
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
  
  /*
     *story:添加部门
     *负责人：陆韵
     */
    public function addDepartment($name)
    {
        // 接收用户的数据,部门描述
        $status = 1;
        $message = '用户名可用';

        if (Department::get(['name'=> $name])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '部门已存在,请重新输入';
        }
        return ['status'=>$status, 'message'=>$message];
        $rule = [
            'name|部门名称' => "require|min:1|max:30",
        ];

        $result = $this -> validate($name, $rule);

        //if ($result === true) {
        $department = new Department;
        $department->insert($name);
        #$department->name = $name;
        #$department->create_time = time();
        #$department->save();
        #$department->save([
             # 'name'  => $name
         # ]);
        #$department->add();  
       # $status = 1;
       # $message = '添加成功';
        //}else{
        //$status = 0;
        //$message = '部门描述长度须在3-30个字符之间，请重新添加';
        //}

        return ['status'=>$status, 'message'=>$message];
    }
      /*
     *story:修改部门名称
     *负责人：张艺璇
     */
      public function change($id,$name){
          $department = Department::get($id);//可以通过此种方式根据别的字段获取记录
          //更新数据库中的部门名称
          /*$department->name= $name;
          $department->save();//保存，也就是把更新提交到数据库表
          return $department->name;*/
          $department->save([
              'name'  => $name
          ],['id' => $id]);
          return $department->name;
    }

}