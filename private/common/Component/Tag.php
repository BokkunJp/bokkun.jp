<?php

namespace Private\Important;

require_once dirname(__DIR__). DIRECTORY_SEPARATOR. 'InitFunction.php';

$wordPath = new \Path(dirname(__DIR__));
$wordPath->addArray(['Word', 'Message.php']);
require_once $wordPath->get();

$tagPath = new \Path(COMPONENT_DIR);
$tagPath->setPathEnd();
$tagPath->add('Tag.php');
require_once $tagPath->get();

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
    public function BackAdmin($query = '')
    {
        $this->movePage('/private/' . $query);
    }
}
