<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$apiPath = new \Path(DOCUMENT_ROOT);
$apiPath->add("api");
$apiPath->add("subdirectory");
$apiPath->setPathEnd();
$apiPath->add("server.php");
require_once $apiPath->get();
