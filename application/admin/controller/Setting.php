<?php
namespace app\admin\controller;

use app\common\model\Setting as SettingModel;
use app\common\model\Member as MemberModel;
use think\Session;

class Setting extends Base
{

    private $class = [
        'common'    => '常规',
        'profile'   => '个人',
        'home'      => '前台',
        'comment'   => '评论',
        'mail'      => '邮件设置',
        'rewrite'   => '固定链接'
    ];

    public function index(){
        return $this->common();
    }

    public function theme(){
        //读取当前主题ID
        $active = SettingModel::get('theme')['value'];
        //获取所有主题
        $path   = './theme/';
        $data   = enumerateFiles($path);
        $theme  = [];
        foreach($data as $v){
            $config     = $path.$v.DS.'config.php';
            if(is_dir($path.$v) && file_exists($config)){
                $config     = include($config);
                $theme[]    = [
                    'theme_name'    => $config['theme_name'],
                    'author'        => $config['author'],
                    'author_url'    => $config['author_url'],
                    'description'   => $config['description'],
                    'version'       => $config['version'],
                    'path'          => str_replace('/',DS,'/theme/').$v,
                    'id'            => $v,
                    'active'        => $active == $v ? true : false,
                    'thumb'         => str_replace('/',DS,'/theme/').$v.DS.'screenshot.png',
                ];
            }
        }
        $this->assign('data',$theme);
        $this->assign('nav_cur','theme');
        return view();
    }

    public function activate_theme(){
        $data   = input('post.');
        $id     = @$data['id'];
        if(empty($id)){
            $this->error('参数错误');
            return false;
        }
        $path   = './theme/';
        if(!file_exists($path.$id.DS.'config.php')){
            $this->error('主题配置错误');
            return false;
        }
        $data = [
            'key'   => 'theme',
            'value' => $id
        ];
        $result = SettingModel::update($data);
        if(!$result){
            $this->error('主题启用失败');
            return false;
        }
        $this->success('主题启用成功');
        return true;
    }

    public function delete_theme(){
        $data   = input('post.');
        $id     = @$data['id'];
        if(empty($id)){
            $this->error('参数错误');
            return false;
        }
        $path   = './theme/'.$id.'/';
        if(!is_dir($path)){
            $this->error('主题目录不存在');
            return false;
        }
        $result = $this->delDirAndFile($path);
        if(!$result){
            $this->error('删除失败');
            return false;
        }
        $this->success('删除成功');

    }

    public function common(){
        //读取该类下的设置
        $data = $this->getData(['blog_name', 'sub_title', 'siteurl', 'admin_email', 'icp_num']);
        $this->assign('data',$data);
        $this->assign('action','common');
        return $this->view();
    }

    public function profile(){
        //根据UID读取当前用户信息
        $uid = Session::get('member.uid');
        $data = MemberModel::get($uid);
        $this->assign('data',$data);
        $this->assign('action','profile');
        return $this->view();
    }

    public function comment(){
        $data = $this->getData(['order_by_time', 'need_review','comment_mail']);
        $this->assign('data',$data);
        $this->assign('action','comment');
        return $this->view();
    }

    public function rewrite(){
        $data = $this->getData(['rewrite']);
        $this->assign('data',$data);
        $this->assign('action','rewrite');
        return $this->view();
    }

    public function home(){
        $data = $this->getData(['home','per_page','description_length']);
        $data = [
            'home'      => json_decode($data['home'],true),
            'per_page'  => $data['per_page'],
            'description_length'    => $data['description_length']
        ];
        $this->assign('data',$data);
        $this->assign('action','home');
        return $this->view();
    }

    public function update(){
        $data = input('post.');
        $action = $data['action'];
        if($action == 'home'){
            //特殊处理首页设置
            $home = [
                'type'  => $data['home'],
                'value' => $data['home']=='article' ? '' : intval($data['value'])
            ];
            $home   = json_encode($home);
            $data   = [
                'home'                  => $home,
                'per_page'              => $data['per_page'],
                'description_length'    => $data['description_length']
            ];
            $data = $this->setData($data);
            $setting = new SettingModel;
            $result = $setting->saveAll($data);
        }else if($action == 'profile'){
            //特殊处理个人信息
            $result = $this->validate($data,'Member');
            if (true !== $result) {
                $this->error('提交失败');
                return false;
            }
            $uid    = Session::get('member.uid');
            $member   = [
                'id'        => $uid,
                'username'  => $data['username'],
                'email'     => $data['email']
            ];
            if(!empty($data['password'])){
                $member['password'] = md5(md5($data['password']).$data['password']);
            }
            $result = MemberModel::update($member);
            if($result && !empty($data['password'])){
                $this->redirect('/admin/login/logout/');
                return true;
            }
        }else{
            $data = $this->setData($data);
            $setting = new SettingModel;
            $result = $setting->saveAll($data);
        }
        if(!$result){
            $this->error('提交失败');
            return false;
        }else{
            $this->redirect('/admin/setting/'.$action);
        }
    }

    public function mail(){
        $data = $this->getData(['smtp_mail','smtp_server','smtp_port','smtp_password','smtp_ssl']);
        $this->assign('data',$data);
        $this->assign('action','mail');
        return $this->view();
    }



    /**-------------------- Protected ---------------------**/

    protected function getData($param=[]){
        if(count($param) == 0){
            return false;
        }
        $data = SettingModel::all(
            function($query) use ($param){
                $query->where('key','in',$param);
            }
        );
        //整理数据
        $settings   = [];
        foreach($data as $v){
            $settings[$v['key']]    = $v['value'];
        }
        return $settings;
    }

    protected function setData($param=[]){
        if(count($param) == 0){
            return false;
        }
        $data = [];
        foreach($param as $k=>$v){
            $data[] = [
                'key'   => $k,
                'value' => $v
            ];
        }
        return $data;
    }

    protected function view(){
        $this->assign('class',$this->class);
        $this->assign('nav_cur','setting');
        return view('setting');
    }

    /**-------------------- Private ---------------------**/
    /**
     * 删除目录及目录下所有文件或删除指定文件
     * @param $path
     * @return bool
     */
    private function delDirAndFile($path) {
        $handle = opendir($path);
        if ($handle) {
            while (false !== ( $item = readdir($handle) )) {
                if ($item != "." && $item != "..")
                    is_dir("$path/$item") ? $this->delDirAndFile("$path/$item") : unlink("$path/$item");
            }
            closedir($handle);
            return rmdir($path);
        }else {
            if (file_exists($path)) {
                return unlink($path);
            } else {
                return false;
            }
        }
    }



}