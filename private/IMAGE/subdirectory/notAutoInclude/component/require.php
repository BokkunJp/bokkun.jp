<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);

define("DS", DIRECTORY_SEPARATOR);

// 関数定義 (初期処理用)
require_once dirname(__DIR__, 4) . DS . 'common' . DS.'InitFunction.php';


// パスの定義
$privatepathList = new PathApplication('word', dirname(__DIR__, 4));

// それぞれの変数セット
$privatepathList->SetAll([
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
$privatepathList->ResetKey('config');
$privatepathList->MethodPath('AddArray', ['common', 'Config.php']);

// 定数・固定文言など
$privatepathList->ResetKey('word');
$privatepathList->MethodPath('AddArray', ['common', 'Word', 'Message.php']);

// 管理側共通(ログイン認証など)
$privatepathList->ResetKey('common');
$privatepathList->MethodPath('AddArray', ['common.php']);

// 設定
$privatepathList->ResetKey('setting');
$privatepathList->MethodPath('AddArray', ['common', 'Setting.php']);

// タグ
$privatepathList->ResetKey('tag');
$privatepathList->MethodPath('AddArray', ['common', 'Component', 'Tag.php']);

// セッション
$privatepathList->ResetKey('session');
$privatepathList->MethodPath('AddArray', ['common', 'Session.php']);

// トークン
$privatepathList->ResetKey('token');
$privatepathList->MethodPath('AddArray', ['common', 'Token.php']);

// ファイル読み込み
$privatepathList->ResetKey('include');
$privatepathList->MethodPath('Reset');
$privatepathList->MethodPath('AddArray', [__DIR__, 'include.php'], true);

// UA
$privatepathList->ResetKey('ua');
$privatepathList->MethodPath('Reset');
$privatepathList->MethodPath('AddArray', ['common', 'Component', 'UA.php']);

// パスの出力
$privatepathList->All();

foreach ($privatepathList->Get() as $path) {
    Debug($path);
    require_once $path;
}

// 共通処理に必要なグローバル変数
$base = new private\Setting();
$ua = new private\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
