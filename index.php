<?php
  valid();  

  function valid($return = false) {
        $echoStr = isset($_GET["echostr"]) ? $_GET["echostr"] : '';
        if ($return) {
            if ($echoStr) {
                if (checkSignature())
                    return $echoStr;
                else
                    return false;
            } else
                return checkSignature();
        } else {
            if ($echoStr) {
                if (checkSignature())
                    die($echoStr);
                else
                    die('no access');
            } else {
                if (checkSignature())
                    return true;
                else
                    die('no access');
            }
        }
        return false;
  }

  function checkSignature() {
        $signature = isset($_GET["signature"]) ? $_GET["signature"] : '';
        $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
        $nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : '';

        $token = 'lilvqing';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
