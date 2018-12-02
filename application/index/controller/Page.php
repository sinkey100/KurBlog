<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/11
 * Time: 10:51
 */

namespace app\index\controller;

use app\common\model\Page as PageModel;
use app\common\model\Comment as CommentModel;
use Parsedown;

class Page extends Base
{
    public function index($param='') {
        if (empty($param)) {
            $this->error('参数错误');
            return false;
        }
        if (is_numeric($param)) {
            $res = PageModel::get($param);
        } else {
            $res = PageModel::get(['slug' => $param]);
        }
        $markdownParser = new Parsedown();
        $template = $res['template'];
        $res['content'] = $markdownParser->text($res['content']);
        $res['description'] = empty($res['description'])
            ? mb_substr(strip_tags($res['content']),0,$this->_K['setting']['description_length'], 'utf-8')
            : $res['description'];
        $this->assign('data',$res);
        $this->assign('comment',[
            'type'      => 'article',
            'value'     => $res['id'],
            'html'      => CommentModel::getComment('page',$res['id'],$this->_K['setting']['order_by_time'])
        ]);
        $this->seo_replace['{title}'] = $res['title'];
        $this->seo_replace['{description}'] = $res['description'];
        $this->setSeoData('page');
        return $this->view->fetch('/page/'.$template);
    }
}