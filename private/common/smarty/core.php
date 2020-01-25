<?php
$libs = '/smarty-3.1.30/libs/';
define('SMARTY_DIR', '../common/smarty'.$libs);

require(SMARTY_DIR. '/Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = './subdirectory/templates/';
$smarty->compile_dir  = './subdirectory/templates_c/';
$smarty->config_dir   = './subdirectory/configs/';
$smarty->cache_dir    = './subdirectory/cache/';

function display($path='', $pathName='no name') {
    return $smarty->display($path. $pathName);
}
