<?php
namespace app\login\controller;
use app\common\controller\Common;
use think\Controller;
use think\Request;
use think\Db;
use app\login\controller\ZhenziSmsClient;
use app\login\model\Mobile;

class Smsbinding extends Common
{
    public function index()
    {
        return $this->fetch();
    }
    public function getCode(){
        $telephone=Request::instance()->post('telephone');
        $mobile=new Mobile();
        $checkres=$mobile->hasMobile();
        if($checkres){//手机号已绑定
            $res['code']=3;
        }
        else {
            session_start();
            $_SESSION['telephone'] = $telephone;
            if (isset($_SESSION['time'])) {
                /*未过期*/
                if ($_SESSION['time']+ 60 > time()) {
                    echo '一分钟内多次操作';
                } else {
                    $_SESSION['time'] = time();
                }
            } else {
                $_SESSION['time'] = time();
            }
            $seed = time();                   // 使用时间作为种子源
            srand($seed);                     // 播下随机数发生器种子
            $verifyCode = rand(100000, 999999);
            $client = new  ZhenziSmsClient("https://sms_developer.zhenzikj.com", "101241", "7c697169-8031-4c8d-8a5f-653c107e6711");
            $result = $client->send($_GPC['phone'], "您的验证码为" + $verifyCode + "，有效时间为5分钟");
            var_dump($result);
            if($result['code']=='0'){
                $res['code']=1;
            }
            else $res['code']=2;
        }
      	echo json_encode($res); 
    }
}