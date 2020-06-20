<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../../common/smarty/core.php');

if ($session->Judge('addition')) {
    $smarty->assign('session', $session->Read('addition'));
    $session->Delete('addition');
}

$dirPath = rtrim(dirname(__DIR__, 5), "\\") . CreateClient();
$dir = ["---"];

$dir = array_merge($dir, scandir($dirPath));

$smarty->assign('base', basename(__DIR__) . '/subdirectory');
$smarty->assign('dir_path', $dirPath);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
