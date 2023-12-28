<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
setVendor();
$smarty = new Smarty();

$smarty->template_dir = './subdirectory/templates/';
$smarty->compile_dir  = './subdirectory/templates_c/';
$smarty->config_dir   = './subdirectory/configs/';
$smarty->cache_dir    = './subdirectory/cache/';

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
