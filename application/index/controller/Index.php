<?php
namespace app\index\controller;

use app\common\model\Common;

class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }
}
