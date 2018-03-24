<?php
namespace app\common\model;

use think\Model;

class Article extends Model
{
    protected $name = 'article';

    protected function getArticleStatusAttr($value,$data){
        $list = [
            0   => '草稿',
            1   => '已发布',
            2   => '加密'
        ];
        return $list[$data['status']];
    }

}