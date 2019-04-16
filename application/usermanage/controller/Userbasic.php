<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:34
 */

namespace app\usermanage\controller;


use app\common\controller\Common;

class Userbasic extends Common
{
    public function index(){
        $userbasic = model('Userbasic');
        $info = $userbasic->getinfo();
        foreach($info as $key=>$value){
            if ($info[$key]['type_id'] == 0){
                $info[$key]['type'] = '普通用户';
            }elseif ($info[$key]['type_id'] == 1){
                $info[$key]['type'] = '院领导';
            }elseif ($info[$key]['type_id'] == 2){
                $info[$key]['type'] = '部门领导';
            }elseif ($info[$key]['type_id'] == 3){
                $info[$key]['type'] = '系领导';
            }
        }
        $this->assign('info',$info);

        //添加人员模态框传值
        $depart = $userbasic->getdepart();
        $this->assign('depart',$depart);
        $position = $userbasic->getposition();
        $this->assign('position',$position);
        return $this->fetch();
    }

    public function edituserinfo()
    {
        $userbasic = model('Userbasic');
        $data = input('post.');
        $is_add = $userbasic->edituserinfo($data);
        if ($is_add) {
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！');
        }
    }
}