<?php
namespace app\login\controller;
use app\common\controller\Common;
use think\Controller;
use think\Request;
use think\Db;
use app\login\controller\ZhenziSmsClient;

class Smsbinding extends Common
{
    /**
     * 吴欣雨
     * 功能：发送验证码
     */
    public function index()
    {
        global $_W, $_GPC;
        session_start();
        $_SESSION['telephone'] = $_GPC['phone'];
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
        $_SESSION['verifyCode'] = $verifyCode;
        if ($result->code == '0') {
            echo '发送成功';
        } else {
            echo '发送失败';
        }
        return $this->fetch('index');
    }
}