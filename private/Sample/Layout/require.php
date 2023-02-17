<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'common/InitFunction.php';
// 定数・固定文言など
require_once AddPath(AddPath(dirname(__DIR__, 2), AddPath(AddPath("public", "common"), "Word"), false), "Message.php", false);
// 設定
require_once AddPath(PUBLIC_COMMON_DIR, "Setting.php", false);
// CSRF対策
require_once AddPath(PUBLIC_COMMON_DIR, "Token.php", false);
// ファイル読み込み処理
require_once AddPath(PUBLIC_COMMON_DIR, "Include.php", false);
// UA
require_once AddPath(PUBLIC_COMPONENT_DIR, "UA.php", false);
// ヘッダーフッター
require_once AddPath(AddPath(DOCUMENT_ROOT, "common"), "Config.php", false);

// カスタムファイル

// if (fileExists()) {

// }

// 共通処理に必要なグローバル変数
$base = new public\Setting();
$ua = new UA\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
if (!isset($title)) {
    $title = basename(getcwd());
}
if (!isset($homepageTitle)) {
    $homepageTitle = htmlspecialchars($title);
}
