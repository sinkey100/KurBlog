<?php
namespace app\convert\model;

use think\Model;
use think\Db;

class WordPress extends Model
{
    public static function toConnect($host,$user,$password,$name,$prefix){
        $db = Db::connect([
            'type'     => 'mysql',
            'hostname' => $host,
            'database' => $name,
            'username' => $user,
            'password' => $password,
            'charset'  => 'utf8',
            'prefix'   => $prefix,
        ]);
        try{
            $db->connect();
            return $db;
        }catch(\Exception $e){
            return false;
        }
    }

    public static function checkPrefix($db){
        try{
            $result = $db->name('users')->select();
            return count($result)>0 ? true : false;
        }catch(\Exception $e){
            return false;
        }
    }

    public static function getPage($db){
        return $db->where('post_type','page')->name('posts')->select();
    }

    public static function getCategory($db){
        $result = $db->name('term_taxonomy')->where('taxonomy','category')->select();
        $ids   = [];
        foreach($result as $v){
            $ids[] = $v['term_id'];
        }
        $ids = implode(',',$ids);
        return $db->name('terms')->where(['term_id'=>['in',$ids]])->select();
    }

    public static function getArticle($db){
        return $db->where('post_type','post')->name('posts')->select();
    }

    public static function relationships($db){
        $result = $db->name('term_relationships')->select();
        $data   = [];
        foreach($result as $v){
            $data[$v['object_id']] = $v['term_taxonomy_id'];
        }
        return $data;
    }

    public static function getComment($db){
        return $db->name('comments')->select();
    }
}