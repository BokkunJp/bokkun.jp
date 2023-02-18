<?php

if (!isset($_SESSION)) {
    session_start();
}

?>

<!DOCTYPE html>
<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'InitFunction.php';
// 定数・固定文言など
require_once AddPath(AddPath(AddPath(dirname(__DIR__), "common", false), "Word", false), "Message.php", false);
// 設定
require_once AddPath(PRIVATE_COMMON_DIR, "Setting.php", false);
// セッション
require_once AddPath(PRIVATE_COMMON_DIR, "Session.php", false);
// CSRF
require_once AddPath(PRIVATE_COMMON_DIR, "Token.php", false);
// 必要なメソッドの読み込み
require_once AddPath(PRIVATE_COMMON_DIR, "Include.php", false);
// タグ
require_once AddPath(PRIVATE_COMPONENT_DIR, "Tag.php", false);

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