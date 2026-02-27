<?php
namespace Public\Important;

// セッションクラス (公開側)
class Session extends \Common\Important\Session
{
    use \SessionTrait;
    private const TYPE = 'public';

    /**
     * タイプにpublicをセットして初期化
     *
     * @param string|null $sessionName
     */
    function __construct(?string $sessionName = null)
    {
        $this->start();

        parent::__construct($sessionName, self::TYPE);
    }
}
