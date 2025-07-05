<?php
namespace Private\Important;

$tokenPath = new \Path(COMMON_DIR);
$tokenPath->setPathEnd();
$tokenPath->add(basename(__FILE__));
require_once $tokenPath->get();
class Token extends \Common\Important\Token {
    function __construct(string $tokenName, $session, bool $isTokenSet = false)
    {
        $this->tokenName = 'private-'. $tokenName;
        parent::__construct($tokenName, $session, $isTokenSet);
    }
}
