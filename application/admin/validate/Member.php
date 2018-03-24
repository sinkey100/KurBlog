<?php
namespace app\admin\validate;

use think\Validate;

class Member extends Validate
{
    protected $rule = [
        ['username','require|min:4|max:20'],
        ['password','min:6'],
        ['email','email']
    ];
}