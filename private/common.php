<?php

$mailPath = new \Path(PRIVATE_COMPONENT_DIR);
$mailPath->setPathEnd();
$mailPath->add('Mail.php');
require_once $mailPath->get();

$session = new Private\Important\Session();
$post = Private\Important\Setting::getPosts();
$secure = null;

if (isset($session->read('admin')['secure'])) {
    $secure = $session->read('admin')['secure'];
}
if (isset($session->read('admin')['page'])) {
    $secure = $session->read('admin')['page'];
}

// $secure = false;
if ($secure !== true) {
    require_once __DIR__. '/secure/index.php';
}

/**
 * AlertAdmin
 * 管理側アクセス・ログイン時のメール送信処理
 *
 * @param  string $noticeType
 * access or login
 *
 * @return void
 */
function AlertAdmin(string $noticeType, $pageTitle):void
{
    $domain = Private\Important\Setting::GetDomain();
    $ip = Private\Important\Setting::GetHostIp();
    $host = Private\Important\Setting::GetHostName();

    if ($noticeType === 'access') {
        $title = "管理画面アクセス通知";
        $body = "対象のドメイン: {$domain}

IP {$ip} ({$host}) の方から、管理画面の{$pageTitle}へアクセスがありました。";
    } elseif ($noticeType === 'login') {
        $title = "管理画面ログイン通知";
        $body = "対象のドメイン: {$domain}

IP {$ip} ({$host})の方が、管理画面へログインを試みました。
十分にご注意ください。";
    } elseif ($noticeType === 'login_success') {
        $title = "管理画面ログイン通知";
        $body = "対象のドメイン: {$domain}

IP {$ip} ({$host})の方が、管理画面へログインされました。
意図したものでない場合は、早急にパスワードを変更ください。";
    } else {
        $noticeType = 'no_send';
    }

    if ($noticeType === 'no_send') {
        sendMail(['secure@bokkun.jp', $title, $body, 'サイト管理者', 'notice@bokkun.jp']);
    }
}
