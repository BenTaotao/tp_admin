<?php
// 这是系统自动生成的公共文件


use think\facade\App;


function getTree($data, $pId)
{
    $tree = array();
    foreach ($data as $k => $v) {
        if ($v['parent'] == $pId) {         //父亲找到儿子
            $v['parent'] = getTree($data, $v['id']);
            $tree[] = $v;
        }
    }
    return $tree;
}
function getTree_children($data, $pId)
{
    $tree = array();
    foreach ($data as $k => $v) {
        if ($v['parent'] == $pId) {         //父亲找到儿子
            $v['children'] = getTree_children($data, $v['id']);
            unset($v['parent']);
            $tree[] = $v;
        }
    }
    $tree = array_values($tree);
    return $tree;
}

#菜单格式化函数---一级菜单
function procHtml(array $tree,int $id,int $self_id = 0)
{
    $html = '';
    if (is_array($tree)) {
        foreach ($tree as $t) {
            if ((int)$t['parent'] == 0) {
                $a = url($t['name']);
                if ($t['id'] == $id) {
                    $html .= "<li class='layui-nav-item'><a href='javascript:;' data-url='$a'>&nbsp;&nbsp;".$t['title']."</a></li>";
                } else {
                    $html .= "<li class='layui-nav-item'><a href='javascript:;' data-url='$a'>&nbsp;&nbsp;".$t['title']."</a></li>";
                }
            } else {
                if ($t['id'] == $id) {
                    $html .= "<li class='layui-nav-item'><a href='javascript:;'>&nbsp;&nbsp;".$t['title']."</a>";
                } else {
                    $html .= "<li class='layui-nav-item'><a href='javascript:;'>&nbsp;&nbsp;".$t['title']."</a>";
                }
                $html .= "<dl class='layui-nav-child'>";
                $html .= procHtmlIn($t['parent'], $self_id);
                $html .= "</dl>";
                $html = $html . "</li>";
            }
        }
    }
    return $html;
}
#菜单格式化杉树---二级菜单
function procHtmlIn(array $tree, int $self_id)
{
    $html = '';
    foreach ($tree as $t) {
        $a = url($t['name']);
        if ($self_id == $t['id']) {
            $html .= "<dd class='layui-this'><a href='javascript:;' data-url='$a'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$t['title']."</a></dd>";
        } else {
            $html .= "<dd><a href='javascript:;' data-url='$a'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$t['title']."</a></dd>";
        }
    }
    return $html;
}


function get_client_ip_rewrite()
{
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');

    } elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

#写日志
function write_log($msg, $dir_name = 'Info', $level = "INFO")
{

    $path = ROOT_PATH . "public" . DS . "log" . DS;

    if (!$msg) {
        return false;
    }
    if (is_array($msg)) {
        $msg = json_encode($msg, JSON_UNESCAPED_UNICODE);
    }

    $msg = date('Y-m-d H:i:s') . " [ {$level} ] " . $msg . "\n";

    $path = $path . $dir_name;
    if (!is_dir($path)) {
        @mkdir($path, 0777);
    }

    $path = $path . DS . date('Y-m-d') . ".log";

    error_log($msg, 3, $path);
}

function curl_ssl($url, $data = [], $RETURNTRANSFER = 1, $header = array())
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $RETURNTRANSFER);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
    $file_contents = curl_exec($ch);
    curl_close($ch);

    return $file_contents;
}


/**格式化数据
 * @param $data
 * @param $filed
 * @param int $type
 * @param string $func
 * @return array
 */
function cms_rebuild($data, $filed, $type = 1, $func = '')
{
    $ret_data = array();
    if ((is_array($data) || is_object($data)) && !empty($data)) {
        if ($type == 1) {
            ####以数组中某个字段为key,其他值为value重新组成数组
            foreach ($data as $key => $row) {
                $ret_data[$row[$filed]] = is_callable($func) ? $func($row) : $row;
            }
        } elseif ($type == 2) {
            ###抽取数组中某个字段值重新组成数组
            foreach ($data as $key => $row) {
                $ret_data[] = is_callable($func) ? $func($row[$filed]) : $row[$filed];
                ###去重
                $ret_data = array_unique($ret_data);
            }
        } elseif ($type == 3) {
            ####以数组中某个字段为key,其他值为value重新组成数组 与1的区别是返回的三维数组
            foreach ($data as $key => $row) {
                $ret_data[$row[$filed]][] = is_callable($func) ? $func($row) : $row;
            }
        } elseif ($type == 4) {
            ###ThinkPHP查询完的数组倒序
            if (!is_object($data)) {
                return array_reverse($data);
            } else {
                $message = [];
                foreach ($data as $v) {
                    $message[] = $v;
                }
                return array_reverse($message);
            }
        } elseif ($type == 5) {
            ###抽取数组中某个字段值重新组成数组 -跟2的区别是这个不去重
            foreach ($data as $key => $row) {
                $ret_data[] = is_callable($func) ? $func($row[$filed]) : $row[$filed];
            }
        }

    } else {
        $ret_data = $data;
    }
    return $ret_data;
}

//暂时用自带的Catch缓存
function getCatchKey($param)
{
    $key = [request()->host(), App::initialize()->http->getName(), request()->controller(), request()->action()];
    $key = array_merge($key, $param);
    $key = implode('_', $key);
    $key = md5($key);
    if (isset($_GET['refresh']) && $_GET['refresh'] == 1) {
        return $key;
    }

    $data = \think\facade\Cache::store("redis")->get($key);
    if ($data) {
        echo $data;
        exit;
    }
    return $key;
}


//数据返回格式 data默认都返回object
function returnJson($error = 0, $msg = 0, $data = array(), $cache_key = NULL, $cache_time = 300, $is_data_object = false)
{
    $error = (int)$error;
    $return = array();
    $return['error'] = (int)$error;
    $return['msg'] = $msg;
    if (empty($data)) {
        $data = $is_data_object ? array() : (object)array();
    }
    $return['data'] = $data;
    $tmp = json_encode($return);

    if ($cache_key && $cache_time > 0) {
        \think\facade\Cache::store("redis")->set($cache_key, $tmp, $cache_time);
    }

    echo $tmp;
    exit;
}


/**
 * ajaxTable请求api格式化
 * @param $code
 * @param $msg
 * @param $count
 * @param $data
 * @return array
 */
function ajaxTable($code, $msg = '', $data = [], $count = 0, $mdata = [])
{
    if ($msg == '') {
        if ($code == 0) {
            $msg = '成功！';
        }
        if ($code == 1) {
            $msg = $msg == '' ? '失败！' : $msg;
        }
    }
    return json(["code" => $code, "msg" => $msg, "count" => $count, "data" => $data, "mdata" => $mdata]);
}


function format_time($time)
{
    $NOW_TIME = time();
    $return = '';
    if (!is_numeric($time)) {
        $time = strtotime($time);
    }
    $d1 = date('d', $time);
    $d2 = date('d', $NOW_TIME);
    $y1 = date('Y', $time);
    $y2 = date('Y', $NOW_TIME);
    $htime = date('H:i', $time);
    $dif = abs($NOW_TIME - $time);
    if ($dif < 10) {
        $return = lang('just now');
    } else if ($dif < 3600) {
        $return = floor($dif / 60) . lang('minutes ago');
    } else if ($dif < 10800) {
        $return = floor($dif / 3600) . lang('hours ago');
    } else if ($d1 == $d2) {
        $return = 'today ' . $htime;
    } else if ($dif < 86400) {
        $return = 'yesterday ' . $htime;
    } else if ($y1 == $y2) {
        $return = date('m-d H:i', $time);
    } else {
        $return = date('Y-m-d H:i', $time);
    }
    return $return;
}


/**截取英文单词，不破坏词语
 * @param $text
 * @param int $length
 * @param string $tail
 * @return string
 */
function str_cut_word($text, $length = 500, $tail = "...")
{
    $txtl = strlen($text);
    if ($txtl > $length) {
        for ($i = $length; $i <= $txtl; $i++) {
            if (substr($text, $i, 1) == " ") {
                return substr($text, 0, $i) . $tail;
            }
        }
    }
    return $text;
}


/**
 * 时区：ios:Asia/Shanghai (GMT+8) offset 28800
 * 安卓：  GMT+08:00
 * GMT+08  设置时区使用： date_default_timezone_set("Etc/GMT-8");
 * 时区处理
 */
function handle_time_zone($timeZone)
{
    if (preg_match('/\+/', $timeZone)) {
        $mark = "-";
    } elseif (preg_match("/-/", $timeZone)) {
        $mark = "+";
    } else {
        return ['set_zone' => "Etc/GMT+0", 'rang' => 0];
    }

    ###取时区
    preg_match("/GMT[\+|-]\d+/i", $timeZone, $zone);

    if (empty($zone)) {
        return ['set_zone' => "Etc/GMT+0", 'rang' => 0];
    }

    ###过滤08为8
    preg_match("/\d+/", $zone[0], $zone_num);
    $zone_num = intval($zone_num[0]);

    return ['set_zone' => "Etc/GMT{$mark}{$zone_num}", 'rang' => $mark == "+" ? $zone_num : (0 - $zone_num)];
    //return "GMT{$mark}{$zone_num}";
}

#通过ip获取国家和城市
function ip_zone($ip)
{
    $url = "http://ip-api.com/json/{$ip}?lang=zh-CN";
    $data = curl_ssl($url);
    if (!$data) {
        return ['country' => 'API', 'city' => 'ERROR'];
    }

    $data = json_decode($data, true);
    if ($data['status'] != 'success') {
        //return "API CODE ERROR";
        return ['country' => 'API', 'city' => 'CODE ERROR'];
    }

    $country = empty($data['country']) ? 'API' : $data['country'];
    $city = empty($data['city']) ? 'CODE ERROR' : $data['city'];
    return ['country' => $country, 'city' => $city];

}

//二维数组排序
function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
{
    if (is_array($arrays)) {
        foreach ($arrays as $array) {
            if (is_array($array)) {
                $key_arrays[] = $array[$sort_key];
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
    array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
    return $arrays;
}

//图片类型转换
function imgToBase64($img_file_content, $imgType)
{
    $file_content = chunk_split(base64_encode($img_file_content)); // base64编码
    switch ($imgType) {           //判读图片类型
        case 1:
            $img_type = "gif";
            break;
        case 2:
            $img_type = "jpg";
            break;
        case 3:
            $img_type = "png";
            break;
    }

    $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

    return $img_base64;
}

//根据空格换行等计算词汇数量
function countWordNum($content)
{
    $content = trim($content);
    preg_match_all('/\s{1,}/', $content, $m);
    $num = count($m[0]) + 1;
    return $num;
}

/**传入本地的当时的格式化时间YmdH 和 当时的时间戳 返回当地的时区
 * @param $time_format eg:2020091608 9月16 8点
 * @param $time
 * @return string
 */
function calculateTimeZone($time_format, $time)
{
    //计算时区 上传本地格式化的时间，和时间戳
    date_default_timezone_set("Etc/GMT-0");
    $standard = date('YmdH', $time);

    $format_d = substr($time_format, 0, 8);
    $format_h = intval(substr($time_format, -2));

    $standard_d = substr($standard, 0, 8);
    $standard_h = intval(substr($standard, -2));
    $pre = $time_format > $standard ? '-' : '+';

    if ($format_d == $standard_d) {
        $s = abs($format_h - $standard_h);
    } else {
        if ($standard_h > $format_h) {
            $s = $format_h + 24 - $standard_h;
        } else {
            $s = $standard_h + 24 - $format_h;
        }
    }

    return "Etc/GMT{$pre}{$s} ";
}

/**舍去法向下取N位小数
 * @param $data 数字
 * @param $n 保留几位小数
 */
function roundDownMoney($data, $n = 6)
{
    $data = $data * pow(10, $n);
    $data = floor($data);
    $data = $data / pow(10, $n);
    return $data;
}

/**计算某个时区的格式化日期
 * @param $format
 * @param $timezone 时区
 * @param $time 时间戳
 * @param $back 默认格式化后设置中国原时区
 */
function dateTimeZone($format, $timezone, $time = null, $back = 1)
{
    date_default_timezone_set($timezone);
    $time = !$time ? time() : $time;
    $d = date($format, $time);
    if ($back == 1) {
        date_default_timezone_set("PRC");
    }
    return $d;
}