<?php


namespace app\common\validate;


use think\Validate;

class ScheduleDefault extends Validate
{
    protected $rule=[
        'time'=>'require',
        'place'=>'require|length:1,30',
        'item'=>'require|length:1,30'
    ];
}