<script src="//cdn.tiny.cloud/1/pdj8ilco6qfdzmqzm941oa79h5gg9z7q4vg93ucxtbhshozs/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use \Smarty\Smarty;

$smarty = new Smarty();
$session = new Private\Important\Session('edit-page');

$smarty->setTemplateDir('./subdirectory/smarty/templates/');
$smarty->setCompileDir('./subdirectory/smarty/templates_c/');
$smarty->setConfigDir('./subdirectory/smarty/configs/');
$smarty->setCacheDir('./subdirectory/smarty/cache/');

if ($session->judge('addition')) {
    $smarty->assign('session', $session->read('addition'));
    $session->delete('addition');
}

$dir = ["---" => "---"];

$editSrcToken = new Private\Important\Token('edit-src-token', $session);
$editSrcToken->set();


$dir = array_merge($dir, scandir('../../'));

// dirファイルを調整
foreach ($dir as $_key => $_dir) {
    if (
            preg_match("/public$|private$|common$|custom.*$|template.*$|\..*$/", $_dir)
            || preg_match("/^.$/", $_dir)
            || preg_match("/^..$/", $_dir)
        ) {
        unset($dir[$_key]);
    }
}
$smarty->assign('base', basename(__DIR__). '/subdirectory');
$smarty->assign('editSrcToken', $editSrcToken);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
