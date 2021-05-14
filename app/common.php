<?php
//
//use think\exception\HttpResponseException;
//use think\facade\View;
//use think\Response;
//
//// 应用公共文件
//
///**
// * 操作成功跳转的快捷方法
// * @access protected
// * @param mixed $msg 提示信息
// * @param string $url 跳转的URL地址
// * @param string $type 请求类型，默认是接口请求，如果pc端请求那么用html
// * @param mixed $data 返回的数据
// * @param integer $wait 跳转等待时间
// * @param array $header 发送的Header信息
// * @return void
// */
//function success($msg = '', $url = null, $type = '', $data = '', $wait = 3, array $header = [])
//{
//    if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
//        $url = $_SERVER["HTTP_REFERER"];
//    }
//    $result = [
//        'code' => 1,
//        'msg' => $msg,
//        'data' => $data,
//        'url' => $url,
//        'wait' => $wait,
//    ];
//
//    // 把跳转模板的渲染下沉，这样在 response_send 行为里通过getData()获得的数据是一致性的格式
//    if ('html' == strtolower($type)) {
//        $data = View::fetch('/dispatch_jump', $result);
//        $response = Response::create($data, $type, 200)->header($header);
//        throw new HttpResponseException($response);
//    } else {
//        print_r(json($result));
//        exit;
//    }
//}
//
///**
// * 操作错误跳转的快捷方法
// * @access protected
// * @param mixed $msg 提示信息
// * @param string $url 跳转的URL地址
// * @param string $type 请求类型，默认是接口请求，如果pc端请求那么用html
// * @param mixed $data 返回的数据
// * @param integer $code 错误代码
// * @param integer $wait 跳转等待时间
// * @param array $header 发送的Header信息
// * @return void
// */
//function error($msg = '', $url = null, $type = '', $data = '', $code = 0, $wait = 3, array $header = [])
//{
//    if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
//        $url = $_SERVER["HTTP_REFERER"];
//    }
//
//    $result = [
//        'code' => $code,
//        'msg' => $msg,
//        'data' => $data,
//        'url' => $url,
//        'wait' => $wait,
//    ];
//
//    // 把跳转模板的渲染下沉，这样在 response_send 行为里通过getData()获得的数据是一致性的格式
//    if ('html' == strtolower($type)) {
//        $data = View::fetch('/dispatch_jump', $result);
//        $response = Response::create($data, $type, 200)->header($header);
//        throw new HttpResponseException($response);
//    } else {
//        print_r(json($result));
//        exit;
//    }
//}

//function getTree($data, $pId)
//{
//    $tree = array();
//    foreach ($data as $k => $v) {
//        if ($v['parentid'] == $pId) {         //父亲找到儿子
//            $v['parentid'] = getTree($data, $v['id']);
//            if($v['parentid'] == array() || empty($v['parentid'])){
//                $v['parentid'] = '';
//            }
//            $tree[] = $v;
//            //unset($data[$k]);
//        }
//    }
//    return $tree;
//}



