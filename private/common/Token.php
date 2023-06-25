<?php
namespace private;

$tokenPath = new \Path(COMMON_DIR);
$tokenPath->SetPathEnd();
$tokenPath->Add(basename(__FILE__));
require_once $tokenPath->Get();
class Token extends \common\Token {

}
