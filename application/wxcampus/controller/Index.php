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
//申请到的企业微信id和secret
    private $APP_KEY = "F8D23F9B6A4AA3F2";
    private $SCHOOL_CODE = "1016145360";
    private $APP_SECRET = "8307ED503A6D58E4733D01FC459E340B";

    public function index(){
        //return $this->fetch();
        $code = input('param.wxcode');
        $accessToken = $this->getAccessToken($this->APP_KEY,$this->APP_SECRET,$code);
        if(!$accessToken){
            $userInfo = $this->getUserInfo($accessToken);
            dump($userInfo);
        }
    }

    private function getAccessToken($key,$secret,$wxcode){
        $url = "https://weixiao.qq.com/apps/school-auth/access-token";
        $jdata = json_encode(array(
            "app_key" => $key,      // 微校授权唯一标识
            "wxcode" => $wxcode,            // 第一步获取到的code
            "app_secret" => $secret
        ));
        $accessData = $this->http_request($url, $jdata);
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
        $jdata = json_encode(array([
            "token" => $accessToken,
        ]));
        $userData = $this->http_request($url,$jdata);
        return $userData;
    }

    /**
     * @param $url
     * @return get方式请求得到的数据
     */
    function https_request_get ($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $out = curl_exec($ch);
        curl_close($ch);
        return  json_decode($out,true);
    }

//HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output);
    }


}