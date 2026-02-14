<?php

namespace Public\Important;

$uaPath = new \Path(COMPONENT_DIR);
$uaPath->setPathEnd();
require_once $uaPath->add(basename(__FILE__), false);
