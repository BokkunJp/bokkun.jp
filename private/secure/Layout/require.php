<?php

// セッションスタート
if (!isset($_SESSION)) {
    if (PHP_OS === 'WINNT') {
        $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/";
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0755);
            $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/session/";
            if (!is_dir($sessionDir)) {
                mkdir($sessionDir, 0755);
            } else {
                $sessionDir .= '/session/';
            }
        }
        session_save_path($sessionDir);
    }
    session_start();
}
?>

<!DOCTYPE html>
<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
// require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'InitFunction.php';
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
require_once PRIVATE_COMPONENT_DIR . '/UA.php';
// CSRF
require_once PRIVATE_COMMON_DIR . "/Token.php";
require_once PRIVATE_COMMON_DIR . "/Token.php";

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
