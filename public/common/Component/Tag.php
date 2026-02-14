<?php

namespace Public\Important;

$tagPath = new \Path(COMPONENT_DIR);
$tagPath->setPathEnd();
require_once $tagPath->add(basename(__FILE__), false);


class UseClass extends \Common\Important\UseClass
{
    // メインページへ遷移
    public function BackPage($query = null)
    {
        $this->movePage('/public/' . $query);
    }
}
