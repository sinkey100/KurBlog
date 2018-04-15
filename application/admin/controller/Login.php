<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use app\common\model\Login as LoginModel;

class Login extends Controller
{
    public function Index(){
        //如已登录，跳回首页
        $flag = Session::get('member.uid');
        if($flag){
            $this->redirect('/admin');
            return false;
        }
        //获取form提交数据
        $data   = input('post.');
        $result = $this->validate($data,'Login');
        if($result === true){
            $user = LoginModel::get(
                ['username'=>$data['username']]
            );
            $password = $data['password'];
            if(@$user->password == md5(sha1($password))){
                Session::set('member.uid',$user['id']);
                Session::set('member.username',$user['username']);
                $this->redirect('/admin');
                return true;
            }
        }
        return view();
    }

    public function Logout(){
        Session::clear();
        $this->redirect('/admin');
    }
}