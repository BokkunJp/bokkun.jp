<?php

require_once __DIR__ . '/Layout/require.php';

$title = 'ログアウト';
// アクセス警告メール
alertAdmin('access', $title);
$reset = explode(basename(__FILE__), '.')[0];

$logoutPath = new \Path(__DIR__);
$logoutPath->add("Layout");
$logoutPath->setPathEnd();
$logoutPath->add("layout.php");

require_once $logoutPath->get();

