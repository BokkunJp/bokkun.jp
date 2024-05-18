<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);

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
$privatepathList->resetKey('config');
$privatepathList->methodPath('addArray', ['common', 'Config.php']);

// 定数・固定文言など
$privatepathList->resetKey('word');
$privatepathList->methodPath('addArray', ['common', 'Word', 'Message.php']);

// 管理側共通(ログイン認証など)
$privatepathList->resetKey('common');
$privatepathList->methodPath('addArray', ['common.php']);

// 設定
$privatepathList->resetKey('setting');
$privatepathList->methodPath('addArray', ['common', 'Setting.php']);

// タグ
$privatepathList->resetKey('tag');
$privatepathList->methodPath('addArray', ['common', 'Component', 'Tag.php']);

// セッション
$privatepathList->resetKey('session');
$privatepathList->methodPath('addArray', ['common', 'Session.php']);

// トークン
$privatepathList->resetKey('token');
$privatepathList->methodPath('addArray', ['common', 'Token.php']);

// ファイル読み込み
$privatepathList->resetKey('include');
$privatepathList->methodPath('Reset');
$privatepathList->methodPath('addArray', [__DIR__, 'include.php'], true);

// UA
$privatepathList->resetKey('ua');
$privatepathList->methodPath('Reset');
$privatepathList->methodPath('addArray', ['common', 'Component', 'UA.php']);

// パスの出力
$privatepathList->all();

foreach ($privatepathList->get() as $path) {
    debug($path);
    require_once $path;
}

// 共通処理に必要なグローバル変数
$base = new Private\Important\Setting();
$ua = new Private\Important\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
