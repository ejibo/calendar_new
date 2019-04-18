<?php
/**
 * Created by PhpStorm.
 * User: 84333
 * Date: 2019/4/14
 * Time: 0:34
 */

namespace app\usermanage\controller;


use app\common\controller\Common;
use think\Request;

//require 'vendor/autoload.php';

class Userbasic extends Common
{
    public function index()
    {
        $userbasic = model('Userbasic');
        $info = $userbasic->getinfo();
        foreach ($info as $key => $value) {
            if ($info[$key]['type_id'] == 0) {
                $info[$key]['type'] = '普通用户';
            } elseif ($info[$key]['type_id'] == 1) {
                $info[$key]['type'] = '院领导';
            } elseif ($info[$key]['type_id'] == 2) {
                $info[$key]['type'] = '部门领导';
            } elseif ($info[$key]['type_id'] == 3) {
                $info[$key]['type'] = '系领导';
            }
        }
        $this->assign('info', $info);

        //添加人员模态框传值
        $depart = $userbasic->getdepart();
        $this->assign('depart', $depart);
        $position = $userbasic->getposition();
        $this->assign('position', $position);
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

    public function addUser()
    {
        $userbasic = model("Userbasic");
        $data = input('post.');
        $addFlag = $userbasic->insertUser($data);
        if ($addFlag) {
            $this->success('添加成功');
        } else {
            $this->error('添加失败');
        }
    }

    public function batchAddByExcel()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        try {
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            die($e->getMessage());
        }

        $sheet = $spreadsheet->getActiveSheet();

        $sqlData = array();

        foreach ($sheet->getRowIterator(2) as $row) {
            $tmp = array();
            foreach ($row->getCellIterator() as $cell) {
                $tmp[] = $cell->getFormattedValue();
            }
            $tmp = ['name' => $tmp[0],
                'work_id' => $tmp[1],
                'type_id' => $tmp[2],
                'depart_id' => $tmp[3],
                'position_id' => $tmp[4]];
            $sqlData[$row->getRowIndex() - 2] = $tmp;
        }

        $userbasic = model("Userbasic");
        $addFlag = $userbasic->insertAllUser($sqlData);
        if ($addFlag) {
            $this->success('批量添加成功');
        } else {
            $this->error('添加失败');
        }
    }


    //删除单个用户
    public function deleteUser() {
        $data = Request::instance()->param('id');
        $userbasic = model("Userbasic");
        $deleteFlag = $userbasic->delwhitelist($data);
        if ($deleteFlag) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    public function batchDeleteUser() {
        $idArray = $_POST['list'];
        $userbasic = model("Userbasic");
        foreach ($idArray as $item) {
            $userbasic->delwhitelist($item);
        }
        return 'ok';
    }


}