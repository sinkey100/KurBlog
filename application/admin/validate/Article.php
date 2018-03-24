<?php
namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        ['title','require|min:1|max:255'],
        ['slug','/^[0-9a-zA-Z\-]*$/|min:1|max:50'],
        ['status','number'],
        ['allow_comment','number'],
        ['category_id','number'],
        ['create_time','date'],
        ['update_time','date']
    ];
}