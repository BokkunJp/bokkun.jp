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

if ($session->Judge('addition')) {
    $smarty->assign('session', $session->Read('addition'));
    $session->Delete('addition');
}

$dir = scandir('../../');
$notList = array_flip(GetNotDelFileList());

foreach ($dir as $_key => $_dir) {
    if (preg_match("/public$|private$|common$|custom.*$|template.*$|\..*$/", $_dir) || isset($notList[$_dir])) {
        unset($dir[$_key]);
    }
}

if (!$session->Judge('token')) {
    $token = MakeToken();
    SetToken($token);
} else {
    $token = $session->Read('token');
}

$session->OnlyView('notice');

$smarty->assign('base', 'subdirectory');
$smarty->assign('token', $token);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
