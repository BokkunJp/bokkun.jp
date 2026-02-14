<?php

namespace Private\Important;

require_once dirname(__DIR__). DIRECTORY_SEPARATOR. 'InitFunction.php';

$wordPath = new \Path(dirname(__DIR__));
$wordPath->addArray(['Word', 'Message.php']);
require_once $wordPath->get();

$tagPath = new \Path(COMPONENT_DIR);
$tagPath->setPathEnd();
require_once $tagPath->add(basename(__FILE__), false);
