<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/3/12
 * Time: 16:52
 */

namespace app\index\controller;

use app\common\model\Comment as CommentModel;
use app\common\model\Article as ArticleModel;
use app\common\model\Page as PageModel;

class Comment extends Base
{

    /**-------------------- API ---------------------**/

    public function api() {
        $json = [];
        $json['err'] = 0;
        $data = input('post.');
        $action = 'api_' . trim(@$data['action']);
        if ($action == 'api') {
            $json['err'] = -1;
        } else if (!method_exists($this, $action)) {
            $json['err'] = -2;
        } else {
            $json = $this->$action($json, $data);
        }
        return json_encode($json);
    }

    protected function api_post($json, $data) {
        $result = $this->validate($data, "Comment");
        if (true !== $result) {
            $json['err'] = 1;
            $json['message'] = $result;
            return $json;
        }
        $status = $this->_K['setting']['need_review'] == 1 ? 0 : 1;
        $data = [
            'parent_id' => intval($data['parent_id']),
            'type' => $data['type'],
            'value' => $data['value'],
            'status' => $status,
            'author' => $data['author'],
            'email' => $data['email'],
            'url' => $data['url'],
            'content' => $data['content'],
            'time' => time(),
            'user_agent' => request()->header()['user-agent'],
            'ip' => request()->ip()
        ];
        $result = CommentModel::create($data);
        if (!$result) {
            $json['err'] = 2;
        } else {
            $json['status'] = $status;
            $json['html'] = CommentModel::getCommentHTML($result);
        }
        $this->sendMail($result);
        return $json;
    }

    protected function sendMail($data) {
        //发送邮件通知
        //当parent_id > 0 && !empty(parent_email) && comment-mail == true
        if ($this->_K['setting']['comment_mail'] == 'true' && $data['parent_id'] > 0) {
            $parent = CommentModel::get($data['parent_id']);
            if ($data['type'] == 'article') {
                $title = ArticleModel::get($data['value']);
            } else {
                $title = PageModel::get($data['value']);
            }
            $slug = empty($title['slug']) ? $data['value'] : $title['slug'];
            $title = $title['title'];
            if (!empty($parent['email'])) {
                $t = include APP_PATH.'theme.php';
                $t = $t['mail'];
                $t = str_replace('{blog_name}', $this->_K['setting']['blog_name'], $t);
                $t = str_replace('{parent_author}', $parent['author'], $t);
                $t = str_replace('{parent_content}', $parent['content'], $t);
                $t = str_replace('{title}', $title, $t);
                $t = str_replace('{author}', $data['author'], $t);
                $t = str_replace('{content}', $data['content'], $t);
                $t = str_replace('{url}', $this->_K['setting']['siteurl'] . $this->getURL($data['type'], $data['value'], $slug), $t);
                sendMail($parent['email'], '您在[' . $this->_K['setting']['blog_name'] . ']的回复有了新动态', $t);
            }
        }
    }

}