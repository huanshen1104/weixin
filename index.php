<?php

require_once 'wechat-php-sdk/wechat.class.php';

$options = array(
    'token'=>'lilvqing' //填写你设定的key
);

$weObj = new Wechat($options);
$weObj->valid();
$type = $weObj->getRev()->getRevType();
log('weixin.log', 'debug', '测试', $type);

switch($type) {
    case Wechat::MSGTYPE_TEXT:
        $weObj->text("hello, I'm lilvqing")->reply();
        exit;
        break;
    case Wechat::MSGTYPE_EVENT:
        break;
    case Wechat::MSGTYPE_IMAGE:
        break;
    default:
        $weObj->text("help info")->reply();
}

/**
 * 日志
 *
 * @param string $file
 * @param string $priority
 *       EMERG       紧急:系统无法使用  数据库连不上、redis连不上等
 *       ALERT       警告:必须采取行动
 *       CRIT        关键:关键条件
 *       ERR         错误:错误条件
 *       WARN        警告:警告条件
 *       NOTICE      注意:正常的但重要的条件
 *       INFO        信息:信息消息
 *       DEBUG       调试:调试消息
 * @param string $title  标题
 * @param mixed  $logArr  可以是字符串、数组（推荐数组）
 * @example baf_Common::log('file', 'DEBUG', '测试标题', '测试内容');
 * @return boolean
 */
function log($file, $priority, $title, $logArr = '')
{
    static $logSwitch = null;

    $priorities = array('DEBUG', 'INFO', 'WARN', 'ERR', 'CRIT', 'ALERT', 'EMERG');

    // 日志分隔符
    $split = ' || ';

    $priority = strtoupper($priority);
    if (!in_array($priority, $priorities)) {
        $priority = 'ERR';
    }

    // 可在后台控制开关 后台设置的为关闭的
    if (in_array($priority, array('DEBUG', 'INFO', 'WARN'))) {

        if ($logSwitch === null) {
            $logSwitch = '';
        }

        if (!empty($logSwitch) && !empty($logSwitch[0]) && in_array($priority, $logSwitch)) {
            return false;
        }

    }

    if (strpos($file, '/') !== false) { // 有包含路径的
        $dir = dirname($file);
        $file = basename($file);
    } else {
        //$dir = 'C:/wamp/www/act.quyundong.com/trunk/public/logs';
        $dir = '/var/www/html/weixin/logs' . date('Ymd');
    }

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // 获取IP
    if (php_sapi_name() == 'cli') {
        $ip = '127.0.0.1';  // 脚本模式，也记录一下占个位置
    } else {
        $ip = Clientip();
    }

    // 完整路劲
    $fullFile = $dir . '/' . $file . '.log';

    // 获取到上一级执行的类名::方法 或者 文件名::行号
    $arr = debug_backtrace();

    if (isset($arr[1])) { // 是在类的方法中执行
        if (isset($arr[1]['class'])) {
            $backtrace1 = $arr[1]['class'];
            $backtrace2 = $arr[1]['function'];
        } else {
            $backtrace1 = basename($arr[1]['file']);
            $backtrace2 = $arr[1]['line'];
        }
    } else { // 取文件名和行号
        $backtrace1 = basename($arr[0]['file']);
        $backtrace2 = $arr[0]['line'];
    }

    $backtrace = $backtrace1 . '::' . $backtrace2;
    // 日志内容-数组自动处理
    if (is_array($logArr)) {
        $arr = array();
        foreach ($logArr as $k => $v) {
            $v = is_array($v) ? json_encode($v) : $v;
            $arr[] = is_numeric($k) ? $v : ($k . ':' . $v);
        }
        $content = implode($split, $arr);
    } else {
        $content = $logArr;
    }

    // 去除换行，确保每条日志是一行
    $content = preg_replace('/(\r\n)|\r|\n/', ' ', $content);

    // 完整日志
    $str = date('Y-m-d H:i:s') . ' [' . $priority . '] trace:' . getTraceId() . ' ' . $backtrace . ' ' .  $ip . ' [' . $title . ']';

    if ($content) {
        $str .= ' == ' . $content;
    }

    $fp = fopen($fullFile, "a");
    flock($fp, LOCK_EX);
    fwrite($fp, $str . "\r\n");
    flock($fp, LOCK_UN);
    fclose($fp);

    return true;
}

/**
 *
 * @return String
 */
function getTraceId(){
    return CURRENT_TIMESTAMP . rand(10000,99999);
}

function Clientip(){
    static $onlineip = '';
    if ($onlineip) {
        return $onlineip;
    }
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    preg_match('/[\d\.]{7,15}/', $onlineip, $onlineipmatches);
    $onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
    return $onlineip;
}