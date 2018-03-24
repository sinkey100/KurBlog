<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/3/24
 * Time: 23:22
 */

namespace app\index\controller;

use app\common\model\Article as ArticleModel;
use app\common\model\Rss as RssModel;
use app\common\model\RssItem;
use Parsedown;

class Rss extends Base
{
    public function index($num = 10)
    {
        header('Content-Type: text/xml');
        $res    = ArticleModel::all(function($query) use ($num){
            $query->where('status',1)->order('id desc')->limit($num);
        });
        $feed = new RssModel();
        $markdownParser = new Parsedown();
        $feed->title       = $this->_K['setting']['blog_name'];
        $feed->link       = $this->_K['setting']['siteurl'];
        $feed->description       = $this->_K['setting']['sub_title'];
        foreach($res as $v){
            $content = $markdownParser->text($v['content']);
            $item = new RssItem();
            $item->title = $v['title'];
            $item->link  = $this->getURL('article',$v['id']);
            $item->setPubDate($v['create_time']);
            $item->description = "<![CDATA[ $content ]]>";
            $feed->addItem($item);
        }
        return $feed->serve();
    }
}