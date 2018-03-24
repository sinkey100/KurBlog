<?php
namespace app\convert\model;

use think\Db;
use think\Model;

class KurBlog extends Model
{

    /**
     * 清空数据表
     * @param $name 表名
     * @return int
     */
    public static function truncateTable($name){
        $prefix = config('database.prefix');
        return Db::execute('TRUNCATE '.$prefix.$name);
    }

    /**
     * 批量插入数据
     * @param $name 表名(不带前缀)
     * @param $data 数据
     * @return int|string
     */
    public static function merge($name,$data){
        return Db::name($name)->insertAll($data);
    }

    public static function getCategoryIds(){
        $result =  Db::name("category")->select();
        $data   = [];
        foreach($result as $v){
            $data[] = $v['id'];
        }
        return $data;
    }

    public static function getPageIds(){
        $result =  Db::name("page")->select();
        $data   = [];
        foreach($result as $v){
            $data[] = $v['id'];
        }
        return $data;
    }
}