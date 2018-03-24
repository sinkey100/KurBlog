<?php
namespace app\admin\controller;

use app\common\model\Link as LinkModel;

class Link extends Base
{
    public function index(){
        //获取所有链接列表
        $list   = LinkModel::all(
            function($query){
                $query->order('weight','asc');
            }
        );
        $this->assign('nav_cur','link');
        $this->assign('list',$list);
        return view();
    }

    public function update(){
        $data   = input('post.');
        $id     = $data['id'];
        $result = $this->validate($data,'Link');
        if (true !== $result) {
            $this->error('提交失败');
            return false;
        }
        $data = [
            'title'         => $data['title'],
            'url'           => $data['url'],
            'description'   => $data['description'],
            'weight'        => 0
        ];
        if($id == 0){
            $result = LinkModel::create($data);
        }else{
            $data['id'] = $id;
            $result = LinkModel::update($data);
        }
        if(!$result){
            $this->error('提交失败');
            return false;
        }
        $this->redirect('/admin/link');
    }

    public function order(){
        $data = input('post.');
        $list = [];
        foreach($data['weight'] as $k=>$v){
            $list[] = [
                'id'     => intval($k),
                'weight' => intval($v)
            ];
        }
        $Link   = new LinkModel;
        $result = $Link->saveAll($list);
        if(!$result){
            $this->error('更新排序失败');
            return false;
        }
        $this->redirect('/admin/link');
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
        LinkModel::destroy($id);
        return $json;
    }
}