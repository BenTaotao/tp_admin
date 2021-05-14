<?php
declare (strict_types = 1);

namespace app\admin\middleware;


use think\Response;

class Login
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
//        halt($request);
        if ($request->param("login_url")=="login/index"){
//        if ($request->login_url=="login/index"){
//            halt($request->login_url);
            return redirect("login/index");
        }
        return $next($request);
    }

}
