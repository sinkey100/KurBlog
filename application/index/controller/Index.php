<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/10
 * Time: 16:35
 */

namespace app\index\controller;

class Index extends Base
{
    public function index($page = 1) {
        $home = json_decode($this->_K['setting']['home']);
        if ($home->type == 'category') {
            //首页显示一个分类
            $class = new Category;
            return $class->index($home->value);
        } else if ($home->type == 'page') {
            //首页展示一个页面
            $class = new Page;
            return $class->index($home->value);
        } else {
            //首页显示最新文章
            $class = new Category;
            return $class->index(NULL,$page);
        }
    }
}