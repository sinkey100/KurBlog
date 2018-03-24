<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/1/12
 * Time: 14:28
 */

namespace app\install\controller;

use think\Controller;
use think\Db;
use think\Session;
use app\install\model\Database;

class Index extends Controller
{
    public function _initialize() {
        parent::_initialize();
        if(request()->action() != 'step_4'){
            $config_path = APP_PATH . 'database.php';
            if(file_exists($config_path)){
                $this->error('请勿重新安装，如需重新安装，请删除 application\database.php 文件后重试',NULL,'',60);
            }
        }

    }

    public function index() {
        return view();
    }

    public function step_1() {
        return view();
    }

    public function step_2() {
        //验证表单
        $data = input('post.');
        $result = $this->validate($data, 'Database');
        if (true !== $result) {
            $this->error('请填写正确的数据库信息');
            return false;
        }
        //尝试连接数据库
        $db = Database::toConnect($data['db_host'], $data['db_user'], $data['db_password'], $data['db_name'], $data['db_prefix']);
        if (false == $db) {
            $this->error('数据库连接失败。');
            return false;
        }
        //检查数据表是否正确
        $result = Database::checkPrefix($db);
        if (true == $result) {
            $this->error('数据表前缀已被占用。');
            return false;
        }
        Session::set('database', $data, 'install');
        return view();
    }

    public function step_3() {
        //验证表单
        $data = input('post.');
        $result = $this->validate($data, 'Setting');
        if (true !== $result) {
            $this->error('请填写正确的信息');
            return false;
        }
        Session::set('data', $data, 'install');
        $database = Session::get('database', 'install');
        //修改database.php
        $tmp_path = APP_PATH . 'install/extra/db.config.php';
        $config_path = APP_PATH .'database.php';
        $config = file_get_contents($tmp_path);
        $a = ['{db_host}', '{db_name}', '{db_user}', '{db_password}', '{db_port}', '{db_prefix}'];
        $b = [$database['db_host'], $database['db_name'], $database['db_user'], $database['db_password'], $database['db_port'], $database['db_prefix']];
        $config = str_replace($a, $b, $config);
        $res = file_put_contents($config_path, $config);
        if (!$res) {
            $this->error('写入数据库配置文件出错，请检查application/database.php的读写权限');
            return false;
        }
        $this->redirect(url('install/index/step_4'));

    }

    public function step_4(){
        //开始安装
        $data = Session::get('data', 'install');
        $database = Session::get('database', 'install');
        if(!$data || !$database){
            $this->error('参数错误');
            return false;
        }
        $sql = file_get_contents(__DIR__ . '/../extra/database.sql');
        $a = ['{prefix}', '{email}', '{blog_name}', '{time}', '{username}', '{password}'];
        $b = [$database['db_prefix'], $data['email'], $data['title'], time(), $data['username'], md5(sha1($data['password']))];
        $sql = str_replace($a, $b, $sql);
        $sql = explode(";", $sql);
        array_pop($sql);
        foreach ($sql as $v) {
            Db::execute($v);
        }
        return view();
    }

}