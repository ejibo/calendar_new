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

    /**
     * 添加用户
     * 获取到前端传递的用户数据(可缺省为空属性待讨论)
     * 通过学号或工号(work_id)判断待添加用户是否存在
     * 查询到work_id且is_delete为0(未被删除)认为用户存在，提示信息
     * 否则用户不存在，添加用户(对于被删除用户，不选择恢复数据，而是重新创建)
     */
    public function addUser()
    {
        $userbasic = model("Userbasic");
        $data = input('post.');
        $workId = $data['work_id'];
        $user = $userbasic->findUserByWorkId($workId);
        if ($user != null) {
            $this->error("该用户已被添加，不可重复添加");
        }
        $addFlag = $userbasic->insertUser($data);
        if ($addFlag) {
            $this->success('添加成功');
        } else {
            $this->error('添加失败');
        }
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * Excel批量添加用户
     * 获取到前端传递的包含用户信息的Excel文件(文件格式将会在前端提示，内部数据合法性暂不处理)
     * 对于每一行获取的用户信息进行用户存在性判断
     * 不存在的用户会被记录，最后批量添加
     */
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

        $i = 0;

        $userbasic = model("Userbasic");

        foreach ($sheet->getRowIterator(2) as $row) {
            $tmp = array();
            foreach ($row->getCellIterator() as $cell) {
                $tmp[] = $cell->getFormattedValue();
            }
            // 未被添加的用户信息才会被记录到数组里，最后批量添加
            if ($userbasic->findUserByWorkId($tmp[1]) == null) {
                $tmp = ['name' => $tmp[0],
                    'work_id' => $tmp[1],
                    'type_id' => $tmp[2],
                    'depart_id' => $tmp[3],
                    'position_id' => $tmp[4]];
                $sqlData[$i++] = $tmp;
            }
        }

        $addFlag = $userbasic->insertAllUser($sqlData);
        if ($addFlag) {
            $this->success('批量添加成功，重复用户信息已自动过滤未添加');
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