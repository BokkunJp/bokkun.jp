<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'InitFunction.php';
// 設定
require_once dirname(__DIR__) . "/common/Setting.php";
// 定数・固定文言など
require_once dirname(__DIR__) . "/common/Word/Message.php";
require_once dirname(__DIR__) . "/common/Component/Tag.php";

//直接のページ遷移を阻止
$request = public\Setting::JudgeAjax();
if (is_null($request)) {
    http_response_code(403);
    $homepageTitle = 'Forbidden';
    $contents = 'アクセスが許可されていません。';
    require_once 'Layout/layout.php';
    exit;
}

// CSRF
require_once COMMON_DIR . "/Token.php";
