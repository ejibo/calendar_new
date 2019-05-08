<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:56
 */

namespace app\login\controller;

use app\logmanage\model\Log as LogModel;
use think\Controller;
//use think\Db;
use app\login\model\Mobile as Suibian;

//http://localhost/ss_calendar_new/public/index.php/login/phonelogin/index
class Phonelogin extends Controller
{
    /**
     * @Purpose:
     * 判断是否登录成功
     * @Method Name:index()
     *
     * @Author: Wang Wanrong
     *
     * @return mixed 渲染页面
     */
    public function index()
    {
        if (request()->isAjax()){
            $tel = trim(input('tel'));
            $code = trim(input('code'));
            if ( !empty($tel) && !empty($code) && $tel == cookie('tel') && $code == cookie('Code')){
                $msg=['status'=>0,'msg'=>'登陆成功'];
                return json($msg);
            }else{
                $msg=['status'=>1,'msg'=>'登陆失败'];
                return json($msg);
            }
        }
        else{
            //echo 'aaaaa';
        }
        return view();
    }

    /**
     * @Purpose:
     * 判断是否为管理员并发送短信
     * @Method Name:sendCode()
     *
     * @Author: Wang Wanrong
     *
     * @return mixed 返回的信息
     */
    public function sendCode(){
        //dump("sendCOde");
        //dump('sendCone');
        if (request()->isAjax()){
            /* 前端的电话号码 */
            $tel=trim(input('phoneNum'));

            /* 判断是否为管理员号码 */
            $def = new Suibian();
            $is_manager = $def -> hasMobile($tel);
            /* 是管理员则发送短信 */
            if($is_manager){
//                $msg=['status'=>0,'msg'=>'是管理员'];
//                return json($msg);
                $code = mt_rand(10000,99999);
                $res = $this->aip($tel,$code);
                if($res == 0){
                    cookie('tel',$tel,60);
                    cookie('Code',$code,60);
                    $msg=['status'=>0,'msg'=>'短信发送成功'];
                    return json($msg);
                }else{
                    cookie('tel', null);
                    cookie('Code', null);
                    $msg=['status'=>$res,'msg'=>'短信发送失败'];
                    return json($msg);
                }
            }else{
                //echo '不是管理员';
                $msg=['status'=>1,'msg'=>'该号码不是管理员手机号'];
                return json($msg);
            }
        }
    }

    /**
     * @Purpose:
     * 请求第三方 API 发送短信
     * @Method Name:aip()
     *
     * @Author: Wang Wanrong
     *
     * @return mixed 第三方平台返回的结果
     */
    public function aip($tel,$code,$time=1){

        $smsapi = "http://www.smsbao.com/"; //短信网关
        $user = "annora"; //短信平台帐号
        $pass = md5("370682"); //短信平台密码
        $content="【wwr】您的验证码为{$code}，在{$time}分钟内有效！";//要发送的短信内容
        $phone = $tel;
        $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
        $result =file_get_contents($sendurl) ;
        return $result;
    }
}