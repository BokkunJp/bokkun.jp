<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
define("DS", DIRECTORY_SEPARATOR);
// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DS . 'public' . DS .'common' . DS . 'InitFunction.php';
// パスの定義
$publicPathList = new PathApplication('word', dirname(__DIR__, 2));

// それぞれの変数セット
$publicPathList->SetAll([
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
$publicPathList->ResetKey('config');
$publicPathList->MethodPath('AddArray', ['common', 'Config.php']);

// 定数・固定文言など
$publicPathList->ResetKey('word');
$publicPathList->MethodPath('AddArray', ['public', 'common', 'Word', 'Message.php']);

// 設定
$publicPathList->ResetKey('setting');
$publicPathList->MethodPath('AddArray', ['public', 'common', 'Setting.php']);

// タグ
$publicPathList->ResetKey('tag');
$publicPathList->MethodPath('AddArray', ['public', 'common', 'Component', 'Tag.php']);

// セッション
$publicPathList->ResetKey('session');
$publicPathList->MethodPath('AddArray', ['public', 'common', 'Session.php']);

// トークン
$publicPathList->ResetKey('token');
$publicPathList->MethodPath('AddArray', ['public', 'common', 'Token.php']);

// ファイル読み込み
$publicPathList->ResetKey('include');
$publicPathList->MethodPath('AddArray', ['public', 'common', 'Include.php']);

// UA
$publicPathList->ResetKey('ua');
$publicPathList->MethodPath('AddArray', ['public', 'common', 'Component', 'UA.php']);

// パスの出力
$publicPathList->All();
foreach ($publicPathList->Get() as $key => $path) {
    require_once $path;
    if ($key === 'ua') {
    $ua = new Public\UA();
    }
}

// カスタムファイル

// if (fileExists()) {

// }

// 共通処理に必要なグローバル変数
$base = new public\Setting();

// UA設定
$ua = new Public\UA();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
$homepageTitle = basename(getcwd());
$title = htmlspecialchars($homepageTitle);

// ファイル読み込み処理
require_once PUBLIC_COMMON_DIR . "/Include.php";
