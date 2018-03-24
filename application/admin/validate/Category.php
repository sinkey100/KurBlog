<?php
namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        ['title','require|min:1|max:255'],
        ['slug','require|/^[0-9a-zA-Z\-]*$/|min:1|max:50']
    ];
}