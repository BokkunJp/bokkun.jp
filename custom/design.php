<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
require_once(dirname(dirname(DOCUMENT_ROOT)). '/Plugin/smarty/core.php');
$smarty->assign('name', 'guest');
$smarty->display('index.tpl');
