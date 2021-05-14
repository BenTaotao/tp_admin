<?php
declare (strict_types=1);
/**
 * Created by
 * User BaiXiantao
 * Date 2020/12/7
 * Time 23:47
 */

namespace app\admin\controller;

use app\Request;
use think\exception\Handle;
use think\facade\Db;
use think\facade\Session;
use think\wenhainan\Auth;
use think\exception\HttpResponseException;
use think\facade\View;
use think\Response;
use app\admin\controller\Config;

class Common
{



    public function __construct()
    {
        $site_name = Config::$site_name;
        View::assign('site_name',$site_name);
        self::auth();//权限验证
        self::initialize(); #提取菜单
    }
    /**
     * 验证登录的
     */
    public function auth()
    {
        $Admin_outime = session('Admin_outime');
        $admin_info = session('admin_info');
        //write_log("Admin_outtime:{$Admin_outime} AdminInfo:".json($admin_info),'Error','ERROR');
        if (!$Admin_outime || !$admin_info) {
            $msg = !$admin_info ? '登录已过期，请您重新登录':'登录已过期，请您重新登录';
            if (request()->isAjax()) {
                return json(['status'=>1,'msg'=>$msg]);
            } else {
                Session::clear();
                $login_url = url("login/index");
                echo("<script type='text/javascript'>window.top.location.href='{$login_url}';</script>");
                die();
            }
        } else {
            if (session('Admin_outime') < time()) {
                session("Admin_outime", null);
                if (request()->isAjax()) {
                    return json(['status'=>1,'msg'=>'登录已过期，请您重新登录']);
                } else {
                    Session::clear();
                    $login_url = url("login/index");
                    echo("<script type='text/javascript'>window.top.location.href='{$login_url}';</script>");
                    die();
                }
            }
        }
        //如果最近操作时间已过去了5小时，则让账户重新登录
        if (session('Admin_lasttime') && time() - session('Admin_lasttime') > 5 * 3600) {
            session("Admin_outime", null);
            session("Admin_lasttime", null);
            if (request()->isAjax()) {
                return json(['status'=>1,'msg'=>'登录已过期，请您重新登录']);
            } else {
                Session::clear();
                $login_url = url("login/index");
                echo("<script type='text/javascript'>window.top.location.href='{$login_url}';</script>");
                die();
            }
        }
        session("admin_name", $admin_info['admin_name']);
        session("admin_id", $admin_info['id']);
        session('truename', $admin_info['truename']);
        session('language', $admin_info['language']);
        session('Admin_lasttime', time()); //每次操作，刷新最近操作时间
        if (empty($admin_info['id'])) {
            Session::clear();
            $login_url = url("login/index");
            echo("<script type='text/javascript'>window.top.location.href='{$login_url}';</script>");
            die();
        }

        #检查权限
        $app_name = app('http')->getName();
        $controller = strtolower(request()->controller());
        $action = strtolower(request()->action());
        $auth = Auth::instance();
        #验证是否为超级管理员，超级管理员不限制
        if ($admin_info['power_team'] == 1){
            return true;
        }
        // 检测权限
        if (!$auth->check(/*'/'.$app_name.'/'.*/$controller . '/' . $action, $admin_info['id'])) {// 第一个参数是规则名称,第二个参数是用户UID
            //有显示操作按钮的权限
            return json(['status'=>1,'msg'=>'没有权限']);
        }

    }

    /**
     *这里是公共的模板数据--菜单等
     */
    public function initialize()
    {
        #menu
        $admin_info = session('admin_info');
        $admin_id = $admin_info['id'];
        $power_team = $admin_info['power_team'];
        $auth_group_id = Db::name('auth_group_access')->where(['uid'=>$admin_id])->find();
        $auth_group = Db::name('auth_group')->where(['status'=>1])->where(['id'=>$auth_group_id['group_id']])->find();
        $rule_list = explode(',', $auth_group['rules']);
        if ($power_team === 1){ ##这里如果是超级管理员则抛出所有菜单
            $menu_list = Db::name('auth_rule')->where(['status'=>1])->field('id,name,title,parent')->select()->toArray();
        }else{
            $menu_list = Db::name('auth_rule')->where(['status'=>1])->where('id','in',$rule_list)->field('id,name,title,parent')->select()->toArray();
        }

        #数组重组
        $tree = getTree($menu_list, 0);
        view::assign('tree',$tree);
        $first_tree = reset($tree);

        $tree = procHtml($tree, $first_tree['id']);

        cache('tree', $tree, array('type' => 'file', 'expire' => 86400)); //

        #名称
        $adminname = $admin_info['truename'] ?? '';
        view::assign('adminname',$adminname);
        view::assign('admin_info',$admin_info);

        #尾部数据输出
        $year = date('Y',time());
        View::assign('year',$year);
    }

    /**
     * 404返回~异常处理
     * @param $name
     * @param $arguments
     * @return \think\response\Json
     */
    public function __call($name, $arguments)
    {
        // 如果是API模块，需要输出API的数据格式（一般是json）
        // 如果是模板引擎的方式，需要输出自定义错误页面
        $result = [
            'status' => 0,
            'message' => '找不到该方法',
            'result' => null
        ];
        return json($result, 400);
    }


}