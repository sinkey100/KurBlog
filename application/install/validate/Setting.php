<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/12
 * Time: 15:08
 */

namespace app\install\validate;

use think\Validate;

class Setting extends Validate
{
    protected $rule = [
        ['title','require|min:1|max:255'],
        ['email','email'],
        ['username','require|min:1|max:255'],
        ['password','require|min:1|max:255']
    ];
}