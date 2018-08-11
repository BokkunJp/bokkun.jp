<?php
$url = $_SERVER["REQUEST_URI"];
//Publicであればpublic/Setting.PHPを
//Adminであればadmin/Seetting.phpを
// どちらもなければこのページを参照(Topページ)
if (strpos($url, 'public')) {
  require_once ('public/Setting.php');
  return true;
} else if (strpos($url, 'admin')) {
  require_once ('admin/Setting.php');
  return true;
}

if (isset($_SERVER['HTTPS'])) {
    $http = '//';
} else {
    $http = 'http://';
}
$domain = $_SERVER['SERVER_NAME'];
$url = $http.$domain;

// 定数などの定義
$COMMON_DIR = __DIR__;
$FUNCTION_DIR = $COMMON_DIR. '/Function';

function GetSelf() {
    return $_SERVER['PHP_SELF'];
}

function GetPrevPage() {
    return filter_input(INPUT_SERVER, 'HTTP_REFERER');
}

function GetURI() {
    return $_SERVER['REQUEST_URI'];
}

function PageValid() {

}
