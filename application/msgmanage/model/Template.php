<?php
/**
 * Created by 佟起.
 * User: 
 * Date: 2019/4/18
 */

namespace app\msgmanage\model;
use think\Model;
use think\Db;

class Template extends Model
{
    //绑定表名
    /* protected $table = 'message_template';
    protected $pk = 'id'; */
    /*story:查询消息模板
    负责人：吴珏
    */
    //根据模板名称获取模板
    public function getItemByTitle($tit){
        $titleTemp = Db::name('message_template')
            ->where('title',$tit)
            ->where('is_delete',0)
            ->select();
        return $titleTemp;
    }

    //根据模板内容获取模板
    public function getItemByContent($content){
        $contentTemp = Db::name('message_template')
            ->where('content',$content)
            ->where('is_delete',0)
            ->select();
        return $contentTemp;
    }
    //获取所有模板
    public function getAllTemplates(){
        $allItems = Db::name('message_template')
            ->where('is_delete',0)
            ->select();
        return $allItems;
    }
    //根据模板名称获取模板
    public function getItemByTitleDelete($tit){
        $titleTemp = Db::name('message_template')
            ->where('title',$tit)
            ->where('is_delete',1)
            ->select();
        return $titleTemp;
    }
    //根据模板内容获取模板
    public function getItemByContentDelete($content){
        $contentTemp = Db::name('message_template')
            ->where('content',$content)
            ->where('is_delete',1)
            ->select();
        return $contentTemp;
    }

    /*story:增加消息模板
    负责人：佟起
    */
    public function insertTemplate($tit, $cont){
        $data = ['title' => $tit, 'content'=> $cont, 'is_delete' => 0,'update_time'=> date('Y-m-d H:i:s',time())];
        $res = Db::name('message_template')->insert($data);
        return $res;
    }
    /**
     * Created by 张骁雄.
     * User:
     * Date: 2019/4/21
     */
    //删除模板
    public function clearTemplate($id){
        $res = Db::name("message_template")->where('id',$id)->update(['is_delete'=>1,'delete_time'=> date('Y-m-d H:i:s',time()),'update_time'=> date('Y-m-d H:i:s',time())]);
        return $res;
    }
    //更新模板
    public function updateTemplate($id,$des){
       $res = Db::name("message_template")->where('id',$id)->update(['title'=>$des,'update_time'=> date('Y-m-d H:i:s',time()),'delete_time'=> date('Y-m-d H:i:s',time())]);
       return $res;
    }
      
    /*
    *story:根据消息模板向用户发送提醒消息（刘玄）
    细分story：发送消息提醒
    *负责人：刘玄
    */
    function remind($user_id)
    {
        $data = ['is_remind' => 1,'update_time'=> date('Y-m-d H:i:s',time()),'remind_time'=> date('Y-m-d H:i:s',time())];
         $res = Db::name('message_template')
            ->where('id',$user_id)
            ->update($data);
    }
    /*
    *story:根据消息模板向用户发送提醒消息（刘玄）
    细分story：取消发送消息提醒
    *负责人：刘玄
    */

    function cancelremind($user_id)
    {
         $data = ['is_remind' => 0,'update_time'=> date('Y-m-d H:i:s',time()),'cancelremind_time'=> date('Y-m-d H:i:s',time())];
         $res = Db::name('message_template')
            ->where('id',$user_id)
            ->update($data);
    }
    /*
    *story:根据消息模板向用户发送提醒消息（刘玄）
    细分story：向客户端发送消息内容
    *负责人：刘玄
    */
  public  function remindToApp()
    {
        
        $data = Db::name('message_template')
            ->where('is_delete',0) ->where('is_remind',1)
            ->select();
        return $data;
    }
      



}