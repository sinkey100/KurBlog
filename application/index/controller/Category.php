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

        //SEO信息;
        $this->seo_replace['{category_name}'] = $category[0]['title'] ? : '';
        $this->seo_replace['{page}'] = $page;
        $this->setSeoData($param == NULL ? 'index' : 'list');
        return $this->view->fetch('/index');
    }
}