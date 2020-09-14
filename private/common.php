<?php
require_once AddPath(AddPath(__DIR__, 'common'), AddPath('Component', 'Mail.php', false), false);

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


/**
 * AlertAdmin
 * 管理側アクセス・ログイン時のメール送信処理
 *
 * @param  string $noticeType
 * access or login
 * 
 * @return boolean|void
 */
function AlertAdmin(string $noticeType, $pageTitle)
{
    $ip = PrivateSetting\Setting::GetHostIp();
    $host = PrivateSetting\Setting::GetHostName();

    if ($noticeType === 'access') {
        $title = "管理画面アクセス通知";
        $body = "IP {$ip} ({$host}) の方から、管理画面の{$pageTitle}へアクセスがありました。";
    } else if ($noticeType === 'login') {
        $title = "管理画面ログイン通知";
        $body = "IP {$ip} ({$host})の方が、管理画面へログインされました。
意図したものでない場合は、早急にパスワードを変更ください。";
    } else {
        return false;
    }

    SendMail(['secure@bokkun.jp', $title, $body, 'サイト管理者', 'notice@bokkun.jp']);
}
