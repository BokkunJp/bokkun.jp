<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require_once __DIR__ . DIRECTORY_SEPARATOR . 'InitFunction.php';
// 設定
require_once __DIR__. "/Setting.php";
// セッション
$sessionPath = new \Path(__DIR__);
$sessionPath->SetPathEnd();
$sessionPath->Add('Session.php');
require_once $sessionPath->Get();
// 設定変数を管理側用に上書き
$base = new private\Setting();
// 定数・固定文言など
$commonWordPath = new \Path(dirname(__DIR__, 2));
$commonWordPath->AddArray(["common", "Word", "Message.php"]);
$privateCommonWordPath = new \Path(__DIR__);
$privateCommonWordPath->AddArray(["Word", "Message.php"]);
require_once $privateCommonWordPath->Get();

// タグ
$tagPath = new \Path(PRIVATE_COMPONENT_DIR);
$tagPath->SetPathEnd();
$tagPath->Add("Tag.php");
require_once $tagPath->Get();

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

// タグ
$tagPath = new \Path(PRIVATE_COMMON_DIR);
$tagPath->SetPathEnd();
$tagPath->Add("ajax-include.php");
require_once $tagPath->Get();

// CSRF
require_once PRIVATE_COMMON_DIR . "/Token.php";
