<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use \Smarty\Smarty;

$smarty = new Smarty();
$session = new Private\Important\Session('error-log');

$smarty->setTemplateDir('./subdirectory/smarty/templates/');
$smarty->setCompileDir('./subdirectory/smarty/templates_c/');
$smarty->setConfigDir('./subdirectory/smarty/configs/');
$smarty->setCacheDir('./subdirectory/smarty/cache/');

// jsファイル読み込み
$jsTitle = createClient('log', separator:'/');
$jsTitle = trim($jsTitle, '/');
includeClientFiles($jsTitle, 'private', 'js');

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
