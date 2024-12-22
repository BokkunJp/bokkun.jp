<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL);

$consoleFlg = true;
// 関数定義 (初期処理用)
require_once __DIR__ . DIRECTORY_SEPARATOR . 'InitFunction.php';
// 設定
require_once __DIR__ . '/Setting.php';
// セッション
require_once __DIR__ . '/Session.php';
// キャッシュ
require_once __DIR__ . "/Cache.php";
// 定数・固定文言など
$wordPath = new \Path(__DIR__);
$configPath = new \Path(dirname(__DIR__, 2));
$configPath->setPathEnd();
$configPath->addArray(["common", "Config.php"]);
$wordPath->addArray(["Word", "Message.php"]);
