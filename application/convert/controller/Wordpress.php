<?php
namespace app\convert\controller;

use app\convert\model\WordPress as WP;
use app\convert\model\KurBlog as Kur;
use think\Session;

class Wordpress extends Base
{
    protected $db = null;

    public function index(){
        return view();
    }

    public function step_1(){
        //验证表单
        $data   = input('post.');
        $result = $this->validate($data,'WordPress');
        if(true !== $result){
            $this->error('请填写正确的数据库信息');
            return false;
        }
        //尝试连接数据库
        $db = WP::toConnect($data['db_host'],$data['db_user'],$data['db_password'],$data['db_name'],$data['db_prefix']);
        if(false == $db){
            $this->error('数据库连接失败。');
            return false;
        }
        //检查数据表是否正确
        $result = WP::checkPrefix($db);
        if(false == $result){
            $this->error('数据表前缀似乎不正确。');
            return false;
        }
        Session::set('convertWpDb',$data);
        return view();
    }

    public function step_2(){
        set_time_limit(0);
        //检查session
        $result = [
            'article'   => [
                'name'  => '文章',
                'count' => 0
            ],
            'page'      => [
                'name'  => '页面',
                'count' => 0
            ],
            'comment'   => [
                'name'  => '评论',
                'count' => 0
            ],
            'category'  => [
                'name'  => '分类',
                'count' => 0
            ],
        ];
        $dbInfo = Session::get('convertWpDb');
        if(!$dbInfo){
            $this->redirect(url('/convert'));
            return false;
        }
        //检查要转换的项目
        $action = input('post.')['action'];
        if(count($action) == 0){
            $this->error('请先选择需要转换的项目。');
            return false;
        }
        //连接数据库
        $db = WP::toConnect($dbInfo['db_host'],$dbInfo['db_user'],$dbInfo['db_password'],$dbInfo['db_name'],$dbInfo['db_prefix']);
        if(false == $db){
            $this->error('数据库连接失败。');
            return false;
        }
        //转换页面
        if(in_array('page',$action)){
            //获取WordPress页面列表
            $wpData = WP::getPage($db);
            //重建KurBlog数据表
            Kur::truncateTable('page');
            $kurData = [];
            foreach($wpData as $v){
                $kurData[] = [
                    'id'            => $v['ID'],
                    'title'         => $v['post_title'],
                    'slug'          => $v['post_name'],
                    'status'        => $v['post_status']=='publish' ? 1 : 0,
                    'content'       => $v['post_content'],
                    'draft'         => '',
                    'allow_comment' => $v['comment_status']=='publish' ? 1 : 0,
                    'create_time'   => strtotime($v['post_date']),
                    'update_time'   => strtotime($v['post_modified'])
                ];
            }
            $result['page']['count'] = Kur::merge('page',$kurData);
        }
        //转换分类
        if(in_array('category',$action)){
            //获取WordPress文章分类
            $wpData = WP::getCategory($db);
            //重建KurBlog数据表
            Kur::truncateTable('category');
            $kurData = [];
            foreach($wpData as $v){
                $kurData[] = [
                    'id'            => $v['term_id'],
                    'title'         => $v['name'],
                    'slug'          => $v['slug']
                ];
            }
            $result['category']['count'] = Kur::merge('category',$kurData);
        }
        //转换文章
        if(in_array('article',$action)){
            //获取WordPress文章
            $wpData         = WP::getArticle($db);
            //获取WordPress文章和分类对应关系
            $wpCategory     = WP::relationships($db);
            //获取KurBlog所有分类ID
            $kurCategory    = Kur::getCategoryIds();
            //重建KurBlog数据表
            Kur::truncateTable('article');
            $kurData = [];
            foreach($wpData as $v){
                $kurData[] = [
                    'id'                => $v['ID'],
                    'title'             => $v['post_title'],
                    'slug'              => substr($v['post_name'],0,99),
                    'status'            => $v['post_status']=='publish' ? 1 : 0,
                    'category_id'       => empty($wpCategory[$v['ID']]) || !in_array($wpCategory[$v['ID']],$kurCategory) ? 1 : $wpCategory[$v['ID']],
                    'content'           => $v['post_content'],
                    'draft'             => '',
                    'description'       => '',
                    'password'          => $v['post_password'],
                    'thumb'             => '',
                    'allow_comment'     => $v['comment_status']=='open' ? 1 : 0,
                    'create_time'       => strtotime($v['post_date']),
                    'update_time'       => strtotime($v['post_modified'])
                ];
            }
            $result['article']['count'] = Kur::merge('article',$kurData);
        }
        //转换评论
        if(in_array('comment',$action)){
            //获取WordPress评论
            $wpData     = WP::getComment($db);
            //获取KurBlog所有页面ID
            $kurPage    = Kur::getPageIds();
            //重建KurBlog数据表
            Kur::truncateTable('comment');
            $kurData = [];
            foreach($wpData as $v){
                $kurData[] = [
                    'id'            => $v['comment_ID'],
                    'parent_id'     => $v['comment_parent'],
                    'type'          => in_array($v['comment_ID'],$kurPage) ? 'page' : 'article',
                    'value'         => $v['comment_post_ID'],
                    'status'        => $v['comment_approved']==1 ? 1 : 0,
                    'author'        => $v['comment_author'],
                    'email'         => $v['comment_author_email'],
                    'url'           => $v['comment_author_url'],
                    'content'       => $v['comment_content'],
                    'time'          => strtotime($v['comment_date']),
                    'user_agent'    => $v['comment_agent'],
                    'ip'            => $v['comment_author_IP'],
                ];
            }
            $result['comment']['count'] = Kur::merge('comment',$kurData);
        }
        $this->assign('count',$result);
        file_put_contents($this->lock,'');
        return view();
    }


}