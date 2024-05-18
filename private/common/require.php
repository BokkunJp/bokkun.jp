<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
define('DS', DIRECTORY_SEPARATOR);
// 関数定義 (初期処理用)
require_once dirname(__DIR__) . DS . 'common' . DS . 'InitFunction.php';

// パスの初期セット
$privatepathList = new PathApplication('word', dirname(__DIR__));

// それぞれの変数セット
$privatepathList->setAll(
    [
        'setting' => '',
        'include' => '',
        'session' => '',
        'token' => '',
        'cache' => '',
        'common' => '',
        'ua' => '',
        'config' => dirname(__DIR__, 2),
    ]
);

// パスの追加
// 管理側共通(ログイン認証など)
$privatepathList->resetKey('common');
$privatepathList->methodPath('addArray', ['common.php']);

// 定数・固定文言など
$privatepathList->resetKey('word');
$privatepathList->methodPath('addArray', ['common', 'Word', 'Message.php']);

// UA
$privatepathList->resetKey('ua');
$privatepathList->methodPath('addArray', ['common', 'Component', 'UA.php']);

$privateList = [
    'config' => 'Config.php',
    'setting' => 'Setting.php',
    'session' => 'Session.php',
    'token' => 'Token.php',
    'cache' => 'Cache.php',
    'include' => 'Include.php',
];

// ヘッダー・フッター
// 設定
// セッション
// トークン
// キャッシュ
// ファイル読み込み
foreach ($privateList as $key => $file) {
    $privatepathList->resetKey($key);
    $privatepathList->methodPath('addArray', ['common', $file]);
}

// パスの出力
$privatepathList->all();
foreach ($privatepathList->get() as $path) {
    require_once $path;
}

// 設定ファイルを管理側用に上書き
$base = new Private\Important\Setting();

// UA判定処理
$ua = new Private\Important\UA();
define('Phone', 2);
define('PC', 1);
switch ($ua->judgeDevice()) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}
