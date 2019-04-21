<?php
/**
 * Date: 2019/4/20
 * Time: 23:34
 * Author: yang kang
 */

namespace app\querystatistics\controller;
use app\common\controller\Common;

use app\querystatistics\model\ScheduleSearch as SchedulModel;
use think\controller;
use think\Db;
use think\Request;

class Query extends Common{
            
	// public function index()
	// {
	// 	$this->assign();
	// 	return $this->fetch();
    // }

    public function index(){
        $schedul_model = new SchedulModel();
        $info = $schedul_model->searchAllInfo();
        $this->assign('info',$info);
        return $this->fetch();
    }
}
