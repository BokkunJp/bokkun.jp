<?php
session_start();
$session = SessionRead();
$post = $_POST;

if (!isset($secure)) {
    $secure = null;
}
if (isset($session['admin']['secure'])) {
    $secure = $session['admin']['secure'];
}
if (isset($session['front']['count'])) {
    $secure = $session['front']['count'];
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

function MovePage() {
    $ret = array();
    SessionRead();
    $ret['message'] = '管理画面';
    $ret['URL'] = 'create/';

    return $ret;
}