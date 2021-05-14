<?php
declare (strict_types=1);
/**
 * Created by
 * User BaiXiantao
 * Date 2020/12/5
 * Time 23:51
 */

namespace app\admin\controller;


use think\facade\Db;
use think\facade\Session;
use think\facade\View;

class Login
{
    public function __construct()
    {
        #$site_name = "XTadmin";
        $site_name = "越南语";
        View::assign('site_name',$site_name);
    }

    public function index()
    {
        Session::clear();
        return view();
    }

    public function login()
    {
        if (!$_POST) {
            return json(['status'=>2,'msg'=>"非法请求"]);
        }

        $name = input("name");
        $pass = input("pass");
        $pass = md5($pass);
        $admin_info = Db::name('admin')->where(['admin_name' => $name])->find();
        if (!empty($admin_info)) {
            if ($pass == $admin_info["admin_pwd"]) {
                if ($admin_info['is_delete'] == 1) {
                    return json(['status'=>4,'msg'=>"该账户已冻结"]);
                }
                //登录成功,判断当前用户组是否可以正常登录
                $pwoerTeam = $admin_info['power_team'];
                //根据权限组ID,查询此权限组赋予的权限
                $pwoerList = Db::name('powerteam')->field("power")->where("id=" . $pwoerTeam)->find();
                $pwoerList = $pwoerList['power'];
                if (empty($pwoerList)) {
                    return json(['status'=>5,'msg'=>"权限错误"]);
                }
                $outime = time() + 36000;
                session('Admin_outime', $outime);//登录过期时间  默认登录10小时过期
                session('Admin_lasttime', time()); //最近操作的时间
                $data_update = array(
                    'login_ip' => get_client_ip_rewrite(),
                    'login_time' => time()
                );
                Db::name('admin')->where("id=" . $admin_info['id'])->update($data_update);
                session('admin_info', $admin_info);
                return json(['status'=>0,'msg'=>"登录成功"]);
            } else {
                return json(['status'=>1,'msg'=>"密码错误"]);
            }
        } else {
            return json(['status'=>3,'msg'=>"管理员不存在"]);
        }

    }

    public function login_out()
    {
        Session::clear();
        return redirect('index/index');
    }


}