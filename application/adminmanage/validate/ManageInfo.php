<?php
namespace app\adminmanage\validate;
use think\Validate;
class ManageInfo extends Validate
{

    protected $regex = [ 'zip' => '/^1[3|4|5|8][0-9]{9}$/'];
    protected $rule = [
      'username' => 'require|max:25|unique:manage_info',
      'telephone' => 'require|regex:1[3-9]{1}[0-9]{9}',
      'password' => ['regex' => '/(?!^\d+$)(?!^[A-Za-z]+$)(?!^[^A-Za-z0-9]+$)^\S{8,30}$/']
      //'password' => 'require|min:8'
    ];
    

    protected $message = [
      'username.require' => '必須輸入管理員名稱',
      'username.max' => '管理員名稱不得超過25個字符',
      'password.require' => '必須輸入管理員密碼',
      'telephone' => '請輸入正確的手機號格式',
      'password.regex' => '密碼格式錯誤'
    ];



    protected $scene = [
      'add' => ['username', 'password', 'telephone'],
      'edit' => ['username' => 'require',]
    ];
}
