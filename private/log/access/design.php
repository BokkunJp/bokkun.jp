<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use \Smarty\Smarty;

$smarty = new Smarty();
$session = new Private\Important\Session('private-access-log');

$smarty->setTemplateDir('./subdirectory/smarty/templates/');
$smarty->setCompileDir('./subdirectory/smarty/templates_c/');
$smarty->setCacheDir('./subdirectory/smarty/cache/');
$smarty->setCacheDir('./subdirectory/smarty/cache/');

// jsファイル読み込み
$jsTitle = createClient('log');
includeJsFiles($jsTitle);

if ($session->judge('addition')) {
    $smarty->assign('session', $session->read('addition'));
    $session->delete('addition');
}

$dirPath = rtrim(dirname(__DIR__, 5), "\\") . createClient('log', 'log');
// print_r(Private\Important\Setting::getServerName());
// ディレクトリを除外
$domain = Private\Important\Setting::getServerName();
$dir = scandir($dirPath);
foreach ($dir as $_key => $_val) {
    if (!preg_match("/^{$domain}/", $_val)) {
        unset($dir[$_key]);
    }
}

$startDir = ["---" => "---"];
$dir = array_merge($startDir, $dir);

$accessLogAry = [];

$smarty->assign('base', basename(__DIR__) . '/subdirectory');
$smarty->assign('dir_path', $dirPath);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
