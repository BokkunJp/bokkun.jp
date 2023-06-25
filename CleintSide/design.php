<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$APIPath = new \Path(DOCUMENT_ROOT);
$APIPath->Add("API");
$APIPath->SetPathEnd();
$APIPath->Add("server.php");
require_once$designPath->Get();
