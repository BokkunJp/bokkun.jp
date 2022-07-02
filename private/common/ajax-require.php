<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require __DIR__ . DIRECTORY_SEPARATOR . 'InitFunction.php';
// 設定
require_once __DIR__ . DIRECTORY_SEPARATOR . "Setting.php";

// 設定変数を管理側用に上書き
$base = new PrivateSetting\Setting();
// 定数・固定文言など
require_once __DIR__ . DIRECTORY_SEPARATOR . "Word/Message.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Component/Tag.php";

//直接のページ遷移を阻止
$request = PrivateSetting\Setting::JudgeAjax();

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
require_once COMMON_DIR . "/Token.php";
