<?php
namespace app\admin\validate;

use think\Validate;

class Link extends Validate
{
    protected $rule = [
        ['title','require|min:1|max:255'],
        ['url','require|url|max:255'],
        ['description','min:0|max:300'],
        ['weight','number']
    ];
}