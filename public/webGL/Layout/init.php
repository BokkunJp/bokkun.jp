<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(dirname(__DIR__)). '/common/Setting.php';
require_once PUBLIC_COMMON_DIR. "/Include.php";

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
