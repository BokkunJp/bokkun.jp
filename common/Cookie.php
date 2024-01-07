<?php
namespace Common\Important;
// Cookieクラス
class Cookie
{
    private $cookie;
    public function __construct()
    {
        $this->init();
    }

    /**
     * Init
     *
     * クッキーの初期設定
     *
     * @return void
     */
    private function init()
    {
        foreach ($_COOKIE as $_key => $_val) {
            setcookie($_key, "", time() - 100);
        }
        unset($_COOKIE);
        $this->cookie = null;
    }

    /**
     * クッキーを取得
     *
     * @return void
     */
    public function getCookie()
    {
        $this->cookie = $_COOKIE;
    }

    /**
     * SetCookie
     *
     * クッキーのセット
     *
     * @param [type] $name
     * @param [type] $val
     * @return void
     */
    public function setCookie($name, $val = null)
    {
        setCookie($val, $name);
    }

    /**
     * ViewCookie
     *
     * クッキーを表示
     *
     * @return void
     */
    public function viewCookie()
    {
        print_r($this->cookie);
    }
}
