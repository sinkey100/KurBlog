<?php
namespace app\admin\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        ['username','require|min:4|max:20'],
        ['password','require']
    ];


}