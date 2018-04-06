<?php
require_once 'wechat-php-sdk/wechat.class.php';

$options = array(
    'token'=>'lilvqingtest', //填写你设定的key
    'appid'=>'wx74c0d19ed5bc05dd', //填写高级调用功能的app id, 请在微信开发模式后台查询
    //'appsecret'=>'xxxxxxxxxxxxxxxxxxx', //填写高级调用功能的密钥
    //'partnerid'=>'88888888', //财付通商户身份标识，支付权限专用，没有可不填
    //'partnerkey'=>'', //财付通商户权限密钥Key，支付权限专用
    //'paysignkey'=>'' //商户签名密钥Key，支付权限专用
);

$weObj = new Wechat($options);

//$weObj->valid();
$type = $weObj->getRev()->getRevType();

//$postStr = file_get_contents("php://input");
//
//$dir = '/var/www/html/weixin/logs/' . date('Ymd');
//
//if (!is_dir($dir)) {
//    $res = mkdir($dir, 0777, true);
//}
//// 完整路劲
//$fullFile = $dir . '/' . 'weixin.log';
//
//$fp = fopen($fullFile, "a");
//flock($fp, LOCK_EX);
//fwrite($fp, '$type:'. $type . "\r\n");
//fwrite($fp, '$postStr:'. $postStr . "\r\n");
//fwrite($fp, '$weObj:'. var_export($weObj, true) . "\r\n");
//flock($fp, LOCK_UN);
//fclose($fp);

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