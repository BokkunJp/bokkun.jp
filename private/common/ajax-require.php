<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
define("DS", DS);
// 関数定義 (初期処理用)
require __DIR__ . DS . 'InitFunction.php';
// 設定
require_once AddPath(__DIR__, "Setting.php", false);
// セッション
require_once AddPath(__DIR__, "Session.php", false);

// 設定変数を管理側用に上書き
$base = new private\Setting();
// 定数・固定文言など
require_once AddPath(__DIR__, AddPath("Word", "Message.php", false), false);
require_once AddPath(PRIVATE_COMPONENT_DIR,  "Tag.php", false);

//直接のページ遷移を阻止
$request = private\Setting::JudgeAjax();

if (is_null($request)) {
    header("Content-Type: text/html; charset=UTF-8");
    require_once dirname(__DIR__) . "/common.php";
    require_once PRIVATE_COMMON_DIR . "/Include.php";

    $ajaxFlg = true;

    // UA
    require_once PRIVATE_COMPONENT_DIR . '/UA.php';
    // CSRF
    require_once PRIVATE_COMMON_DIR . "/Token.php";

    // UA判定処理
    $ua = new UA\UA();
    define('Phone', 2);
    define('PC', 1);
    switch ($ua->DesignJudge()) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}
    http_response_code(403);
    $homepageTitle = 'Forbidden';
    $contents = 'アクセスが許可されていません。';
    require_once 'layout.php';
    exit;
}

// CSRF
require_once AddPath(PRIVATE_COMMON_DIR, "Token.php", false);
