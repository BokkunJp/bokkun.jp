<?php
namespace Public;

$commonTokenPath = new \Path(COMMON_DIR);
$commonTokenPath->SetPathEnd();
$commonTokenPath->Add(basename(__FILE__));
require_once $commonTokenPath->Get();
class Token extends \common\Token {

}
