<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
setPlugin('smarty');
$smarty = new Smarty();

$smarty->template_dir = './subdirectory/templates/';
$smarty->compile_dir  = './subdirectory/templates_c/';
$smarty->config_dir   = './subdirectory/configs/';
$smarty->cache_dir    = './subdirectory/cache/';

if ($session->JudgeArray('admin', 'addition')) {
    $smarty->assign('admin_addition', $session->ReadArray('admin', 'addition'));
    $session->DeleteArray('admin', 'addition');
} else {
    $smarty->assign('admin_addition', '');
}

$dir = scandir('../../');

foreach ($dir as $_key => $_dir) {
    if (preg_match("/public$|private$|common$|custom.*$|template.*$|\..*$/", $_dir)) {
        unset($dir[$_key]);
    }
}

$session->OnlyView('notice');

$createToken = new private\Token('create-token', $session);
$createToken->Set();

$editToken= new private\Token('edit-token', $session);
$editToken->Set();

$smarty->assign('base', 'subdirectory');
$smarty->assign('createToken', $createToken);
$smarty->assign('editToken', $editToken);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
