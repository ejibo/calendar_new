<?php


namespace app\manageconfig\validate;

use think\Validate;

class ScheduleDefault extends Validate
{
    protected $rule=[
        'day'=>'require|int|between:1,7',
        'time'=>'require',
        'place'=>'require|length:1,30',
        'item'=>'require|length:1,30',
        'note'=>'max:2000'
    ];
}