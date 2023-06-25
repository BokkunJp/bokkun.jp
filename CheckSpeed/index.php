<?php

// 関数呼び出し
require_once dirname(__DIR__) . '/public/common/Layout/scratch.php';
$designPath = new \Path(__DIR__);
$designPath->SetPathEnd();
$designPath->Add("design.php");
require_once$designPath->Get();
