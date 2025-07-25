<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL);
define("DS", DIRECTORY_SEPARATOR);
$ajaxFlg = true;
// 関数定義 (初期処理用)
require_once dirname(__DIR__) . DS . "common" . DS . "InitFunction.php";
// 定数・固定文言など
require_once dirname(__DIR__) . DS . "common" . DS . "Word" . DS . "Message.php";
// 設定
$ajaxPath = new \PathApplication("setting", PUBLIC_COMMON_DIR);
$ajaxPath->setAll([
    'session' => '',
    'include' => '',
    'tag' => PUBLIC_COMPONENT_DIR,
]);
$ajaxPath->setKey("setting");
$ajaxPath->methodPath("SetPathEnd");
$ajaxPath->methodPath("Add", "Setting.php");

$ajaxPath->setKey("session");
$ajaxPath->methodPath("SetPathEnd");
$ajaxPath->methodPath("Add", "Session.php");

$ajaxPath->setKey("include");
$ajaxPath->methodPath("SetPathEnd");
$ajaxPath->methodPath("Add", "Include.php");

$ajaxPath->setKey("tag");
$ajaxPath->methodPath("SetPathEnd");
$ajaxPath->methodPath("Add", "Tag.php");

$ajaxPath->resetKey();
foreach ($ajaxPath->get() as $path) {
    require_once $path;
}

//直接のページ遷移を阻止
$request = Public\Important\Setting::judgeAjax();
if (is_null($request)) {
    http_response_code(403);
    $homepageTitle = 'Forbidden';
    $contents = 'アクセスが許可されていません。';
    require_once 'layout/layout.php';
    exit;
}

// CSRF
require_once COMMON_DIR . DS . "Token.php";
