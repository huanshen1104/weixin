<?php
//require_once 'wechat-php-sdk/wechat.class.php';
//
//$options = array(
//    'token'=>'lilvqingtest' //填写你设定的key
//);
//
//$weObj = new Wechat($options);
//$weObj->valid();
//$type = $weObj->getRev()->getRevType();

//$postStr = file_get_contents("php://input");

$dir = '/var/www/html/weixin/logs/' . date('Ymd');

if (!is_dir($dir)) {
    $res = mkdir($dir, 0777, true);
}
var_dump($res);exit;
// 完整路劲
$fullFile = $dir . '/' . 'weixin.log';

$fp = fopen($fullFile, "a");
flock($fp, LOCK_EX);
fwrite($fp, $postStr . "\r\n");
flock($fp, LOCK_UN);
fclose($fp);
exit($fullFile);


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