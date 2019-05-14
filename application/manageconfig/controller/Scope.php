<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:26
 */

namespace app\manageconfig\controller;

use think\Model;
use app\common\controller\Common;
use think\Db;
use think\Request;
use app\manageconfig\model\ScopeModel;

class Scope extends Common
{
    public function index()
    {
        $scope_model = new ScopeModel();
        $scope_model->initScope();

        // $default = array();
        // $default['Scope'] = 2592000; //缺省为30天
        // if (!file_exists('scope/config.json')) { //检查文件夹是否存在
        //     mkdir("scope");    //没有就创建一个新文件夹
        //     file_put_contents('scope/config.json', json_encode($default));
        //     //初始化配置文件
        // }



        // // 从文件中读取数据到PHP变量  
        // $json_string = file_get_contents('scope/config.json');
        // //从数据库读取数据
        // $scope_from_mysql = $scope_model->getScope();


        // // 用参数true把JSON字符串转成PHP对象 
        // $data = json_decode($json_string);
        // $last_scope = $data->Scope / 86400;

        $last_scope_test = $scope_model->getScope();
        $this->assign('last_scope', $last_scope_test['parameter'] / 86400);
        return $this->fetch();
    }
    public function scopeModify()
    {
        // 获取post数据        
        $scope = Request::instance()->param('scope');
        $scope_model = new ScopeModel();

        $data = array();
        $data['Scope'] = $scope * 86400; //转化为以秒为单位

        $scope_model->editScope($data['Scope']);
        

        // 把PHP数组转成JSON字符串
        $json_string = json_encode($data);

        // 写入文件
        file_put_contents('scope/config.json', $json_string);
        return 0;
    }
}
