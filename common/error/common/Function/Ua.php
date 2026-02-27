<?php

namespace Error\Important;

const PC_design = 1;
const SP_design = 2;

// Ua.phpの読み込み
$commonPath = new \Path(COMMON_DIR);
$commonPath->add('Component');
$commonPath->setPathEnd();
require_once $commonPath->add('Ua.php', false);

/**
 * Ua.phpの内容を継承
 * (個別で必要な機能があれば追加予定)
 */
class UA extends \Common\Important\Ua
{
}
