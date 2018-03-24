<?php
namespace app\admin\controller;

use app\common\model\Article as ArticleModel;
use app\common\model\Category as CategoryModel;
use app\common\model\Comment as CommentModel;

class Article extends Base
{
    public function index(){
        //获取所有分类
        $category = $this->getCategory();
        //获取文章
        $list = ArticleModel::order('id','desc')->paginate(15);
        $commentNum = [];
        foreach ($list as $v){
            $num = CommentModel::where('value',$v['id'])
                               ->where('type','article')
                               ->count();
            $commentNum[$v['id']]  = $num;
        }
        $this->assign('nav_cur', 'article');
        $this->assign('category', $category);
        $this->assign('commentNum',$commentNum);
        $this->assign('article_list', $list);
        return view();
    }

    public function write($id=0){
        $id     = $id > 0 ? intval($id) : 0;
        $title = $id == 0 ? '写文章' : '编辑文章';
        //获取分类
        $category = $this->getCategory();
        if($id > 0){
            $data = ArticleModel::get($id);
        }

        $data   = [
            'title'         => $id > 0 ? $data['title'] : '',
            'slug'          => $id > 0 ? $data['slug'] : '',
            'status'        => $id > 0 ? $data['status'] : 1,
            'category_id'   => $id > 0 ? $data['category_id'] : 1,
            'content'       => $id > 0 ? $data['content'] : '',
            'draft'         => $id > 0 ? $data['draft'] : '',
            'description'   => $id > 0 ? $data['description'] : '',
            'password'      => $id > 0 ? $data['password'] : '',
            'thumb'         => $id > 0 ? $data['thumb'] : '',
            'allow_comment' => $id > 0 ? $data['allow_comment'] : 1,
            'create_time'   => $id > 0 ? $data['create_time'] : '',
            'update_time'   => $id > 0 ? date("Y-m-d H:i:s",time()) : ''
        ];
        //参数绑定
        $this->assign('nav_cur', 'write');
        $this->assign('id', $id);
        $this->assign('category', $category);
        $this->assign('data', $data);
        $this->assign('title', $title);
        $this->assign('action', 'Article');
        return view('index/write');
    }

    public function update(){
        $data   = input('post.');
        $result = $this->validate($data,"Article");
        if(!$result){
            return false;
        }
        $id    = intval($data['id']);
        //日期为空 给当前时间
        $create_time    = empty($data['create_time']) ? time() : strtotime($data['create_time']);
        $update_time    = empty($data['update_time']) ? time() : strtotime($data['update_time']);
        //status!=2 设置个毛密码
        $password       = $data['status']==2 ? $data['password'] : '';
        $data    = [
            'title'         => $data['title'],
            'slug'          => $data['slug'],
            'status'        => intval($data['status']),
            'category_id'   => $data['category_id'],
            'content'       => $data['content'],
            'allow_comment' => @$data['allow_comment']=='on' ? 1 : 0,
            'draft'         => '',
            'description'   => $data['description'],
            'password'      => $password,
            'thumb'         => $data['thumb'],
            'create_time'   => $create_time,
            'update_time'   => $update_time,
        ];
        if($id == 0){
            $result = ArticleModel::create($data);
        }else{
            $data['id']     = $id;
            $result = ArticleModel::update($data);
        }
        if(!$result){
            $result->getError();
        }else{
            $this->redirect('/admin/Article');
            return true;
        }
    }


    /**-------------------- Protected ---------------------**/

    protected function getCategory(){
        $list = CategoryModel::all();
        $category = [];
        foreach($list as $v){
            $category[$v['id']] = $v['title'];
        }
        return $category;
    }


    /**-------------------- API ---------------------**/

    public function api(){
        $json           = [];
        $json['err']    = 0;
        $data = input('post.');
        $action = 'api'.ucfirst(@$data['action']);
        if($action=='api'){
            $json['err']    = -1;
        }else if(!method_exists($this, $action)){
            $json['err']    = -2;
        }else{
            $json = $this->$action($json,$data);
        }
        return json_encode($json);
    }

    protected function apiAutoSave($json,$data){
        $content = $data['content'];
        if($content == ''){
            $json['err']    = 1;
            return $json;
        }
        $id = abs(intval($data['id']));
        if($id == 0){
            $data = [
                'title'         => empty($data['title']) ? '[无标题]' : trim($data['title']),
                'draft'         => $content,
                'category_id'   => 1,
                'status'        => 0,
                'allow_comment' => 1,
                'create_time'   => time(),
                'update_time'   => time()
            ];
            $result = ArticleModel::create($data);
            if(!$result){
                $json['err']    = 2;
            }else{
                $json['id']    = $result->id;
            }
        }else{
            $data = [
                'id'    => $id,
                'draft' => $content,
            ];
            $result = ArticleModel::update($data);
            if(!$result){
                $json['err']    = 2;
            }else{
                $json['id']    = $result->id;
            }
        }
        return $json;

    }

    protected function apiDelete($json,$data){
        $id = intval($data['id']);
        if($id == 0){
            $json['err']    = 1;
            return $json;
        }
        $result = ArticleModel::destroy($id);
        if(!$result){
            $json['err']    = 2;
        }
        return $json;
    }




}