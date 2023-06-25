<?php

namespace PrivateTag;

require_once dirname(__DIR__). DIRECTORY_SEPARATOR. 'InitFunction.php';

$wordPath = new \Path(dirname(__DIR__));
$wordPath->AddArray(['Word', 'Message.php']);
require_once $wordPath->Get();

$tagPath = new \Path(COMPONENT_DIR);
$tagPath->SetPathEnd();
$tagPath->Add('Tag.php');
require_once $tagPath->Get();

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
