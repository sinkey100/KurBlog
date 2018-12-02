<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/11
 * Time: 11:24
 */

namespace app\index\controller;

use app\common\model\Article as ArticleModel;
use app\common\model\Category as CategoryModel;
use app\common\model\Comment as CommentModel;

class Article extends Base
{
    public function index($param='') {
        if (empty($param)) {
            $this->error('参数错误');
            return false;
        }
        if (is_numeric($param)) {
            $res = ArticleModel::get($param);
        } else {
            $res = ArticleModel::get(['slug' => $param]);
        }
        $res->views++;
        $res->save();
        $category = CategoryModel::get($res['category_id']);
        $res = $this->processing([$res],[$category]);
        $res = $res[0];
        $this->assign('data',$res);
        $this->assign('comment',[
            'type'      => 'article',
            'value'     => $res['id'],
            'html'      => CommentModel::getComment('article',$res['id'],$this->_K['setting']['order_by_time'])
        ]);
        //SEO信息;
        $this->seo_replace['{title}'] = $res['title'];
        $this->seo_replace['{category_name}'] = $category['title'];
        $this->seo_replace['{description}'] = $res['description'];
        $this->setSeoData('article');
        return $this->view->fetch('/article');
    }

    public function search(){
        $data   = input('get.');
        $data['page'] = empty($data['page'] ) ? 1 : intval($data['page']);
        $kw     = $data['kw'];
        if(!$kw){
            $this->error('请先输入关键词');
            return false;
        }
        $where      = [
            'title'     => ['like','%'.$kw.'%'],
            'status'    => 1
        ];
        $this->assign('title','搜索');
        $category = CategoryModel::all();
        //获取数据
        $res    = ArticleModel::where($where)
            ->order('id','desc')
            ->paginate($this->_K['setting']['per_page'],true,[
                'page'     => $data['page'],
                'path'     => 'search?kw='.$kw.'&page='.'[PAGE]'
            ]);
        //数据加工
        $res = $this->processing($res,$category);
        $this->assign('data',$res);
        $this->assign('title','搜索');
        return $this->view->fetch('/index');
    }
}