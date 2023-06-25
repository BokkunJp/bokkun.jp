<?php

namespace PublicTag;

$commonPath = new \Path(DOCUMENT_ROOT);
$commonPath->AddArray(["common", "Component", "Tag.php"]);
require_once $commonPath->Get();
class Base extends \BasicTag\Base
{
}

class HTMLClass extends \BasicTag\HTMLClass
{
}
class CustomTagCreate extends \BasicTag\CustomTagCreate
{
}

class ScriptClass extends \BasicTag\ScriptClass
{
}
class UseClass extends \BasicTag\UseClass
{
    // メインページへ遷移
    public function BackPage($query = null)
    {
        $this->MovePage('/public/' . $query);
    }
}
