<?php
namespace app\admin\controller;

use app\common\model\Comment as CommentModel;

class Comment extends Base
{
    public function index($status=null){
        if(!empty($status)){
            $t = $status == 1 ? 1 : 0;
            $where      = ['status'   => $t];
            $comment    = CommentModel::where($where)
                ->order('id','desc')
                ->paginate(10);
            $this->assign('status',$status);
        }else{
            $comment    = CommentModel::where('id','>',0)
                ->order('id','desc')
                ->paginate(10);
            $this->assign('status','all');
        }

        $this->assign('comment_list',$comment);
        $this->assign('avatar','getGravatar');
        $this->assign('nav_cur','comment');
        return view();
    }

    public function edit($id){
        $id = intval($id);
        if($id < 1){
            $this->error('评论不存在');
        }
        $result = CommentModel::get($id);
        if($result === false){
            $this->error('评论不存在');
        }
        $this->assign('data',$result);
        return view();
    }

    public function update(){
        $data = input('post.');
        $id = intval($data['id']);
        $author = trim($data['author']);
        $email = trim($data['email']);
        $content = trim($data['content']);
        $url = trim($data['url']);
        if($id == 0 || empty($author) || empty($email) || empty($content)){
            $this->error('参数错误');
        }
        //修改评论
        $data = [
            'id'    => $id,
            'author' => $author,
            'email' => $email,
            'content' => $content,
            'url'    => $url
        ];
        $result = CommentModel::update($data);
        if($result){
            $this->success('修改成功',url('/admin/comment/index/'));
        }else{
            $this->error('修改失败');
        }

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
        $result = CommentModel::destroy($id);
        if(!$result){
            $json['err']    = 2;
        }
        return $json;
    }

    protected function apiUpdate($json,$data){
        $id     = intval($data['id']);
        $status = intval($data['status']);
        if($id == 0){
            $json['err']    = 1;
            return $json;
        }
        $data   = [
            'id'        => $id,
            'status'    => $status
        ];
        $result = CommentModel::update($data);
        if(!$result){
            $json['err']    = 2;
        }
        return $json;
    }
}