<?php
namespace app\adminmanage\model;
use think\Model;
use think\Db;
use think\Session;
use think\Cookie;
use app\logmanage\Model\Log as LogModel; //引入日志接口

class ManageInfo extends Model
{
  //protected $autoWriteTimestamp = true;
  protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
  protected $createTime = 'create_time';
  protected $updateTime = 'update_time';

  protected function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = ip2long($ip);
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

  /**
   * @author 程咏
   * 功能：管理员登入验证
   * @param String username
   * @param String password
   * @version 1.0
   * @return Number 1:帐号错误| 2: 密码错误| 3: 登入成功| 4: 帐户状态失效| 5: 其他错误
   */ 
  public function checkLogin($username, $password){
    Session::set(null);
    cookie('PHPSESSID', null);
    $admin = Db::table('manage_info')->where('username',$username)->find();
    if (!$admin){
      return 1; // 帐号错误
    }
    if($admin['is_delete'] == 1){
      return 4; // 帐户状态失效
    }
    if ($admin['password'] == md5($password)){
      // 纪录 session 和 cookie
      Session::set('admin_id', $admin['id']);
      Session::set('admin_name', $admin['username']);
      Cookie::set('PHPSESSID', Session_id(), 60*29);
      // 写入日志
      $model = new LogModel();
      $type = 1;
      if(Session::get('admin_id')){
        $res = $model->recordLogApi(Session::get('admin_id'), $type);
        if ($res){
          return 3; //登入成功
        }
      }
    }else{
      return 2; //密码错误
    }
    return 5; //其他错误
  }
}
