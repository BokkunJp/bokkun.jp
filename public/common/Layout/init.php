<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(__DIR__). '/Setting.php';
require_once COMMON_DIR. "/Include.php";
require_once FUNCTION_DIR. '/UA.php';
require_once COMMON_DIR. "/Token.php";

// タイトルの初期設定
if (isset($homepageTitle)) {
    $title = htmlspecialchars($homepageTitle);
} else {
    $title = htmlspecialchars(basename(__DIR__));
}
// ユーザーエージェントの設定
$ua = new UA\UA();
define('Phone', 2);
define('PC', 1);
$statusCode = $ua->DesignJudege();
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

// 文字列から特定の文字列を切り取る関数 (完成・検証後にSetting.phpに移動)
function StrExtraction($str, $target, $state) {
}
// パンくずリストの生成 (完成・検証後にSetting.phpに移動)
$dir = scandir(__DIR__);
$realPath = getcwd();
$currentDir = $realPath;
$count = 0;
while (1) {
    $breadCrumbList[$count] = array();
    $breadCrumbList[$count]['title'] = basename($currentDir);
    $breadCrumbList[$count]['path'] = $currentDir;
    if (basename($currentDir) === $domain) {
        break;
    } else {
        $count++;
    }
    $currentDir = dirname($currentDir);
}
