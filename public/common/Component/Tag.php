<?php

namespace Public\Important;

$commonPath = new \Path(DOCUMENT_ROOT);
$commonPath->addArray(["common", "Component", "Tag.php"]);
require_once $commonPath->get();
class Tag extends \Common\Important\Tag
{
}

class HTMLClass extends \Common\Important\HTMLClass
{
}
class CustomTagCreate extends \Common\Important\CustomTagCreate
{
}

class ScriptClass extends \Common\Important\ScriptClass
{
}
class UseClass extends \Common\Important\UseClass
{
    // メインページへ遷移
    public function BackPage($query = null)
    {
        $this->movePage('/public/' . $query);
    }
}
