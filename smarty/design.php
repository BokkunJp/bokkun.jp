<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php

use \Smarty\Smarty;

$smarty = new Smarty();

$smarty->setTemplateDir('./subdirectory/smarty/templates/');
$smarty->setCompileDir('./subdirectory/smarty/templates_c/');
$smarty->setConfigDir('./subdirectory/smarty/configs/');
$smarty->setCacheDir('./subdirectory/smarty/cache/');

$smarty->assign('name', 'guest');
$smarty->display('index.tpl');
