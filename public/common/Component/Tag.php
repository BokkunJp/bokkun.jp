<?php
namespace PublicTag;

$commonPath = AddPath(DOCUMENT_ROOT, 'common');
$commonPath = AddPath($commonPath, 'Component');
$commonPath = AddPath($commonPath, 'Tag.php', false);
require_once $commonPath;
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
