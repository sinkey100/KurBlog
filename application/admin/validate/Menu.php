<?php
namespace app\admin\validate;

use think\Validate;

class Menu extends Validate
{
    protected $rule = [
        ['title','require|min:1|max:255'],
        ['parent_id','number'],
        ['type','require|min:2|max:10'],
        ['value','min:1|max:100']
    ];
}