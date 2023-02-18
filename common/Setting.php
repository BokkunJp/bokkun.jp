<?php

namespace common;

require_once 'InitFunction.php';
require_once 'Session.php';

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

// 設定関係のクラス
class Setting
{
    protected $css;
    protected $csv;
    protected $client;
    protected $domain;
    protected $error;
    protected $image;
    protected $js;
    protected $public;
    protected $url;

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
    private function InitSSL(&$scheme)
    {
        $sslFlg = $this->GetSERVER('HTTPS');
        if (isset($sslFlg)) {
            $scheme = '//';
        } else {
            $scheme = 'http://';
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
        if (property_exists('public\Setting', $elm) !== false) {
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
     * @return array
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
