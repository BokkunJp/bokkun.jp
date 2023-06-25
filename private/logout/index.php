<?php

require_once __DIR__ . '/Layout/require.php';
require_once __DIR__ . '/Layout/init.php';

$title = 'ログアウト';
// アクセス警告メール
AlertAdmin('access', $title);
$reset = explode(basename(__FILE__), '.')[0];

$logoutPath = new \Path(__DIR__);
$logoutPath->Add("logout");
$logoutPath->SetPathEnd();
$logoutPath->Add("logout.php");
require_once$logoutPath->Get();

