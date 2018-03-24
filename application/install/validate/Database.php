<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/12
 * Time: 14:56
 */
namespace app\install\validate;

use think\Validate;

class Database extends Validate
{
    protected $rule = [
        ['db_host','require|min:1|max:255'],
        ['db_name','require|min:1|max:255'],
        ['db_user','require|min:1|max:255']
    ];
}