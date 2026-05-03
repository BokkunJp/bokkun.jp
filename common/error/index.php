<?php

// 初期設定を記述
$homepageTitle = htmlspecialchars(basename(__DIR__));

if (PHP_OS !== 'WINNT') {
    if (isset($_SERVER['REDIRECT_STATUS'])) {
        $redirectStatus = (int)$_SERVER['REDIRECT_STATUS'];
    } else {
        $redirectStatus = 500;
    }
    http_response_code($redirectStatus);
}

require_once __DIR__. '/common/layout/layout.php';
