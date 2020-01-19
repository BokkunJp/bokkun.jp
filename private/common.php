<?php
if (!isset($_SESSION)) {
    session_start();

}
$session = new PrivateSetting\Session();
$post = $_POST;

if (!isset($secure)) {
    $secure = null;
}
if (isset($session->Read('admin')['secure'])) {
    $secure = $session->Read('admin')['secure'];
}
if (isset($session->Read('admin')['page'])) {
    $secure = $session->Read('admin')['page'];
}
if ($secure !== true) {
    require_once __DIR__. '/secure.php';
}

function MovePage($message='管理画面', $url='admin.php') {
    $ret = array();
    $ret['message'] = $message;
    $ret['URL'] = $url;

    return $ret;
}