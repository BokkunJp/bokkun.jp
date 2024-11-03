<?php
namespace Public\Important;

// セッションクラス (公開側)
class Session extends \Common\Important\Session
{
    use \SessionTrait;

    /**
     * タイプにpublicをセットして初期化
     *
     * @param string|null $sessionName
     */
    function __construct(?string $sessionName = null)
    {
        $this->start();

        $this->setType('public');
        $this->setSessionName($sessionName);

        parent::__construct($sessionName);
    }
}
