<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:41
 */

namespace app\adminmanage\controller;

use think\Config;
use app\adminmanage\model\ManageAuthGroup as ManageAuthGroupModel;

use app\common\controller\Common;

class Role extends Common
{
    public function index()
    {
        $resp['auth_group_list'] = ManageAuthGroupModel::paginate(5);
        $resp['status_list'] = Config::get('STATUS');
        $this->assign('resp', $resp);
        return $this->fetch('index');
    }

    /**
     * 编辑用户组
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function edit()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if (!empty($data['rules'])) {
                $data['rules'] = implode(',', $data['rules']);
            } else {
                $data['rules'] = '';
            }
            // check status switch
            $status = isset($_POST['status']);
            if ($status == true) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }

            $save = db('manage_auth_group')->update($data);
            if ($save !== false) {
                $this->success('修改用戶組成功', 'index');
            } else {
                $this->error('修改用戶組失敗');
            }
        }
        //query the group needed edit
        $auth_rule = new \app\adminmanage\model\ManageAuthRule();
        $auth_rule_list = $auth_rule->authRuleTree();
        $this->assign('auth_rule_list', $auth_rule_list);
        $auth_group = db('manage_auth_group')->find(input('id'));
        $this->assign('auth_group', $auth_group);

        return $this->fetch('edit');
    }

    /**
     * 增加用户组
     * @return mixed|void
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if ($data['rules']) {
                $data['rules'] = implode(',', $data['rules']);
            }
            // check status switch
            $status = isset($_POST['status']);
            if ($status == true) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }


            $add = db('manage_auth_group')->insert($data);
            if ($add) {
                $this->success('添加用戶組成功', 'index');
            } else {
                $this->error('添加用戶組失敗');
            }
            return;
        }


        //get methods
        $auth_rule = new \app\adminmanage\model\ManageAuthRule();
        $auth_rule_list = $auth_rule->authRuleTree();
        $this->assign('auth_rule_list', $auth_rule_list);
        return $this->fetch('add');
    }


    /**
     * 删除用户组
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        $del = db('manage_auth_group')->delete(input('id'));
        if ($del) {
            $this->success('刪除用戶組成功', 'index');
        } else {
            $this->error('刪除用戶組失敗');
        }
    }
}