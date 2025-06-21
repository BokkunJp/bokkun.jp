<?php

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL);
define('DS', DIRECTORY_SEPARATOR);
// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DS . 'public' . DS. 'common' . DS. 'InitFunction.php';
// 設定
require_once dirname(__DIR__, 2) . DS . 'public' . DS. 'common' . DS. 'Setting.php';

$homepageTitle = NOW_PAGE;
$title = htmlspecialchars($homepageTitle);

// パスの定義
$publicPathList = new PathApplication('word', dirname(__DIR__, 2));

// それぞれの変数セット
$publicPathList->setAll([
    'setting' => '',
    'tag' => '',
    'config' => '',
    'ua' => '',
    'include' => '',
    'session' => '',
    'token' => '',
]);

// パスの追加
// ヘッダー・フッター
$publicPathList->setKey('config');
$publicPathList->methodPath('addArray', ['common', 'Config.php']);

// 定数・固定文言など
$publicPathList->setKey('word');
$publicPathList->methodPath('addArray', ['public', 'common', 'Word', 'Message.php']);

// 設定
$publicPathList->setKey('setting');
$publicPathList->methodPath('addArray', ['public', 'common', 'Setting.php']);

// タグ
$publicPathList->setKey('tag');
$publicPathList->methodPath('addArray', ['public', 'common', 'Component', 'Tag.php']);

// セッション
$publicPathList->setKey('session');
$publicPathList->methodPath('addArray', ['public', 'common', 'Session.php']);

// トークン
$publicPathList->setKey('token');
$publicPathList->methodPath('addArray', ['public', 'common', 'Token.php']);

// ファイル読み込み
$publicPathList->setKey('include');
$publicPathList->methodPath('addArray', ['public', 'common', 'Include.php']);

// UA
$publicPathList->setKey('ua');
$publicPathList->methodPath('addArray', ['public', 'common', 'Component', 'Ua.php']);

// パスの出力
$publicPathList->resetKey();
foreach ($publicPathList->get() as $key => $path) {
    require_once $path;
    if ($key === 'ua') {
        $ua = new Public\Important\UA();
    }
}

// 共通処理に必要なグローバル変数
$base = new Public\Important\Setting();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];

// ファイル読み込み処理
require_once PUBLIC_COMMON_DIR . "/Include.php";
