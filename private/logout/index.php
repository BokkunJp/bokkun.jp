<?php

require_once __DIR__ . '/Layout/require.php';
require_once __DIR__ . '/Layout/init.php';

$title = 'ログアウト';
// アクセス警告メール
AlertAdmin('access', $title);
$reset = explode(basename(__FILE__), '.')[0];

require_once AddPath(AddPath(__DIR__, 'Layout'), 'layout.php', false);