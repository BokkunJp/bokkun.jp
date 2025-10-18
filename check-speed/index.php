<?php

// 関数呼び出し
require_once dirname(__DIR__) . '/public/common/layout/scratch.php';
$designPath = new \Path(__DIR__);
$designPath->setPathEnd();
$designPath->add("design.php");
require_once$designPath->get();
