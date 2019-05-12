<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/25 0025
 * Time: 19:31
 */

namespace app\wxcampus\controller;


use think\Controller;

class Index extends Controller
{
//微校相关信息
    private $APP_KEY = "F8D23F9B6A4AA3F2";
    private $SCHOOL_CODE = "1016145360";
    private $APP_SECRET = "8307ED503A6D58E4733D01FC459E340B";

    public function index(){
        return $this->fetch();
        $code = input('param.wxcode');
        $accessToken = $this->getAccessToken($this->APP_KEY,$this->APP_SECRET,$code);
        if($accessToken){
            $userInfo = $this->getUserInfo($accessToken);
            $this->assign("number",$userInfo['card_number']);
            $this->assign("name",$userInfo['name']);
          
            return $this->fetch();
        }
        else{
            echo "error";
        }
    }
    public function wx_search(){
        return $this->fetch();
    }


    private function getAccessToken($key,$secret,$wxcode){
        $url = "https://weixiao.qq.com/apps/school-auth/access-token";
        $data = array(
            "app_key" => $key,      // 微校授权唯一标识
            "wxcode" => $wxcode,            // 第一步获取到的code
            "app_secret" => $secret
        );
        $res = $this->send_post($url, json_encode($data));
        $accessData = json_decode($res,true);
        //判断是否拿到数据
        if($accessData['errcode']===0){
            return $accessData['access_token'];
        }
        else{
            return null;
        }
    }

    private function getUserInfo($accessToken){
        $url = "https://weixiao.qq.com/apps/school-auth/user-info";
        $data = array(
            "token" => $accessToken,
        );
        //dump($data);
        $userData = json_decode($this->send_post($url,json_encode($data)),true);
        //dump($userData);
        return $userData;
    }



    function send_post($url,$data){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        //忽略证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));

        $result = curl_exec($ch);
/*        if (curl_errno($ch)) {
            echo curl_error($ch);
        }*/
        curl_close($ch);
        return $result;
    }


}