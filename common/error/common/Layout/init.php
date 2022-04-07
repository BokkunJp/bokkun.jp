<?php

ini_set('error_reporting', E_ALL | ~E_STRICT);


require_once dirname(__DIR__, 3). DIRECTORY_SEPARATOR. 'InitFunction.php';

// タイトルの初期設定
$errCode = http_response_code();    // ステータスコードを出力
if (empty($title)) {
    $title = 'Page Error -';            // タイトル用に調整
    $title .= $errCode;
    $title .= '-';
}


require_once AddPath(dirname(__DIR__), 'word.php', false);
require_once AddPath(COMMON_DIR, 'Setting.php', false);
require_once AddPath(ERROR_COMMON_DIR, 'Setting.php', false);
require_once AddPath(ERROR_COMMON_DIR, "Include.php", false);

// UA判定処理 (内容はベースと同様)
$agent = new UA\UA();
define('Phone', 2);
define('PC', 1);
switch ($agent->DesignJudge()) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}
