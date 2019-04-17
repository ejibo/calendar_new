<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:42
 */

namespace app\adminmanage\controller;

use app\adminmanage\model\ManageAuthRule as ManageAuthRuleModel;


use app\common\controller\Common;

class Power extends Common
{
    public function index()
    {
        $auth_rule = new ManageAuthRuleModel();
        if (request()->isPost()) {
            $sorts = input('post.');

            foreach ($sorts as $k => $v) {
                $auth_rule->update(['id' => $k, 'sort' => $v]);
            }
            $this->success('更新排序成功', 'index');
            return;
        }
        $auth_rule_list = $auth_rule->authRuleTree();
        $this->assign('auth_rule_list', $auth_rule_list);
        return $this->fetch('index');
    }

    /**
     * 增加用户组权限
     * @return mixed|void
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $pre_level = db('manage_auth_rule')->where('id', $data['pid'])->field('level')->find();
            if ($pre_level) {
                $data['level'] = $pre_level['level'] + 1;
            }

            $add = db('manage_auth_rule')->insert($data);
            if ($add) {
                $this->success("添加權限成功", 'index');
            } else {
                $this->error("添加權限失敗");
            }

            return;
        }
        $auth_rule = new ManageAuthRuleModel();
        $auth_rule_list = $auth_rule->authRuleTree();
        $this->assign('auth_rule_list', $auth_rule_list);
        return $this->fetch('add');
    }

    /**
     * 编辑用户组权限
     * @return mixed|void
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
            $pre_level = db('manage_auth_rule')->where('id', $data['pid'])->field('level')->find();
            if ($pre_level) {
                $data['level'] = $pre_level['level'] + 1;
            } else {
                $data['level'] = 0;
            }
            $save = db('manage_auth_rule')->update($data);
            if ($save !== false) {
                $this->success('修改權限成功', 'index');
            } else {
                $this->error('修改權限失敗');
            }
            return;
        }

        $auth_rule = new ManageAuthRuleModel();
        $auth_rule_list = $auth_rule->authRuleTree();
        $auth_rule = $auth_rule->find(input('id'));
        $this->assign(
            array(
                'auth_rule_list' => $auth_rule_list,
                'auth_rule' => $auth_rule
            )
        );
        return $this->fetch('edit');
    }

    /**
     * 删除用户组权限
     */
    public function del()
    {
        $auth_rule = new ManageAuthRuleModel();
        $authRuleIds = $auth_rule->getchilrenid(input('id'));
        $authRuleIds[] = input('id');
        $del = ManageAuthRuleModel::destroy($authRuleIds);
        if ($del) {
            $this->success('刪除權限成功');
        } else {
            $this->error('刪除權限失敗');
        }
    }

}