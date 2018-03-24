<?php
namespace app\index\validate;

use think\Validate;

class Comment extends Validate
{
    protected $rule = [
        ['type','require|min:1|max:10'],
        ['value','require|number'],
        ['author','require|min:1|max:100'],
        ['email','require|email'],
        ['url','url'],
        ['content','require|min:1']
    ];
}