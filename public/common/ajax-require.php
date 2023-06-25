<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
define("DS", DIRECTORY_SEPARATOR);
// 関数定義 (初期処理用)
require_once dirname(__DIR__) . DS . "common" . DS . "InitFunction.php";
// 定数・固定文言など
require_once dirname(__DIR__) . DS . "common" . DS . "Word" . DS . "Message.php";
// 設定
$ajaxPath = new \PathApplication("setting", PUBLIC_COMMON_DIR);
$ajaxPath->SetAll([
    'session' => '',
    'tag' => PUBLIC_COMPONENT_DIR
]);
$ajaxPath->ResetKey("setting");
$ajaxPath->MethodPath("SetPathEnd");
$ajaxPath->MethodPath("Add", "Setting.php");

$ajaxPath->ResetKey("session");
$ajaxPath->MethodPath("SetPathEnd");
$ajaxPath->MethodPath("Add", "Session.php");

$ajaxPath->ResetKey("tag");
$ajaxPath->MethodPath("SetPathEnd");
$ajaxPath->MethodPath("Add", "Tag.php");

$ajaxPath->All();
foreach ($ajaxPath->Get() as $path) {
    require_once $path;
}

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
require_once COMMON_DIR . DS . "Token.php";
