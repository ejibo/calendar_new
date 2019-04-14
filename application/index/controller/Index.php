<?php
namespace app\index\controller;

use app\common\controller\Common;

class Index extends Common
{
    public function index()
    {
        $username = '';
        return $this->fetch();
    }
}
