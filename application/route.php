<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//首页文章列表分页
Route::rule('index/[:page]','index/index/index');
//分类文章列表分页
Route::rule('category/[:param]/[:page]','index/category/index');
//文章页
Route::rule('post/[:param]','index/article/index');
//页面
Route::rule('page/[:param]','index/page/index');
//搜索
Route::rule('search/[:param]','index/article/search');
//评论
Route::rule('comment/api','index/comment/api');
