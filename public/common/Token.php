<?php
namespace Public\Important;

$commonTokenPath = new \Path(COMMON_DIR);
$commonTokenPath->setPathEnd();
$commonTokenPath->add(basename(__FILE__));
require_once $commonTokenPath->get();
class Token extends \Common\Important\Token {

}
