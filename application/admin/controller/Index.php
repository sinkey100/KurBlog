<?php
namespace app\admin\controller;

use think\Db;
use app\common\model\Article as ArticleModel;
use app\common\model\Page as PageModel;
use app\common\model\Comment as CommentModel;


class Index extends Base
{

    public function index(){
        //数据库大小
        $dbSize = 0;
        $tables = Db::query("SHOW TABLE STATUS");
        foreach($tables as $table) {
            $dbSize += $table['Data_length'] + $table['Index_length'];
        }
        $dbSize = $dbSize ? round($dbSize/1024/1024,2).' MB' : 'null';
        //系统信息
        $serverInfo     = PHP_OS.' / PHP v'.PHP_VERSION;
        $serverInfo    .= @ini_get('safe_mode') ? ' Safe Mode' : NULL;
        $serverSoft     = $_SERVER['SERVER_SOFTWARE'];
        $fileUpload     = ini_get('upload_max_filesize');
        $dbVersion      = Db::query("SELECT VERSION()");
        $dbVersion      = $dbVersion[0]['VERSION()'];
        $system_data = [
            'KurBlog'       => '1.0',
            'ThinkPHP'      => THINK_VERSION,
            'serverInfo'    => $serverInfo,
            'fileUpload'    => $fileUpload,
            'serverSoft'    => $serverSoft,
            'dbVersion'     => $dbVersion,
            'dbSize'        => $dbSize,
        ];
        //取月初时间戳
        $monthTime  = date("Y-m");
        $monthTime  = $monthTime.'-01';
        $monthTime  = strtotime($monthTime);
        //取文章数据
        $monthNum   = ArticleModel::where('create_time>'.$monthTime)->count();
        $allNum     = ArticleModel::count();
        $data['article']    = [
            'monthNum'  => $monthNum,
            'allNum'    => $allNum
        ];
        //取页面数据
        $allNum     = PageModel::count();
        $data['page']['allNum']    = $allNum;
        //取评论数据
        $monthNum   = CommentModel::where('time>'.$monthTime)->count();
        $allNum     = CommentModel::count();
        $data['comment']    = [
            'monthNum'  => $monthNum,
            'allNum'    => $allNum
        ];
        //获取KurBlog的数据推送
        $param  = json_encode($system_data);
        $res = requestByCurl('http://www.kurblog.com/api/service/notice', $param, 'post', '', [], 86400);
        $res = json_decode($res['content'],true);
        $this->assign('notice',$res['err'] === 0 ? $res['data'] : []);
        $this->assign('data',$data);
        $this->assign('system_data',$system_data);
        return view();
    }



}