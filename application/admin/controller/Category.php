<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\Category as CategoryModel;

class Category extends Base
{
    public function index(){
        //获取所有分类
        $list = CategoryModel::all();
        $this->assign('nav_cur','category');
        $this->assign('category_list',$list);
        return view();
    }


    public function add(){
        $data   = input('post.');
        $result = $this->validate($data,'Category');
        if (true !== $result) {
            $this->error('提交失败');
            return false;
        }
        $data = [
            'title'    => @$data['title'],
            'slug'     => @$data['slug'],
        ];
        $result = CategoryModel::create($data);
        if(!$result){
            $this->error('添加失败');
            return false;
        }
        $this->redirect('/admin/category');
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

    protected function apiDelete($json,$data){
        $id = intval($data['id']);
        if($id == 0){
            $json['err']    = 1;
            return $json;
        }
        $result = CategoryModel::destroy($id);
        if(!$result){
            $json['err']    = 2;
        }
        return $json;
    }

    protected function apiUpdate($json,$data){
        $result = $this->validate($data,'Category');
        if (true !== $result) {
            $json['err']    = 1;
            return $json;
        }
        $data = [
            'id'    => intval($data['id']),
            'title' => trim($data['title']),
            'slug'  => trim($data['slug'])
        ];
        $result = CategoryModel::update($data);
        if(!$result){
            $json['err']    = 2;
        }
        return $json;
    }
}