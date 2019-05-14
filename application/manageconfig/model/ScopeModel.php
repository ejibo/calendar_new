<?php

namespace app\manageconfig\model;

use think\Model;
use think\Db;
use app\logmanage\model\Log as LogModel;

class ScopeModel extends Model
{
    protected $table = 'global_config';
    //初始化日程范围配置
    public function initScope()
    {
        # code...
        $model = new LogModel();
        $uid = ADMIN_ID;
        $type = 2;
        $table = 'global_config';
        
        $default = ['config_item' => 'scope', 'parameter' => 2592000];
        $exi = DB::table('global_config')->where('config_item', 'scope')->count();
        if ($exi > 0) {
            $init_success = 1;
        } else {
            $init_success = DB::table('global_config')->insert($default);
            $id = DB::table('global_config')->getLastInsID();
            $field = [$id];
            $model->recordLogApi($uid,$type,$table,$field);
         }

        return $init_success; //成功返回1
    }
    //读取日程范围配置
    public function getScope()
    {
        # code...
        $scope = DB::table('global_config')->where('config_item', 'scope')->find();
        return $scope;
    }
    //修改日程范围配置
    public function editScope($parameter)
    {
        # code...
        $edit_success = DB::table('global_config')->where('config_item', 'scope')->update(['parameter' => $parameter]);
        return $edit_success; //成功返回影响的条数（设定应该成功为1）
    }
}
