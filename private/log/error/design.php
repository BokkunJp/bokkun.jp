<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
SetPlugin('smarty');
$smarty = new Smarty();

$smarty->template_dir = './subdirectory/templates/';
$smarty->compile_dir  = './subdirectory/templates_c/';
$smarty->config_dir   = './subdirectory/configs/';
$smarty->cache_dir    = './subdirectory/cache/';

// jsファイル読み込み
$jsTitle = CreateClient('log');
IncludeJSFiles($jsTitle);

if ($session->Judge('addition')) {
    $smarty->assign('session', $session->Read('addition'));
    $session->Delete('addition');
}

$dirPath = rtrim(dirname(__DIR__, 5), "\\") . CreateClient('log');
$dir = ["---" => "---"];
$dir = array_merge($dir, scandir($dirPath));

$smarty->assign('base', basename(__DIR__) . '/subdirectory');
$smarty->assign('dir_path', $dirPath);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
