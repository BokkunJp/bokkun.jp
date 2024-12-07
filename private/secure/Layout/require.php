<!DOCTYPE html>
<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL);
// 設定
require_once dirname(__DIR__, 2) . "/common/Setting.php";
require_once dirname(__DIR__, 2) . "/common.php";
// 定数・固定文言など
$commonWordPath = new \Path(dirname(__DIR__, 2));
$commonWordPath->addArray(["common", "Word", "Message.php"]);
require_once $commonWordPath->get();
// ヘッダー・フッター
$configPath = new \Path('');
$configPath->addArray([COMMON_DIR, "Config.php"], true);
require_once $configPath->get();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
// UA
require_once PRIVATE_DIR_LIST['Component'] . '/Ua.php';
// CSRF
require_once PRIVATE_COMMON_DIR . "/Token.php";

// 設定ファイルを管理側用に上書き
$base = new Private\Important\Setting();

// UA判定処理
$ua = new Private\Important\UA();
define('Phone', 2);
define('PC', 1);
switch ($ua->judgeDevice()) {
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
