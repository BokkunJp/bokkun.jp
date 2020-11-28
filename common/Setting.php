<?php
namespace CommonSetting;
require_once 'InitFunction.php';

$base = new Setting();

require_once AddPath(__DIR__, "Config.php", false);
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];

if (isset($_SERVER['HTTPS'])) {
    $http = '//';
} else {
    $http = 'http://';
}
$domain = $_SERVER['SERVER_NAME'];
$url = $http . $domain;

// 定数などの定義
$COMMON_DIR = __DIR__;
$FUNCTION_DIR = $COMMON_DIR . '/Function';

// 設定関係のクラス化(実装中)
class Setting {

    protected $domain, $url, $public;
    protected $client, $css, $js, $image;

    function __construct() {
        // 基本設定
        $this->InitSSL($this->url);
        $this->domain = $this->GetSERVER('SERVER_NAME');
        $this->url = $this->url . $this->domain;
        $this->public = AddPath('', 'public', true, '/');
        $this->client = AddPath($this->public, 'client', true, '/');
    }

    private function InitSSL(&$http) {
        $http_flg = $this->GetSERVER('HTTPS');
        if (isset($http_flg)) {
            $http = '//';
        } else {
            $http = 'http://';
        }
    }

    static protected function GetSERVER($elm) {
        return Sanitize(filter_input_fix(INPUT_SERVER, $elm));
    }

    static public function GetDocumentRoot() {
        return self::GetSERVER('DOCUMENT_ROOT');
    }

    static public function GetServarName() {
        return self::GetSERVER('SERVER_NAME');
    }

    static public function GetPropaty($elm) {
        if (property_exists('PublicSetting\Setting', $elm) !== false) {
            return $elm;
        } else {
            return null;
        }
    }

    static public function GetURI() {
        return self::GetSERVER('REQUEST_URI');
    }

    static public function GetScript() {
        return self::GetSERVER('SCRIPT_NAME');
    }

    // 全Post値を取得
    static public function GetPosts() {
        return Sanitize(filter_input_array(INPUT_POST));
    }

    // 配列形式のPost値を取得
    static public function GetPostArray($var)
    {
        return self::GetPost($var, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    // 指定した要素のPost値を取得
    static public function GetPost($elm = '', $filter = FILTER_DEFAULT, $options = null) {
        return Sanitize(filter_input_fix(INPUT_POST, $elm, $filter, $options));
    }

    static public function GetRemoteADDR() {
        return self::GetSERVER('REMOTE_ADDR');
    }

    // すべてのGet値を取得
    static public function GetRequest() {
        return Sanitize(filter_input_array(INPUT_GET));
    }

    // 指定した要素のGet値を取得
    static public function GetQuery($elm = '',$filter = FILTER_DEFAULT, $options = null) {
        return Sanitize(filter_input_fix(INPUT_GET, $elm, $filter, $options));
    }

    // 配列形式のGet値を取得
    static public function GetQueryArray($var)
    {
        return self::GetQuery($var, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    // FILEを取得
    static public function GetFiles() {
        return $_FILES;
    }

    // 公開パスなどのURLを取得
    public function GetUrl($query='', $type='url', $relativePath = false) {
        if ($relativePath === false) {
            $url = $this->url;
        } else {
            $url = '';
        }

        switch ($type) {
            case 'client':
                $url .= rtrim($this->client, '/');
                break;
            case 'css':
                $url .= $this->css;
                break;
            case 'js':
                $url .= $this->js;
                break;
            case 'image':
                $url .= $this->image;
                break;
            case 'csv':
                $url .= $this->csv;
                break;
            default:
                break;
        }
        return AddPath($url, $query, false, '/');
    }
}

// セッションクラス (ファイルを分離予定)
class Session
{

    private $init;
    private $session;

    function __construct()
    {
        $this->Read();
        $this->init = $this->session;
    }

    private function SessionStart()
    {
        if (!isset($_SESSION) || session_status() === PHP_SESSION_DISABLED) {
            session_start();
        } else {
            // セッションが定義されている場合は更新
            session_regenerate_id();
        }
    }

    /**
     * セッションの追加 (プライベートクラス)
     *
     * @param [Strging] $sessionElm
     * @param [mixed] $sessionVal
     * @return void
     */
    private function Add($sessionElm, $sessionVal)
    {
        $this->session[$sessionElm] = $sessionVal;
        $_SESSION[$sessionElm] = $this->session[$sessionElm];
    }

    /**
     * セッションの書き込み
     *
     * @param [String] $tag
     * @param [String] $message
     * @param [mixed] $handle
     * @return void
     */
    public function Write($tag, $message, $handle = null)
    {
        if (!empty($handle)) {
            $this->$handle();
        }
        $this->Add($tag, $message);
    }

    /**
     * セッション配列の更新
     *
     * @param String $parentId
     * @param String $childId
     * @param mixed $data
     * @return void
     */
    public function WriteArray($parentId, $childId, $data)
    {
        if ($this->Read($parentId) != NULL) {
            $tmp = $this->Read($parentId);
        } else {
            $tmp = [];
        }

        $tmp[$childId] = $data;
        $this->Write($parentId, $tmp);
    }

    public function Read($sessionElm = null)
    {
        if (!isset($_SESSION)) {
            $this->SessionStart();
        }

        $this->session = $_SESSION;

        if (isset($sessionElm)) {
            if (!isset($this->session[$sessionElm])) {
                return null;
            }
            return $this->session[$sessionElm];
        } else {
            return $this->session;
        }
    }

    public function Delete($sessionElm = null)
    {
        if (!isset($_SESSION)) {
            trigger_error('Session is already deleted.', E_USER_ERROR);
            exit;
        }
        if (isset($sessionElm)) {
            unset($this->session[$sessionElm]);
            $_SESSION = $this->session;
        } else {
            unset($this->session);
            $this->session = $this->init;
        }
    }

    // セッション判定用
    public function Judge($id = null)
    {
        if (!isset($id)) {
            return null;
        }

        if (!isset($this->session[$id])) {
            return false;
        }

        return true;
    }

    // セッション閲覧用
    public function View($id = null)
    {
        $judge = $this->Judge($id);
        if ($judge === null) {
            var_dump($this->session);
        } else if ($judge === true) {
            print_r($this->session[$id]);
        }
    }

    // セッション参照後、該当のセッションを削除する
    public function OnlyView($tag)
    {
        if ($this->Judge($tag) === true) {
            $this->View($tag);
            $this->Delete($tag);
        }
    }

    // セッションの完全な破棄
    public function FinaryDestroy()
    {
        session_unset();

        // セッションを切断するにはセッションクッキーも削除する。
        // Note: セッション情報だけでなくセッションを破壊する。
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }

        // 最終的に、セッションを破壊する
        session_destroy();
    }
}

// Cookieクラス
class Cookie
{
    private $cookie;
    function __construct()
    {
        $this->Init();
    }

    private function Init()
    {
        foreach ($_COOKIE as $_key => $_val) {
            setcookie($_key, "", time() - 100);
        }
        unset($_COOKIE);
        $this->cookie = null;
    }

    public function GetCookie()
    {
        $this->cookie = $_COOKIE;
    }

    public function SetCookie($name, $val = null)
    {
        setCookie($val, $name);
    }

    public function ViewCookie()
    {
        print_r($this->cookie);
    }
}
