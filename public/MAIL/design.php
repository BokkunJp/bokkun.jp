<!-- デザイン用ファイル -->
<?php
use PublicSetting\Session as Session;
use PublicSetting\Permmision as Permmision;
use Model\Mail as Mail;

require_once (DOCUMENT_ROOT. '/API/smarty/core.php');

$setting = new Setting();
$mail = new Mail();
$smarty->assign('url', $url. PublicSetting\GetURI());
$smarty->display('index.tpl');
