<?php

namespace Public\Important;

$commonPath = new \Path(DOCUMENT_ROOT);
$commonPath->addArray(["common", "Component", "Tag.php"]);
require_once $commonPath->get();
class Tag extends \Basic\Important\Tag
{
}

class HTMLClass extends \Basic\Important\HTMLClass
{
}
class CustomTagCreate extends \Basic\Important\CustomTagCreate
{
}

class ScriptClass extends \Basic\Important\ScriptClass
{
}
class UseClass extends \Basic\Important\UseClass
{
    // メインページへ遷移
    public function BackPage($query = null)
    {
        $this->movePage('/public/' . $query);
    }
}
