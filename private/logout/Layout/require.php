<?php

if (!isset($_SESSION)) {
    if (PHP_OS === 'WINNT') {
        $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/session/";
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0755);
        }
        session_save_path($sessionDir);
    }
    session_start();
}

?>

<!DOCTYPE html>
<?php
/* 定義・呼び出し処理 */
ini_set('error_reporting', E_ALL | ~E_STRICT);
// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'InitFunction.php';

// パスの初期セット
$privatepathList = new PathApplication('word', dirname(__DIR__, 2));

// それぞれの変数セット
$privatepathList->SetAll(
    [
        'setting' => '',
        'include' => '',
        'session' => '',
        'token' => '',
        'common' => '',
        'ua' => '',
        'config' => dirname(__DIR__, 2),
    ]
);

// パスの追加
// ヘッダー・フッター
$privatepathList->ResetKey('config');
$privatepathList->MethodPath('AddArray', [dirname(__DIR__, 3), 'common', 'Config.php'], true);

// 定数・固定文言など
$privatepathList->ResetKey('word');
$privatepathList->MethodPath('AddArray', ['common', 'Word', 'Message.php']);

// // 管理側共通(ログイン認証など)
$privatepathList->ResetKey('common');
$privatepathList->MethodPath('AddArray', ['common.php']);

// 設定
$privatepathList->ResetKey('setting');
$privatepathList->MethodPath('AddArray', ['common', 'Setting.php']);

// セッション
$privatepathList->ResetKey('session');
$privatepathList->MethodPath('AddArray', ['common', 'Session.php']);

// トークン
$privatepathList->ResetKey('token');
$privatepathList->MethodPath('AddArray', ['common', 'Token.php']);

// ファイル読み込み
$privatepathList->ResetKey('include');
$privatepathList->MethodPath('AddArray', ['common', 'Include.php']);

// UA
$privatepathList->ResetKey('ua');
$privatepathList->MethodPath('AddArray', ['common', 'Component', 'UA.php']);

// パスの出力
$privatepathList->All();
foreach ($privatepathList->Get() as $path) {
    require_once $path;
}

// UA判定処理
$ua = new private\UA();
define('Phone', 2);
define('PC', 1);
switch ($ua->DesignJudge()) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}