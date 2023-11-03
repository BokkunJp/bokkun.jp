<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// 関数呼び出し
require_once dirname(__DIR__) . '/public/common/Layout/scratch.php';
// $api = modelTest();
// $api->setUrl('aaa', 'hoge');
// output($api, true, true);
// $api->sendData();


$ary = ["hoge" => 1,"fuga" => 2,3];
$testArray = new ArrayClass($ary);
$testObject = new ObjectClass($ary);
Debug($testArray->findValue("hoge"));
Debug($testObject->findValue("hoge"));
?>
<canvas class='canvas' width=40 height=40></canvas>