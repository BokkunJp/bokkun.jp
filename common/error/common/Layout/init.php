<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(__DIR__). '/Setting.php';
require_once COMMON_DIR. "/Include.php";

// タイトルの初期設定
$errCode = http_response_code();    // ステータスコードを出力
$title = 'Page Error -';            // タイトル用に調整
$title .= $errCode;
$title .= '-';

// スマホ判定処理 (内容はベースと同様)
$agent = new UA\UA();
define('Phone', 2);
define('PC', 1);
$statusCode = $agent->designJudege();
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