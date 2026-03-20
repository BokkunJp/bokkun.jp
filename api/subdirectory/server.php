<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// 関数呼び出し
require_once dirname(__DIR__, 2) . '/public/common/layout/scratch.php';
require_once COMMON_DIR. '/include.php';
// $api = modelTest();
// $api->setUrl('aaa', 'hoge');
// output($api, true, true);
// $api->sendData();

$path = new \Path(__DIR__);
$path->setPathEnd();

includeFiles($path->get());

$ary = ["hoge" => 1,"fuga" => 2,3];
$testArray = new \ArrayClass($ary);
$testObject = new \ObjectClass($ary);
