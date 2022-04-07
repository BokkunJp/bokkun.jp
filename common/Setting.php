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
class Setting
{
    protected $domain;
    protected $url;
    protected $public;
    protected $client;
    protected $css;
    protected $js;
    protected $image;

    public function __construct()
    {
        // 基本設定
        $this->InitSSL($this->url);
        $this->domain = $this->GetSERVER('SERVER_NAME');
        $this->url = $this->url . $this->domain;
        $this->public = AddPath('', 'public', true, '/');
        $this->client = AddPath($this->public, 'client', true, '/');
    }

    /**
     * InitSSL
     *
     * HTTPSの有無を判定してセットする。
     *
     * @param [type] $http
     * @return void
     */
    private function InitSSL(&$http)
    {
        $http_flg = $this->GetSERVER('HTTPS');
        if (isset($http_flg)) {
            $http = '//';
        } else {
            $http = 'http://';
        }
    }

    /**
     * GetSERVER
     *
     * $_SERVERの内容を安全に取得する。
     *
     * @param mixed $elm
     * @return mixed
     */
    protected static function GetSERVER($elm): mixed
    {
        return Sanitize(filter_input_fix(INPUT_SERVER, $elm));
    }

    /**
     * GetDocumentRoot
     *
     * ドキュメントルートを取得する。
     *
     * @return string
     */
    public static function GetDocumentRoot(): string
    {
        return self::GetSERVER('DOCUMENT_ROOT');
    }

    /**
     * GetServerName
     *
     * ドメイン名を取得する。
     *
     * @return mixed
     */
    public static function GetServerName(): mixed
    {
        return self::GetSERVER('SERVER_NAME');
    }

    /**
     * GetPropaty
     *
     * プロパティ名を取得する。
     *
     * @param [type] $elm
     * @return mixed|null
     */
    public static function GetPropaty($elm): ?string
    {
        if (property_exists('PublicSetting\Setting', $elm) !== false) {
            return $elm;
        } else {
            return null;
        }
    }

    /**
     * GetURI
     *
     * URIを取得。
     *
     * @return string
     */
    public static function GetURI(): string
    {
        return self::GetSERVER('REQUEST_URI');
    }

    public static function GetScript()
    {
        return self::GetSERVER('SCRIPT_NAME');
    }

    /**
     * GetPosts
     *
     * 全Post値を取得。
     *
     * @return array|string|null
     */
    public static function GetPosts(): mixed
    {
        return Sanitize(filter_input_array(INPUT_POST));
    }


    /**
     * GetPostArray
     *
     * 配列形式のPost値を取得。
     *
     * @return array|string|null
     */
    public static function GetPostArray($var): ?array
    {
        return self::GetPost($var, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    // 指定した要素のPost値を取得
    public static function GetPost($elm = '', $filter = FILTER_DEFAULT, $options = null)
    {
        return Sanitize(filter_input_fix(INPUT_POST, $elm, $filter, $options));
    }

    /**
     * ドメインを取得。
     *
     * @return mixed
     */
    public static function GetRemoteADDR()
    {
        return self::GetSERVER('REMOTE_ADDR');
    }

    /**
     * GetRequest
     *
     * すべてのGet値を取得
     *
     * @return mixed
     */
    public static function GetRequest()
    {
        return Sanitize(filter_input_array(INPUT_GET));
    }

    /**
     * GetQuery
     *
     * 指定した要素のGet値を取得
     *
     * @param string $elm
     * @param [type] $filter
     * @param [type] $options
     *
     * @return mixed
     */
    public static function GetQuery($elm = '', $filter = FILTER_DEFAULT, $options = null)
    {
        return Sanitize(filter_input_fix(INPUT_GET, $elm, $filter, $options));
    }

    /**
     * GetQueryArray
     *
     * 配列形式のGet値を取得
     *
     * @param [type] $var
     *
     * @return array
     */
    public static function GetQueryArray($var): ?array
    {
        return self::GetQuery($var, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    /**
     * GetFiles
     *
     * FILEを取得
     *
     * @return void
     */
    public static function GetFiles()
    {
        return $_FILES;
    }

    /**
     * GetUrl
     *
     * 公開パスなどのURLを取得
     *
     * @param string $query
     * @param string $type
     * @param boolean $relativePath
     *
     * @return string
     */
    public function GetUrl($query='', $type='url', $relativePath = false): string
    {
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

    public function __construct()
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
     * Add
     *
     * セッションの追加
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
     * @param string|int $tag
     * @param mixed $message
     * @param ?string $handle
     *
     * @return void
     */
    public function Write(string|int $tag, mixed $message, ?string $handle = null)
    {
        if (!empty($handle)) {
            $this->$handle();
        }
        $this->Add($tag, $message);
    }

    /**
     * WriteArray
     *
     * セッション配列の更新
     *
     * @param string|int $parentId
     * @param string|int $childId
     * @param mixed $data
     * @return void
     */
    public function WriteArray(string|int $parentId, string|int $childId, mixed $data)
    {
        if ($this->Judge($parentId)) {
            $tmp = $this->Read($parentId);
        } else {
            $tmp = [];
        }

        $tmp[$childId] = $data;
        $this->Write($parentId, $tmp);
    }

    /**
     * Read
     *
     * セッションの読み込み
     *
     * @param string|int $sessionElm
     *
     * @return mixed
     */
    public function Read(string|int $sessionElm = null): mixed
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

    /**
     * Delete
     *
     * セッションの削除
     *
     * @param string|int $sessionElm
     *
     * @return void
     */
    public function Delete(string|int $sessionElm = null)
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

    /**
     * Judge
     *
     * セッション判定用
     *
     * @param string|int $id
     *
     * @return mixed
     */
    public function Judge(string|int $id = null): mixed
    {
        $ret = true;
        if (!isset($id)) {
            $ret = null;
        }

        if (!isset($this->session[$id])) {
            $ret = false;
        }

        return $ret;
    }

    /**
     * View
     *
     * セッション閲覧用
     *
     * @param ?mixed $id
     *
     * @return void
     */
    public function View(mixed $id = null)
    {
        $judge = $this->Judge($id);
        if ($judge === null) {
            var_dump($this->session);
        } elseif ($judge === true) {
            print_r($this->session[$id]);
        }
    }

    /**
     * OnlyView
     *
     * セッション参照後、該当のセッションを削除する
     *
     * @param string|int $tag
     * @return void
     */
    public function OnlyView(string|int $tag)
    {
        if ($this->Judge($tag) === true) {
            $this->View($tag);
            $this->Delete($tag);
        }
    }

    /**
     * FinaryDestroy
     *
     * セッションの完全な破棄
     *
     * @return void
     */
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
