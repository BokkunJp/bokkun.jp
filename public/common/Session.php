<?php
namespace Public\Important;

// セッションクラス (公開側)
class Session extends \Common\Important\Session
{
    function __construct()
    {
        $this->start();
        parent::__construct();
    }
}
