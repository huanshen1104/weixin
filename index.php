<?php

require_once 'wechat-php-sdk/wechat.class.php';

$options = array(
    'token'=>'lilvqing' //填写你设定的key
);

$weObj = new Wechat($options);
$weObj->valid();
$type = $weObj->getRev()->getRevType();

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