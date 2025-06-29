<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL);
// 関数定義 (初期処理用)
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'InitFunction.php';
// 設定
require_once dirname(__DIR__) . '/Setting.php';
// セッション
require_once dirname(__DIR__) . '/Session.php';
// キャッシュ
require_once dirname(__DIR__) . "/Cache.php";
// 定数・固定文言など
$wordPath = new \Path(dirname(__DIR__));
$configPath = new \Path(dirname(__DIR__, 3));
$configPath->setPathEnd();
$configPath->addArray(["common", "Config.php"]);
$wordPath->addArray(["Word", "Message.php"]);
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
require_once $wordPath->get();

if (!isset($title)) {
    $title = NOW_PAGE;
}
if (!isset($homepageTitle)) {
    $homepageTitle = htmlspecialchars($title);
}

// ヘッダーフッター
require_once $configPath->get();
// UA
require_once PUBLIC_COMPONENT_DIR . 'Ua.php';
$ua = new Public\Important\UA();

// ファイル読み込み処理
require_once PUBLIC_COMMON_DIR . "/Include.php";
// CSRF対策
require_once PUBLIC_COMMON_DIR . "/Token.php";

// カスタムファイル

// if (fileExists()) {

// }

// 共通処理に必要なグローバル変数
$base = new Public\Important\Setting();
