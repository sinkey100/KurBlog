<?php

namespace app\admin\controller;

use think\Controller;
use think\Session;
use app\common\model\Setting as SettingModel;

class Base extends Controller
{

    public function _initialize() {
        checkInstall();
        $flag = Session::get('member.uid');
        if (!$flag) {
            $this->redirect('/admin/login');
            return false;
        }
        //读取设置
        $data = SettingModel::all();
        $setting = [];
        foreach ($data as $v) {
            $setting[$v['key']] = $v['value'];
        }
        $this->assign('setting', $setting);
        $this->assign('navBar',$this->navBar());
    }

    public function uploadFile() {
        $json = [
            'err' => 0,
            'action' => 'uploadFile',
        ];
        $filePath = '/static/upload/';
        $uploadPath = './' . str_replace('/', DS,  $filePath);
        $file = request()->file('image');
        if (empty($file)) {
            $json['err'] = 1;
            $json['message'] = '请先选择文件';
            return json_encode($json);
        }
        $info = $file->validate(['ext' => 'jpg,png,gif'])->move($uploadPath);
        if (!$info) {
            $json['err'] = 2;
            $json['message'] = $file->getError();
        } else {
            //上传成功
            $json['path'] = $filePath . str_replace('\\', '/', $info->getSaveName());
        }
        return json_encode($json);
    }

    public function navBar() {
        return [
            [
                ['home', '/admin', '仪表盘']
            ], [
                ['article', '/admin/article/', '所有文章'],
                ['write', '/admin/article/write/', '写文章'],
                ['category', '/admin/category/', '文章分类']
            ], [
                ['page', '/admin/page/', '页面管理']
            ], [
                ['theme', '/admin/setting/theme', '主题'],
                ['menu', '/admin/menu/', '菜单']
            ], [
                ['comment', '/admin/comment/', '评论'],
                ['link', '/admin/link/', '链接']
            ], [
                ['setting', '/admin/setting/', '设置']
            ],
        ];
    }
}