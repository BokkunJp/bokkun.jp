<?php
define("DS", DIRECTORY_SEPARATOR);

/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DS . 'public' . DS . 'common/InitFunction.php';

if (!isset($homepageTitle)) {
    $homepageTitle = basename(getcwd());
}
if (!isset($title)) {
    $title = htmlspecialchars($homepageTitle);
}

// パスの定義
$privatepathList = new PathApplication('word', dirname(__DIR__, 2));

// それぞれの変数セット
$privatepathList->SetAll([
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
$privatepathList->ResetKey('config');
$privatepathList->MethodPath('AddArray', ['common', 'Config.php']);

// 定数・固定文言など
$privatepathList->ResetKey('word');
$privatepathList->MethodPath('AddArray', ['public', 'common', 'Word', 'Message.php']);

// 設定
$privatepathList->ResetKey('setting');
$privatepathList->MethodPath('AddArray', ['public', 'common', 'Setting.php']);

// タグ
$privatepathList->ResetKey('tag');
$privatepathList->MethodPath('AddArray', ['public', 'common', 'Component', 'Tag.php']);

// セッション
$privatepathList->ResetKey('session');
$privatepathList->MethodPath('AddArray', ['public', 'common', 'Session.php']);

// トークン
$privatepathList->ResetKey('token');
$privatepathList->MethodPath('AddArray', ['public', 'common', 'Token.php']);

// ファイル読み込み
$privatepathList->ResetKey('include');
$privatepathList->MethodPath('AddArray', ['public', 'common', 'Include.php']);

// UA
$privatepathList->ResetKey('ua');
$privatepathList->MethodPath('AddArray', ['public', 'common', 'Component', 'UA.php']);

// パスの出力
$privatepathList->All();
foreach ($privatepathList->Get() as $key => $path) {
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
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];
