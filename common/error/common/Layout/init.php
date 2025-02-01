<!DOCTYPE html>
<?php

ini_set('error_reporting', E_ALL);


require_once dirname(__DIR__, 3). DIRECTORY_SEPARATOR. 'InitFunction.php';

// タイトルの初期設定
$errCode = http_response_code();    // ステータスコードを出力
if (!isset($title) && empty($title)) {
    $title = 'Page Error -';            // タイトル用に調整
    $title .= $errCode;
    $title .= '-';
}

$initPathList = new \PathApplication('word', dirname(__DIR__));
$initPathList->setAll([
    'setting' => dirname(__DIR__, 3),
    'error_setting' => dirname(__DIR__),
    'error_include' => ''
]);
$initPathList->setKey('word');
$initPathList->methodPath('SetPathEnd');
$initPathList->methodPath('Add', 'word.php');
$initPathList->setKey('setting');
$initPathList->methodPath('SetPathEnd');
$initPathList->methodPath('Add', 'Setting.php');
$initPathList->setKey('error_setting');
$initPathList->methodPath('SetPathEnd');
$initPathList->methodPath('Add', 'Setting.php');
$initPathList->setKey('error_include');
$initPathList->methodPath('SetPathEnd');
$initPathList->methodPath('Add', 'Include.php');

$initPathList->resetKey();
foreach ($initPathList->get() as $path) {
    require_once $path;
}
// UA判定処理 (内容はベースと同様)
$agent = new Error\Important\UA();
define('Phone', 2);
define('PC', 1);
switch ($agent->judgeDevice()) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}
