<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/10
 * Time: 16:35
 */
namespace app\index\controller;

use think\Controller;
use think\View;
use Parsedown;
use app\common\model\Setting as SettingModel;
use app\common\model\Link as LinkModel;
use app\common\model\Menu as MenuModel;

class Base extends Controller
{

    public $_K;
    public $seo_replace = [];


    public function _initialize() {
        parent::_initialize();
        checkInstall();
        //获取设置
        $data       = SettingModel::all();
        $setting    = [];
        foreach($data as $v){
            $setting[$v['key']]     = $v['value'];
        }
        $this->_K['setting']    = $setting;
        //定义公共目录和主题目录
        $this->_K['public']     = '/';
        $this->_K['theme_path'] = './theme/'.$this->_K['setting']['theme'].'/';
        if(!defined('__THEME__')){
            define('__THEME__','./theme/'.$this->_K['setting']['theme']);
            define('__PUBLIC__','/theme/'.$this->_K['setting']['theme']);
        }
        //获取链接
        $link   = LinkModel::order('weight','asc')->select();
        $this->_K['link']   = $link;
        //获取菜单
        $menu   = MenuModel::order('weight','asc')->select();
        //绑定菜单数据
        $data   = [];
        foreach($menu as $v){
            $data[] = [
                'id'            => $v['id'],
                'parent_id'     => $v['parent_id'],
                'title'         => $v['title'],
                'weight'        => $v['weight'],
                'class'         => $v['class'],
                'url'           => $v['type']=='url' ? $v['value']  : $this->getURL($v['type'],$v['value'])
            ];
        }
        $this->_K['menu'] = $data;
        //视图实例化
        $this->view = new View([
            'type'          => 'Php',
            'view_path'     => $this->_K['theme_path'],
            'view_suffix'   => 'php',
            'view_depr'     => '/',
        ]);
        $this->assign('_K',$this->_K);
        //SEO优化预设
        $this->seo_replace = [
            '{blog_name}'       => $this->_K['setting']['blog_name'],
            '{sub_title}'       => $this->_K['setting']['sub_title'],
            '{site_url}'        => $this->_K['setting']['siteurl']
        ];
        $this->assign('seo_title',$this->_K['setting']['blog_name']);
        $this->assign('seo_keywords','');
        $this->assign('seo_description','');
    }




    /**
     * 获取伪静态URL
     * @param $type
     * @param $id
     * @param null $slug
     * @return string
     */
    protected function getURL($type,$id,$slug=null){
        $t = ($this->_K['setting']['rewrite'] == 'slug' && !empty($slug)) ? $slug : $id;
        if($type == 'page'){
            return url('index/page/index',['param'=>$t]);
        }else if($type == 'category'){
            return url('index/category/index',['param'=>$t]);
        }else if($type == 'article'){
            return url('index/article/index',['param'=>$t]);
        }
    }


    protected function setSeoData($key){
        $find = [];
        $replace = [];
        foreach ($this->seo_replace as $k=>$v){
            $find[]     = $k;
            $replace[]  = $v;
        }
        $this->assign('seo_title',str_replace($find,$replace,$this->_K['setting']['seo_'.$key.'_title']));
        $this->assign('seo_keywords',str_replace($find,$replace,$this->_K['setting']['seo_'.$key.'_keywords']));
        $this->assign('seo_description',str_replace($find,$replace,$this->_K['setting']['seo_'.$key.'_description']));
    }

    protected function processing($res,$category_list){
        $category = [];
        $markdown = new Parsedown;
        foreach ($category_list as $v) {
            $v['url']   = $this->getURL('category',$v['id'],$v['slug']);
            $category[$v['id']] = $v;
        }
        //数据封装处理
        for ($i = 0; $i < count($res); $i++) {
            //添加分类
            $res[$i]['category'] = $category[$res[$i]['category_id']];
            //MarkDown转义
            $res[$i]['content'] = $markdown->text($res[$i]['content']);
            //缩略图
            if(empty($res[$i]['thumb'])){
                $pattern        ="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
                preg_match_all($pattern,$res[$i]['content'],$matches);
                $res[$i]['thumb']    = empty($matches[1][0]) ? '' : $matches[1][0];
            }
            //添加描述
            $res[$i]['description']    = empty($res[$i]['description'])
                ? mb_substr(strip_tags($res[$i]['content']),0,$this->_K['setting']['description_length'], 'utf-8')
                : $res[$i]['description'];
            //添加URL
            $res[$i]['url'] = $this->getURL('article',$res[$i]['id'],$res[$i]['slug']);
        }
        return $res;
    }













}