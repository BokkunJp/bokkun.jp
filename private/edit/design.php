<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../common/smarty/core.php');

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
$smarty->assign('base', basename(__DIR__). '/subdirectory');
$smarty->assign('token', $token);
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
