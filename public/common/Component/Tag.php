<?php

namespace Public\Important;

$commonPath = new \Path(DOCUMENT_ROOT);
$commonPath->addArray(["common", "Component", "Tag.php"]);
require_once $commonPath->get();

class UseClass extends \Common\Important\UseClass
{
    // メインページへ遷移
    public function BackPage($query = null)
    {
        $this->movePage('/public/' . $query);
    }
}
