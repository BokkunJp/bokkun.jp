<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$smarty = new Smarty();

$smarty->template_dir = './subdirectory/templates/';
$smarty->compile_dir  = './subdirectory/templates_c/';
$smarty->config_dir   = './subdirectory/configs/';
$smarty->cache_dir    = './subdirectory/cache/';

// jsファイル読み込み
$jsTitle = createClient('log', separator:'/');
$jsTitle = trim($jsTitle, '/');
includeJsFiles($jsTitle);

if ($session->judge('addition')) {
    $smarty->assign('session', $session->read('addition'));
    $session->delete('addition');
}

$dirPath = rtrim(dirname(__DIR__, 5), "\\") . createClient('log');
$dir = ["---" => "---"];
$dir = array_merge($dir, scandir($dirPath));

// dirファイルを調整
foreach ($dir as $_key => $_dir) {
    if (
            preg_match("/^.$/", $_dir)
            || preg_match("/^..$/", $_dir)
        ) {
        unset($dir[$_key]);
    }
}

$smarty->assign('base', basename(__DIR__) . '/subdirectory');
$smarty->assign('dir_path', $dirPath);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
