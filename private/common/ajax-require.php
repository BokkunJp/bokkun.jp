<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
//直接のページ遷移を阻止
$request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
if ($request !== 'xmlhttprequest') {
    echo '不正な遷移です。';
    exit;
}
// 関数定義 (初期処理用)
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'InitFunction.php';
// 設定
require_once dirname(__DIR__) . "/common/Setting.php";
require_once dirname(__DIR__) . "/common.php";
// 定数・固定文言など
require_once AddPath(AddPath(AddPath(dirname(__DIR__), "common", false), "Word", false), "Message.php", false);
// CSRF
require_once PRIVATE_COMMON_DIR . "/Token.php";
