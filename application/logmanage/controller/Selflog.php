<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:47
 */

namespace app\logmanage\controller;


use app\common\controller\Common;
use app\logmanage\model\Log as LogModel;


class Selflog extends Common
{
    public function index(){
        dump(request());
        $model = new LogModel();
        $model1 = new LogModel();

        $uid = 1;
        $uid1 = 2;
        //$agent = Request::instance()->header('user-agent');
        // $ip = Request()->ip();

        $model->addMangerLog($uid, 1);
        $model->addMangerLog($uid, 2, 't增加', ['11']);
        $model->addMangerLog($uid, 3, 't修改', 'aa_'.'bb', 'faa_'.'fbb', 'taa_'.'tbb');

        $model1->addWechatLog($uid1, 1);
        $model1->addWechatLog($uid1, 2, 't增加', ['11']);
        $model1->addWechatLog($uid1, 3, 't修改', 'aa_'.'bb', 'faa_'.'fbb', 'taa_'.'tbb');

        dump("__________________");

        dump($model1->getLogByUid(1111));
        dump($model1-> getAllUserLog());
        dump($model1-> getAllManagerLog());
    
        $number = "测试======<br /> 学号 姓名 用户类型 部门 职位 注册日期 最后更新";
        $this->assign('number', $number);
        return $this->fetch();
    }


    public function testAdd(){

        dump(request());
        $model = new LogModel();
        $model1 = new LogModel();

        $uid = 1;
        $uid1 = 2;
        //$agent = Request::instance()->header('user-agent');
        // $ip = Request()->ip();

        $model->addMangerLog($uid, 1);
        $model->addMangerLog($uid, 2, 't增加', ['11']);
        $model->addMangerLog($uid, 3, 't修改', 'aa_'.'bb', 'faa_'.'fbb', 'taa_'.'tbb');

        $model1->addWechatLog($uid1, 1);
        $model1->addWechatLog($uid1, 2, 't增加', ['11']);
        $model1->addWechatLog($uid1, 3, 't修改', 'aa_'.'bb', 'faa_'.'fbb', 'taa_'.'tbb');

        dump("__________________");

        dump($model1->getLogByUid(1111));
        dump($model1-> getAllUserLog());
        dump($model1-> getAllManagerLog());
    }
}