<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php

$smarty = new Smarty();
$smarty->template_dir = './subdirectory/templates/';
$smarty->compile_dir  = './subdirectory/templates_c/';
$smarty->config_dir   = './subdirectory/configs/';
$smarty->cache_dir    = './subdirectory/cache/';

$smarty->assign('name', 'guest');
$smarty->display('index.tpl');