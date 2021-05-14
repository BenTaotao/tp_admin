<?php
/**
 * Created by
 * User BaiXiantao
 * Date 2020/12/5
 * Time 23:51
 */

namespace app\admin\controller;


use think\Exception;
use think\facade\Db;
use \think\facade\View;

class Admin extends Common
{

    public function index(){
        $adminlist = Db::name('admin')->paginate(10);
        // 获取分页显示
        $page = $adminlist->render();
        view::assign('adminlist',$adminlist);
        view::assign('page',$page);
        return view();
    }

    #管理员列表
    public function admin_list()
    {
        if (request()->isAjax()){
            $group_list = Db::name('admin')->select();
            $power_team = Db::name('auth_group')->field('id,title')->select();
            $power_team = cms_rebuild($power_team, 'id');
            foreach ($group_list as $k=>$v) {
                $v['power_team'] = $power_team[$v['power_team']]['title'];
                $group_list[$k] = $v;
            }
            //view::assign('group_list', $group_list);
            return ajaxTable(0,'',$group_list);
        }

        return view::fetch();
    }

    #管理员添加
    public function admin_add()
    {
        if (request()->isAjax()) {
            $data = $_POST;
            $admin_name = $data['admin_name'];
            $is_exit = Db::name('admin')->where(['admin_name' => $admin_name])->find();
            if (!empty($is_exit)) {
                return ajaxTable(0, '已经存在');
            }
            $res = Db::name('admin')->insert($data);
            if ($res) {
                return ajaxTable(0, 'success');
            } else {
                return ajaxTable(1, 'fail');
            }
        }
        $power_team = Db::name('auth_group')->field('id,title')->select();
        view::assign('power_team',$power_team);
        return view::fetch();
    }

    #管理员保存
    public function admin_edit()
    {
        if (request()->isAjax()) {
            $data = input();
            $id = $data['id'];
            unset($data['s']);
            $res = Db::name('admin')->where(['id' => $id])->update($data);
            if ($res) {
                return ajaxTable(0, 'success');
            } else {
                return ajaxTable(1, 'fail');
            }
        }

        $id = input('id');
        $data = Db::name('admin')->where(['id' => $id])->find();
        view::assign('data',$data);
        $power_team = Db::name('auth_group')->field('id,title')->select();
        view::assign('power_team',$power_team);
        return view::fetch();
    }

    #管理员删除
    public function admin_del()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $res = Db::name('admin')->where(['id' => $id])->update(['is_delete' => 0]);
            if ($res) {
                return ajaxTable(0, 'success');
            } else {
                #write_log("删除失败:","Admin","Error");
                return ajaxTable(1, 'fail');
            }
        }
    }

}