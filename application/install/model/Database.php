<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/12
 * Time: 14:58
 */
namespace app\install\model;

use think\Model;
use think\Db;

class Database extends Model
{

    public static function toConnect($host,$user,$password,$name,$prefix,$hostport=3306){
        $db = Db::connect([
            'type'     => 'mysql',
            'hostname' => $host,
            'database' => $name,
            'username' => $user,
            'password' => $password,
            'hostport' => $hostport,
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
            $result = $db->name('setting')->select();
            return $result ? true : false;
        }catch(\Exception $e){
            return false;
        }
    }

}