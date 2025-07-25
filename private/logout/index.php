<?php

require_once __DIR__ . '/layout/require.php';

$title = 'ログアウト';
// アクセス警告メール
alertAdmin('access', $title);
$reset = explode(basename(__FILE__), '.')[0];

$logoutPath = new \Path(__DIR__);
$logoutPath->add("layout");
$logoutPath->setPathEnd();
$logoutPath->add("layout.php");

require_once $logoutPath->get();

