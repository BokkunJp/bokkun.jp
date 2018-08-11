<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(dirname(__DIR__)). '/common/Setting.php';
require_once COMMON_DIR. "/Include.php";

// タイトルの初期設定
if (isset($homepageTitle)) {
    $title = htmlspecialchars($homepageTitle);
} else {
    $title = htmlspecialchars(basename(__DIR__)); 
}
$ua = new UA\UA();
define('Phone', 2);
define('PC', 1);
$statusCode = $ua->designJudege();
switch ($statusCode) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}