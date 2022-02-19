<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'common/InitFunction.php';
// 設定
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'common/Setting.php';
// 定数・固定文言など
require_once AddPath(AddPath(dirname(dirname(__DIR__)), AddPath(AddPath("public", "common"), "Word"), false), "Message.php", false);
// ヘッダーフッター
require_once AddPath(AddPath(DOCUMENT_ROOT, "common"), "Config.php", false);
// UA
require_once PUBLIC_COMPONENT_DIR . '/UA.php';
// CSRF対策
require_once PUBLIC_COMMON_DIR . "/Token.php";

// カスタムファイル

// if (fileExists()) {

// }

// 共通処理に必要なグローバル変数
$base = new PublicSetting\Setting();
$ua = new UA\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
if (!isset($title)) {
    $title = basename(getcwd());
}
if (!isset($homepageTitle)) {
    $homepageTitle = htmlspecialchars($title);
}

// ファイル読み込み処理
require_once PUBLIC_COMMON_DIR . "/Include.php";
