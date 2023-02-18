<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
define('DS', DIRECTORY_SEPARATOR);
// 関数定義 (初期処理用)
require dirname(__DIR__) . DS . 'common' . DS . 'InitFunction.php';
// 定数・固定文言など
require_once AddPath(AddPath(AddPath(dirname(__DIR__), "common", false), "Word", false), "Message.php", false);
// ヘッダー・フッター
require_once AddPath(COMMON_DIR, "Config.php", false);
// 設定
require_once AddPath(PRIVATE_COMMON_DIR, "Setting.php", false);
// セッション
require_once AddPath(PRIVATE_COMMON_DIR, "Session.php", false);
// CSRF
require_once AddPath(PRIVATE_COMMON_DIR, "Token.php", false);
// ファイル読み込み
require_once AddPath(PRIVATE_COMMON_DIR, "Include.php", false);
// 管理側共通処理
require_once AddPath(PRIVATE_DIR, "common.php", false);
// サイト設定(ヘッダー・フッター)
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
// UA
require_once AddPath(PRIVATE_COMPONENT_DIR, "UA.php", false);

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
