<?php
namespace app\admin\controller;

use app\common\model\Menu as MenuModel;

class Menu extends Base
{
    public function index(){
        //获取所有菜单
        $list   = MenuModel::order('weight','asc')->select();
        $menu   = [];
        //枚举顶级菜单
        foreach($list as $v){
            if($v['parent_id']==0){
                $menu[$v['id']] = $v;
                //枚举子菜单
                foreach($list as $parent){
                    if($parent['parent_id'] == $v['id']){
                        $menu[$parent['id']] = $parent;
                    }
                }
            }
        }
        $this->assign('nav_cur','menu');
        $this->assign('list',$menu);
        return view();
    }

    public function update(){
        $data   = input('post.');
        $id     = $data['id'];
        $result = $this->validate($data,'Menu');
        if (true !== $result) {
            $this->error('提交失败');
            return false;
        }
        $data = [
            'parent_id' => intval($data['parent_id']),
            'title'     => $data['title'],
            'type'      => $data['type'],
            'value'     => $data['type']=='url' ? $data['value'] : intval($data['value']),
            'class'     => $data['class']
        ];
        if($id == 0){
            $data['weight'] = 0;
            $result = MenuModel::create($data);
        }else{
            $data['id'] = $id;
            $result = MenuModel::update($data);
        }
        if(!$result){
            $this->error('提交失败');
            return false;
        }
        $this->redirect('/admin/menu');
    }

    public function order(){
        $data = input('post.');
        $list = [];
        foreach($data['weight'] as $k=>$v){
            $list[] = [
                'id'     => intval($k),
                'weight' => intval($v)
            ];
        }
        $Menu   = new MenuModel;
        $result = $Menu->saveAll($list);
        if(!$result){
            $this->error('更新排序失败');
            return false;
        }
        $this->redirect('/admin/menu');
    }




    /**-------------------- API ---------------------**/

    public function api(){
        $json           = [];
        $json['err']    = 0;
        $data = input('post.');
        $action = 'api'.ucfirst(@$data['action']);
        if($action=='api'){
            $json['err']    = -1;
        }else if(!method_exists($this, $action)){
            $json['err']    = -2;
        }else{
            $json = $this->$action($json,$data);
        }
        return json_encode($json);
    }

    protected function apiDelete($json,$data){
        $id = intval($data['id']);
        if($id == 0){
            $json['err']    = 1;
            return $json;
        }
        MenuModel::destroy($id);
        MenuModel::destroy([
            'parent_id' => $id
        ]);
        return $json;
    }
}