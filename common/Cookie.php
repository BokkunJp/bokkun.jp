<?php
// Cookieクラス
class Cookie
{
    private $cookie;
    public function __construct()
    {
        $this->Init();
    }

    /**
     * Init
     *
     * クッキーの初期設定
     *
     * @return void
     */
    private function Init()
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
    public function GetCookie()
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
    public function SetCookie($name, $val = null)
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
    public function ViewCookie()
    {
        print_r($this->cookie);
    }
}
