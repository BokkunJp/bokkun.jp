<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL);

define("DS", DIRECTORY_SEPARATOR);

// 関数定義 (初期処理用)
require_once dirname(__DIR__, 4) . DS . 'common' . DS.'InitFunction.php';


// パスの定義
$privatepathList = new PathApplication('word', dirname(__DIR__, 4));

// それぞれの変数セット
$privatepathList->setAll([
    'setting' => '',
    'include' => '',
    'session' => '',
    'token' => '',
    'common' => '',
    'tag' => '',
    'ua' => '',
    'config' => dirname(__DIR__, 5),
]);

// パスの追加
// ヘッダー・フッター
$privatepathList->setKey('config');
$privatepathList->methodPath('addArray', ['common', 'Config.php']);

// 定数・固定文言など
$privatepathList->setKey('word');
$privatepathList->methodPath('addArray', ['common', 'Word', 'Message.php']);

// 管理側共通(ログイン認証など)
$privatepathList->setKey('common');
$privatepathList->methodPath('addArray', ['common.php']);

// 設定
$privatepathList->setKey('setting');
$privatepathList->methodPath('addArray', ['common', 'Setting.php']);

// タグ
$privatepathList->setKey('tag');
$privatepathList->methodPath('addArray', ['common', 'Component', 'Tag.php']);

// セッション
$privatepathList->setKey('session');
$privatepathList->methodPath('addArray', ['common', 'Session.php']);

// トークン
$privatepathList->setKey('token');
$privatepathList->methodPath('addArray', ['common', 'Token.php']);

// ファイル読み込み
$privatepathList->setKey('include');
$privatepathList->methodPath('addArray', ['common', 'Include.php']);

// UA
$privatepathList->setKey('ua');
$privatepathList->methodPath('Reset');
$privatepathList->methodPath('addArray', ['common', 'Component', 'Ua.php']);

// パスの出力
$privatepathList->resetKey();

foreach ($privatepathList->get() as $path) {
    require_once $path;
}

// 共通処理に必要なグローバル変数
$base = new Private\Important\Setting();
$ua = new Private\Important\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
