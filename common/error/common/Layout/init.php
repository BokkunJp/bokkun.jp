<!DOCTYPE html>
<?php

ini_set('error_reporting', E_ALL | ~E_STRICT);


require_once dirname(__DIR__, 3). DIRECTORY_SEPARATOR. 'InitFunction.php';

// タイトルの初期設定
$errCode = http_response_code();    // ステータスコードを出力
if (empty($title)) {
    $title = 'Page Error -';            // タイトル用に調整
    $title .= $errCode;
    $title .= '-';
}

$initPathList = new \PathApplication('word', dirname(__DIR__));
$initPathList->SetAll([
    'setting' => dirname(__DIR__, 3),
    'error_setting' => dirname(__DIR__),
    'error_include' => ''
]);
$initPathList->ResetKey('word');
$initPathList->MethodPath('SetPathEnd');
$initPathList->MethodPath('Add', 'word.php');
$initPathList->ResetKey('setting');
$initPathList->MethodPath('SetPathEnd');
$initPathList->MethodPath('Add', 'Setting.php');
$initPathList->ResetKey('error_setting');
$initPathList->MethodPath('SetPathEnd');
$initPathList->MethodPath('Add', 'Setting.php');
$initPathList->ResetKey('error_include');
$initPathList->MethodPath('SetPathEnd');
$initPathList->MethodPath('Add', 'Include.php');

$initPathList->All();

foreach ($initPathList->Get() as $path) {
    require_once $path;
}
// UA判定処理 (内容はベースと同様)
$agent = new UA\UA();
define('Phone', 2);
define('PC', 1);
switch ($agent->DesignJudge()) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}
