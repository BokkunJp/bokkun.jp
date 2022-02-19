<?php

namespace PrivateTag;

require_once dirname(__DIR__). DIRECTORY_SEPARATOR. 'InitFunction.php';
$wordPath = AddPath(dirname(__DIR__), 'Word');
$wordPath = AddPath($wordPath, 'Message.php', false);
require_once $wordPath;

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
    public function BackAdmin($query = '')
    {
        $this->MovePage('/private/' . $query);
    }
}
