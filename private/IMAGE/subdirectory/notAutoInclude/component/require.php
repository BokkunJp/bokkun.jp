<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);

define("DS", DIRECTORY_SEPARATOR);

// 関数定義 (初期処理用)
require_once dirname(__DIR__, 4) . DS . 'common' . DS.'InitFunction.php';
// 設定
// require_once dirname(__DIR__, 4) . DS . 'common' . DS .  'Setting.php';
require_once AddPath(dirname(__DIR__, 4) ,  AddPath('common',  'Setting.php', false), false);
// 定数・固定文言など
require_once AddPath(AddPath(dirname(__DIR__, 4), AddPath('common', 'Word'), false), "Message.php", false);
// セッション
require_once AddPath(PRIVATE_COMMON_DIR, 'Session.php', false);
// CSRF対策
require_once AddPath(PRIVATE_COMMON_DIR, "Token.php", false);
// タグ
require_once AddPath(PRIVATE_COMPONENT_DIR, "Tag.php", false);
// UA
require_once AddPath(PRIVATE_COMPONENT_DIR, 'UA.php', false);
// ヘッダーフッター
require_once AddPath(AddPath(DOCUMENT_ROOT, "common"), "Config.php", false);

// カスタムファイル

// if (fileExists()) {

// }

// 共通処理に必要なグローバル変数
$base = new private\Setting();
$ua = new UA\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];

// ファイル読み込み処理
require_once AddPath(__DIR__, "include.php", false);
