<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'InitFunction.php';
// 設定
require_once dirname(__DIR__) . "/common/Setting.php";
require_once dirname(__DIR__) . "/common.php";
// 定数・固定文言など
require_once AddPath(AddPath(AddPath(dirname(__DIR__), "common", false), "Word", false), "Message.php", false);
// ヘッダー・フッター
require_once AddPath(COMMON_DIR, "Config.php", false);
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
// UA
require_once PRIVATE_COMPONENT_DIR . '/UA.php';
// CSRF
require_once PRIVATE_COMMON_DIR . "/TokenClass.php";
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

require_once PRIVATE_COMMON_DIR . "/Include.php";
