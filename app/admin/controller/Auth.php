<?php


namespace app\admin\controller;


use app\admin\model\Admin;
use think\db\exception\DbException;
use think\facade\Db;
use think\facade\View as view;

//use think\Loader;
use think\Request;

class Auth extends Common
{

    public function index()
    {
        return view::fetch();
    }

    #菜单列表
    public function menu()
    {
        if (request()->isAjax()) {
            $tree = Db::name('auth_rule')->field('id,name,title,parent,type')->select()->toArray();
            #数组重组
            $tree = getTree_children($tree, 0);
            return ajaxTable(0,'',$tree);
        }
        return view::fetch();
    }

    #新增菜单
    public function menu_add(){
        $first_menu = Db::name('auth_rule')->where(['type'=>0])->select();
        view::assign("first_menu", $first_menu);
        view::assign("name", '新增菜单');
        return view::fetch('menu_edit');
    }

    #修改菜单
    public function menu_edit(){
        if (request()->isAjax()){
            $id = input('id','');
            $name = input('name');
            $parent = input('parent',0);
            $title = input('title');
            $status = input('status');
            $type = input('type');
            $data = [
                'name' => $name
                ,'parent' => $parent
                ,'title' => $title
                ,'status' => empty($status) ? 0 : 1
                ,'type' => empty($type) ? 0 : 1
            ];
            $res = empty($id) ? Db::name('auth_rule')->insert($data) : Db::name('auth_rule')->where('id', $id)->update($data);
            if(empty($res)){
                return ajaxTable(1);
            }else{
                return ajaxTable(0);
            }
        }
        $id = input('id','');
        $first_menu = Db::name('auth_rule')->where(['type'=>0])->select();
        $data = Db::name('auth_rule')->where('id',$id)->find();
        $name = '修改菜单';
        $info = compact('first_menu','data','name');
        view::assign($info);
        return view::fetch();
    }

    #菜单彻底删除--暂时先不用
    public function menu_del(){
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $group = Db::name('auth_group')->select();
            foreach($group as $list){
                if (isset($list['rules'])){
                    $list_array = explode(",",$list['rules']);
                    if (in_array($id,$list_array)){
                        returnJson(0, $list['title'].': 还在角色在使用这玩意儿，请先在角色中取消选择，再删除');
                    }
                }
            }
            $res = Db::name('auth_rule')->where(['id' => $id])->delete();
            if ($res) {
                returnJson(0, 'success');
            } else {
                returnJson(0, 'fail');
            }
        }
    }

    #权限组列表
    public function group_list()
    {
        if (request()->isAjax()){
            $group_list = Db::name('auth_group')->select();
            //view::assign('group_list', $group_list);
            return ajaxTable(0,'',$group_list);
        }

        return view::fetch();
    }

    #权限组添加
    public function group_add()
    {
        if (request()->isAjax()) {
            $data = $_POST;
            $title = $data['title'];
            $is_exit = Db::name('auth_group')->where(['title' => $title])->find();
            if (!empty($is_exit)) {
                return ajaxTable(0, '已经存在');
            }
            $res = Db::name('auth_group')->insert($data);
            if ($res) {
                return ajaxTable(0, 'success');
            } else {
                return ajaxTable(1, 'fail');
            }
        }
        return view::fetch();
    }

    #权限组保存
    public function group_edit()
    {
        if (request()->isAjax()) {
            $data = input();
            $id = $data['id'];
            unset($data['s']);
            $res = Db::name('auth_group')->where(['id' => $id])->update($data);
            if ($res) {
                return ajaxTable(0, 'success');
            } else {
                return ajaxTable(1, 'fail');
            }
        }

        $id = input('id');
        $data = Db::name('auth_group')->where(['id' => $id])->find();
        view::assign('data',$data);
        return view::fetch();
    }

    #权限组删除
    public function group_del()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $res = Db::name('auth_group')->where(['id' => $id])->update(['status' => 0]);
            if ($res) {
                return ajaxTable(0, 'success');
            } else {
                return ajaxTable(1, 'fail');
            }
        }
    }

    #权限组规则
    public function group_rule()
    {
        $id = input('id','');
        if (request()->isAjax()){
            $menu_list = Db::name('auth_rule')->select();
            #数组重组
            $menu_list = getTree_children($menu_list, 0);
            $rule = Db::name('auth_group')->where('id',$id)->value('rules');
            $rule = explode(',', $rule);
            foreach ($menu_list as $k=>$val){
                if(in_array($val['id'],$rule)){
                    $val['checked'] = true;
                    $val['spread'] = true;
                }
                if(is_array($val['children'])){
                    foreach ($val['children'] as &$vv) {
                        if(in_array($vv['id'],$rule)){
                            $vv['checked'] = false;
                        }
                    }
                }
                $menu_list[$k] = $val;
            }

            return ajaxTable(0,'',$menu_list);
        }

        view::assign('id', $id);
        return view::fetch();
    }

    #权限组规则-保存
    public function group_rule_save()
    {
        if (request()->isAjax()){
            $id = input('id');
            $id_arr = input('id_arr/a');
            // $id_arr = array_merge(["49"],$id_arr);##49控制台
            $rules = json_encode($id_arr);
            $rules = implode(',',$id_arr);
            $res = Db::name('auth_group')->where('id',$id)->update(['rules'=>$rules]);
            if (empty($res)){
                return ajaxTable(1);
            }else{
                return ajaxTable(0);
            }
        }
    }

}