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
            echo 'aaaaa';
        }
        return view();
    }

    public function sendCode(){
        dump("sendCOde");
        dump('sendCone');
        if (request()->isAjax()){
            $tel=trim(input('phoneNum'));
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
        }
    }

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
    /**
     * @Purpose:
     * 模板渲染
     * @Method Name:index()
     *
     * @Author: Wang Wanrong
     *
     * @return mixed 显示登录页面
     */
    public function index_()
    {
        $def = new Suibian();
        /* 从前端获取输入的手机号 */
        $telephone = 15624954416;
        /* 判断是否为管理员 */
        $is_manager = $def -> is_manager($telephone);
        //dump($is_manager);//输出信息（调试）
        /* 是管理员则发送短信 */
        if($is_manager){
            echo '是管理员！';
        }else{
            echo '不是管理员';
        }

        $this -> assign('name1',$is_manager);
        return $this -> fetch();
        //return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }


    //6
    public function index6()
    {
        $def = new Suibian();
        $name = $def -> get_name();
        $this -> assign('name1',$name);
        return $this -> fetch();
        //return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }

    //5
    //use think\Db;
    public function index5()
    {
        $test = Db::name('test') -> where('id',1) -> find();
        //$name = "四";
        $name = $test['name'];
        dump($name);//输出信息（调试）
        $this -> assign('name1',$name);
        return $this -> fetch();
        //return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }
}