<?php
namespace app\common\model;

use think\Model;

class Menu extends Model
{
    protected $name = 'menu';

    protected function getMenuTypeAttr($value,$data){
        $type = [
            'url'       => '链接',
            'category'  => '文章分类',
            'article'   => '文章',
            'page'      => '页面'
        ];
        return $type[$data['type']];
    }

}