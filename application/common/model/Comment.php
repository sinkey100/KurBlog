<?php
namespace app\common\model;

use think\Model;

class Comment extends Model
{
    protected $name = 'comment';

    protected function getTimeAttr($time){
        return date("Y-m-d H:i:s",$time);
    }

    protected function getStatusStrAttr($value,$data){
        $status = [
            0   => '<span>待审核</span>',
            1   => '<span></span>',
            2   => '<span></span>'
        ];
        return $status[$data['status']];
    }


    public static function getCommentHTML($data){
        $style = include APP_PATH.'theme.php';
        $html = $style['comment']['comment_start'].$style['comment']['child_start'].
            $style['comment']['child_end'].$style['comment']['comment_end'];
        $str = str_replace(
            ['{id}','{avatar}','{author}','{url}','{content}','{time}'],
            [$data['id'],getGravatar($data['email']),$data['author'],$data['url'],$data['content'],$data['time']],
            $html
        );
        return $str;
    }

    public static function getComment($type='article',$id,$order){
        $list = self::where('type',$type)
            ->where('value',$id)
            ->order('id',$order)
            ->select();

        $style = include APP_PATH.'theme.php';
        if(count($list) == 0){
            return $style['comment']['empty'];
        }else{
            return self::getCommentRecursion($list,0,$style);
        }
    }

    protected static function getCommentRecursion($list,$row,$style=[]){
        //数组返回
        /*
        $data = [];
        foreach ($list as $v){
            if($v['parent_id'] == intval($row['id'])){
                $v['child'] = self::getCommentRecursion($list,$v);
                $v['avatar'] = getGravatar($v['email']);
                $data[] = $v;
            }
        }
        return $data;
        */

        //HTML返回
        $html = '';
        foreach ($list as $v){
            if($v['parent_id'] == intval($row['id'])){
                $str = str_replace(
                    ['{id}','{avatar}','{author}','{url}','{content}','{time}'],
                    [$v['id'],getGravatar($v['email']),$v['author'],$v['url'],$v['content'],$v['time']],
                    $style['comment']['comment_start']
                );
                $html .= $str;
                $html .= $style['comment']['child_start'];
                $html .= self::getCommentRecursion($list,$v,$style);
                $html .= $style['comment']['child_end'];
                $html .= $style['comment']['comment_end'];
            }
        }
        return $html;
    }

}