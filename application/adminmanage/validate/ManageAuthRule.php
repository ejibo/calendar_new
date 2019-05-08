<?php
namespace app\adminmanage\validate;
use think\Validate;
class ManageAuthRule extends Validate
{

    protected $rule = [
      'name' => 'require|unique:manage_auth_rule',
      'title' => 'unique:manage_auth_rule',
    ];
    

    protected $message = [
      'name.unique' => '權限 [控制器/方法] 不得重複',
      'title.unique' => '權限名稱不得重複',
      'name.require' => '請輸入 [控制器/方法]'
    ];

}
