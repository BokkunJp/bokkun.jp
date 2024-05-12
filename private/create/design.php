<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use \Smarty\Smarty;

$smarty = new Smarty();

$smarty->setTemplateDir('./subdirectory/smarty/templates/');
$smarty->setCompileDir('./subdirectory/smarty/templates_c/');
$smarty->setCacheDir('./subdirectory/smarty/cache/');
$smarty->setCacheDir('./subdirectory/smarty/cache/');

if ($session->judgeArray('admin', 'addition')) {
    $smarty->assign('admin_addition', $session->readArray('admin', 'addition'));
    $session->deleteArray('admin', 'addition');
} else {
    $smarty->assign('admin_addition', '');
}

$dir = scandir('../../');

foreach ($dir as $_key => $_dir) {
    if (preg_match("/public$|private$|common$|custom.*$|template.*$|\..*$/", $_dir)) {
        unset($dir[$_key]);
    }
}

$session->onlyView('notice');

$createToken = new Private\Important\Token('create-token', $session);
$createToken->set();

$editToken= new Private\Important\Token('edit-token', $session);
$editToken->set();

$smarty->assign('base', 'subdirectory');
$smarty->assign('createToken', $createToken);
$smarty->assign('editToken', $editToken);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
