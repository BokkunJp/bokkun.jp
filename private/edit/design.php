<script src="//cdn.tiny.cloud/1/pdj8ilco6qfdzmqzm941oa79h5gg9z7q4vg93ucxtbhshozs/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
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

$dir = ["---" => "---"];

if (!$session->Judge('token')) {
    $token = MakeToken();
    SetToken($token);
} else {
    $token = $session->Read('token');
}

$dir = array_merge($dir, scandir('../../'));

foreach ($dir as $_key => $_dir) {
    if (preg_match("/public$|private$|common$|custom.*$|template.*$|\..*$/", $_dir)) {
        unset($dir[$_key]);
    }
}

$smarty->assign('base', basename(__DIR__). '/subdirectory');
$smarty->assign('token', $token);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
