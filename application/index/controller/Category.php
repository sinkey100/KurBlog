<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/11
 * Time: 11:14
 */

namespace app\index\controller;

use app\common\model\Category as CategoryModel;
use app\common\model\Article as ArticleModel;

class Category extends Base
{
    public function index($param = NULL, $page = 1) {
        //获取分类信息
        $where = ['status' => 1];
        if ($param == NULL) {
            $category = CategoryModel::all();
            $path = url('index/index/index');
        } else {
            $category = CategoryModel::get(is_numeric($param) ? $param : ['slug' => $param]);
            $this->assign('title',$category['title']);
            $where['category_id'] = $category['id'];
            $category = [$category];
            $path = url('index/category/index', ['param' => $param]);
        }
        //加载文章数据
        $res = ArticleModel::where($where)
            ->order('id', 'desc')
            ->paginate($this->_K['setting']['per_page'], true, [
                'page' => $page,
                'path' => $path . '/[PAGE]'
            ]);
        //数据加工
        $res = $this->processing($res,$category);
        $this->assign('data',$res);
        return $this->view->fetch('/index');
    }
}