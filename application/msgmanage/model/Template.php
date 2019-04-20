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

    //根据模板名称获取模板
    public function getItemByTitle($tit){
        $titleTemp = Db::name('message_template')
            ->where('title',$tit)
            ->where('is_delete',0)
            ->find();
        return $titleTemp;
    }

    //获取所有模板
    public function getAllTemplates(){
        $allItems = Db::name('message_template')
            ->where('is_delete',0)
            ->select();
        return $allItems;
    }

    //插入模板
    public function insertTemplate($tit, $cont){
        $data = ['title' => $tit, 'content'=> $cont, 'is_delete' => 0,'update_time'=> date('Y-m-d H:i:s',time())];
        $res = Db::name('message_template')->insert($data);
        return $res;
    }

}