<?php
namespace app\convert\validate;

use think\Validate;

class WordPress extends Validate
{
    protected $rule = [
        ['db_host','require|min:1|max:255'],
        ['db_name','require|min:1|max:255'],
        ['db_user','require|min:1|max:255']
    ];
}