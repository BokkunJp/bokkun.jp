<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$APIPath = new \Path(DOCUMENT_ROOT);
$APIPath->add("api");
$APIPath->setPathEnd();
$APIPath->add("server.php");
require_once $designPath->get();
