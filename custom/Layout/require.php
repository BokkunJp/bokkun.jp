<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
define('DS', DIRECTORY_SEPARATOR);
// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DS . 'public' . DS. 'common' . DS. 'InitFunction.php';
// 設定
require_once dirname(__DIR__, 2) . DS . 'public' . DS. 'common' . DS. 'Setting.php';
// 定数・固定文言など
require_once AddPath(AddPath(dirname(__DIR__, 2), AddPath("public", AddPath("common", "Word")), false), "Message.php", false);
// ヘッダーフッター
require_once AddPath(COMMON_DIR, "Config.php", false);
// セッション
require_once AddPath(PUBLIC_COMMON_DIR, 'Session.php', false);
// CSRF対策
require_once AddPath(PUBLIC_COMMON_DIR, 'Token.php', false);
// UA
require_once AddPath(PUBLIC_COMPONENT_DIR, 'UA.php', false);

// 共通処理に必要なグローバル変数
$base = new public\Setting();
$ua = new UA\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
$homepageTitle = basename(getcwd());
$title = htmlspecialchars($homepageTitle);

// ファイル読み込み処理
require_once PUBLIC_COMMON_DIR . "/Include.php";
