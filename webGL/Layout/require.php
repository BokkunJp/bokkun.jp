<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
define("DS", DIRECTORY_SEPARATOR);
// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DS . 'public' . DS .'common' . DS . 'InitFunction.php';
// 定数・固定文言など
$wordPath = new \Path(dirname(__DIR__));
$configPath = new \Path(dirname(__DIR__, 3));
$configPath->SetPathEnd();
$configPath->AddArray(["common", "Config.php"]);
$wordPath->AddArray(["Word", "Message.php"]);
require_once $wordPath->Get();
// ヘッダーフッター
require_once $configPath->Get();
// UA
require_once PUBLIC_COMPONENT_DIR . 'UA.php';
// 設定
$settingPath = new \Path(PUBLIC_COMMON_DIR);
$tokenPath = new \Path($settingPath->Get());
$settingPath->SetPathEnd();
$settingPath->Add("Settng.php");
require_once $settingPath->Get();
// CSRF対策
$tokenPath->SetPathEnd();
$tokenPath->Add("Token.php");
require_once $tokenPath->Get();

// カスタムファイル

// if (fileExists()) {

// }

// 共通処理に必要なグローバル変数
$base = new public\Setting();

// UA設定
$ua = new UA\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
$homepageTitle = basename(getcwd());
$title = htmlspecialchars($homepageTitle);

// ファイル読み込み処理
require_once PUBLIC_COMMON_DIR . "/Include.php";
