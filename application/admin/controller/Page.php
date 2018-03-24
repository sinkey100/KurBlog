<?php
namespace app\admin\controller;

use app\common\model\Page as PageModel;
use app\common\model\Comment as CommentModel;
use app\common\model\Setting as SettingModel;
use SplFileObject;

class Page extends Base
{

    public function index(){
        //获取所有页面
        $list = PageModel::all();
        $commentNum = [];
        foreach ($list as $v){
            $num = CommentModel::where('value',$v['id'])
                ->where('type','page')
                ->count();
            $commentNum[$v['id']]  = $num;
        }
        $this->assign('nav_cur', 'page');
        $this->assign('commentNum', $commentNum);
        $this->assign('page_list', $list);
        return view();
    }

    public function write($id=0){
        $id     = $id > 0 ? intval($id) : 0;
        $title  = $id == 0 ? '新建页面' : '编辑页面';
        //获取页面模版列表
        $template = $this->getPageTemplate();
        if($id > 0){
            $data = PageModel::get($id);
        }
        $data   = [
            'title'         => $id > 0 ? $data['title'] : '',
            'slug'          => $id > 0 ? $data['slug'] : '',
            'status'        => $id > 0 ? $data['status'] : 1,
            'content'       => $id > 0 ? $data['content'] : '',
            'draft'         => $id > 0 ? $data['draft'] : '',
            'template'      => $id > 0 ? $data['template'] : '',
            'allow_comment' => $id > 0 ? $data['allow_comment'] : 1,
            'create_time'   => $id > 0 ? $data['create_time'] : '',
            'update_time'   => $id > 0 ? date("Y-m-d H:i:s",time()) : ''
        ];
        //参数绑定
        $this->assign('nav_cur', 'page');
        $this->assign('id', $id);
        $this->assign('template', $template);
        $this->assign('data', $data);
        $this->assign('title', $title);
        $this->assign('action', 'Page');
        return view('index/write');
    }

    public function update(){
        $data   = input('post.');
        $result = $this->validate($data,"Page");
        if(!$result){
            return false;
        }
        $id    = intval($data['id']);
        //日期为空 给当前时间
        $create_time    = empty($data['create_time']) ? time() : strtotime($data['create_time']);
        $update_time    = empty($data['update_time']) ? time() : strtotime($data['update_time']);
        $data    = [
            'title'         => $data['title'],
            'slug'          => $data['slug'],
            'status'        => intval($data['status']),
            'content'       => $data['content'],
            'draft'         => '',
            'template'      => $data['template'],
            'allow_comment' => @$data['allow_comment']=='on' ? 1 : 0,
            'create_time'   => $create_time,
            'update_time'   => $update_time,
        ];
        if($id == 0){
            $result = PageModel::create($data);
        }else{
            $data['id']     = $id;
            $result = PageModel::update($data);
        }
        if(!$result){
            $result->getError();
        }else{
            $this->redirect('/admin/Page');
            return true;
        }
    }

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


    /**-------------------- Protected ---------------------**/

    protected function getPageTemplate(){
        $theme = SettingModel::get('theme');
        $theme = $theme['value'];
        $path   = './theme/'.$theme.'/page/';
        $files  = enumerateFiles($path);
        $list   = [];
        if(count($files)==0){
            $list[] = [
                'file'  => 'Index.php',
                'id'    => 'Index',
                'name'  => '默认模板'
            ];
        }else{
            foreach($files as $fileName){
                $file = new SplFileObject($path.$fileName);
                $file->seek(1);
                $templateName   = getSubstr($file->current(),'---Name:','---*');
                $templateID     = substr($fileName,0,-4);
                $list[] = [
                    'file'  => $fileName,
                    'id'    => $templateID,
                    'name'  => $templateName=='' ? $fileName : $templateName
                ];
            }
        }

        return $list;
    }


    /**-------------------- API ---------------------**/

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
                'status'        => 0,
                'template'      => 'Index',
                'allow_comment' => 1,
                'create_time'   => time(),
                'update_time'   => time()
            ];
            $result = PageModel::create($data);
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
            $result = PageModel::update($data);
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
        $result = PageModel::destroy($id);
        if(!$result){
            $json['err']    = 2;
        }
        return $json;
    }

}