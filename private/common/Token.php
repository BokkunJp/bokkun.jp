<?php
namespace Private\Important;

$tokenPath = new \Path(COMMON_DIR);
$tokenPath->setPathEnd();
$tokenPath->add(basename(__FILE__));
require_once $tokenPath->get();
class Token extends \Common\Important\Token {

}
