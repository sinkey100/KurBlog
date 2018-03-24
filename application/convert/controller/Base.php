<?php
namespace app\convert\controller;

use think\Controller;

class Base extends Controller
{

    public $lock;

    public function _initialize() {
        parent::_initialize();
        $this->lock = APP_PATH.'convert/extra/convert.lock';
        if(file_exists($this->lock)){
            $this->error('如需运行转换程序，请先删除 appliction/convert/extra/convert.lock 文件',NULL,'',60);
            return;
        }

    }
}