<?php

// 初期設定を記述
$homepageTitle = htmlspecialchars(basename(__DIR__));
$title = 'メンテナンス中';
http_response_code(503);
require_once dirname(__DIR__). '/common/layout/layout.php';
