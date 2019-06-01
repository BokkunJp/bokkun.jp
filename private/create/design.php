<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../common/smarty/core.php');

if (isset($session['addition'])) {
    $smarty->assign('session', $session['addition']);
    unset($session);
    unset($_SESSION['addition']);
}

$dir = scandir('../../public/');
$smarty->assign('base', basename(__DIR__). '/subdirectory');
$smarty->assign('dir', $dir);
$smarty->display('index.tpl');
