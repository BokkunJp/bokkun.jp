<?php
if (!isset($_SESSION)) {
    session_start();
}
$session = SessionRead();
$post = $_POST;

if (!isset($secure)) {
    $secure = null;
}
if (isset($session['admin']['secure'])) {
    $secure = $session['admin']['secure'];
}
if (isset($session['admin']['page'])) {
    $secure = $session['admin']['page'];
}
if ($secure !== true) {
    require_once __DIR__. '/secure.php';
}

function SessionWrite($session) {
    $_SESSION = $session;
}

function SessionAdd($sessionElm, $sessionVal) {
    $_SESSION[$sessionElm] = $sessionVal;
}

function SessionRead($sessionElm=null) {
    if (isset($sessionElm)) {
        return $_SESSION[$sessionElm];
    } else {
        return $_SESSION;
    }
}

function MovePage($loginFlg=true) {
    $session = SessionRead();
    $ret = array();
    $ret['message'] = '管理画面';
    $ret['URL'] = 'admin.php';

    return $ret;
}