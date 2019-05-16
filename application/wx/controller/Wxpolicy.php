<?php
/**
 * @Author: gunjianpan
 * @Date:   2019-04-18 11:25:17
 * @Last Modified by:   gunjianpan
 * @Last Modified time: 2019-05-16 11:11:16
 */

namespace app\wx\controller;

use app\wx\common\Common;
use app\logmanage\model\Log as LogModel;


class Wxpolicy extends Common
{
    public function getPolicy(){
        $provisionFile = fopen("provisions.txt", "r") or die("Unable to open file!");
        $last = fread($provisionFile, filesize("provisions.txt"));
        fclose($provisionFile);
        return json(['data' => $last, 'code' => 20010]);
    }
}