<?php
namespace app\adminmanage\model;
use think\Model;
use think\Db;

class ManageInfo extends Model
{
  //protected $autoWriteTimestamp = true;
  protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
  protected $createTime = 'create_time';
  protected $updateTime = 'update_time';
}
