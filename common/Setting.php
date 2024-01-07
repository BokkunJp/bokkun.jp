<?php
namespace Common\Important;

require_once 'InitFunction.php';
require_once 'Session.php';
require_once 'Cookie.php';
require_once 'Cache.php';

$base = new Setting();

$configPath = new \Path(__DIR__);
$configPath->setPathEnd();
$configPath->add('Config.php');
require_once $configPath->get();
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
    protected string $css;
    protected string $csv;
    protected \Path|string $client;
    protected string $domain;
    protected string $error;
    protected string $image;
    protected string $js;
    protected string $public;
    protected ?string $url;

    public function __construct()
    {
        // 基本設定
        $this->initSsl($this->url);
        $this->domain = $this->getServer('SERVER_NAME');
        $this->url = $this->url . $this->domain;
        $public = new \Path('');
        $public->add('public');
        $this->public = $public->get();

        $client = new \Path($public->get());
        $client->add('client');
        $this->client = $client->get();
    }

    /**
     * initSSL
     *
     * HTTPSの有無を判定してセットする。
     *
     * @param [type] $http
     * @return void
     */
    private function initSsl(&$scheme)
    {
        $sslFlg = $this->getServer('HTTPS');
        if (isset($sslFlg)) {
            $scheme = '//';
        } else {
            $scheme = 'http://';
        }
    }

    /**
     * getSERVER
     *
     * $_SERVERの内容を安全に取得する。
     *
     * @param mixed $elm
     * @return mixed
     */
    protected static function getServer($elm): mixed
    {
        return sanitize(filterInputFix(INPUT_SERVER, $elm));
    }

    /**
     * getDocumentRoot
     *
     * ドキュメントルートを取得する。
     *
     * @return string
     */
    public static function getDocumentRoot(): string
    {
        return self::getServer('DOCUMENT_ROOT');
    }

    /**
     * getServerName
     *
     * ドメイン名を取得する。
     *
     * @return mixed
     */
    public static function getServerName(): mixed
    {
        return self::getServer('SERVER_NAME');
    }

    /**
     * getPropaty
     *
     * プロパティ名を取得する。
     *
     * @param [type] $elm
     * @return mixed|null
     */
    public static function getPropaty($elm): ?string
    {
        if (property_exists('Public\Important\Setting', $elm) !== false) {
            return $elm;
        } else {
            return null;
        }
    }

    /**
     * getURI
     *
     * URIを取得。
     *
     * @return string
     */
    public static function getUri(): string
    {
        return self::getServer('REQUEST_URI');
    }

    public static function getScript()
    {
        return self::getServer('SCRIPT_NAME');
    }

    /**
     * getPosts
     *
     * 全Post値を取得。
     *
     * @return array|string|null
     */
    public static function getPosts(): mixed
    {
        return sanitize(filter_input_array(INPUT_POST));
    }


    /**
     * getPostArray
     *
     * 配列形式のPost値を取得。
     *
     * @return array|string|null
     */
    public static function getPostArray($var): ?array
    {
        return self::getPost($var, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    // 指定した要素のPost値を取得
    public static function getPost($elm = '', $filter = FILTER_DEFAULT, $options = null)
    {
        return sanitize(filterInputFix(INPUT_POST, $elm, $filter, $options));
    }

    /**
     * ドメインを取得。
     *
     * @return mixed
     */
    public static function getRemoteAddr()
    {
        return self::getServer('REMOTE_ADDR');
    }

    /**
     * getRequest
     *
     * すべてのGet値を取得
     *
     * @return mixed
     */
    public static function getRequest()
    {
        return sanitize(filter_input_array(INPUT_GET));
    }

    /**
     * getQuery
     *
     * 指定した要素のGet値を取得
     *
     * @param string $elm
     * @param [type] $filter
     * @param [type] $options
     *
     * @return mixed
     */
    public static function getQuery($elm = '', $filter = FILTER_DEFAULT, $options = null)
    {
        return sanitize(filterInputFix(INPUT_GET, $elm, $filter, $options));
    }

    /**
     * getQueryArray
     *
     * 配列形式のGet値を取得
     *
     * @param [type] $var
     *
     * @return array
     */
    public static function getQueryArray($var): ?array
    {
        return self::getQuery($var, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    /**
     * getFiles
     *
     * FILEを取得
     *
     * @return array
     */
    public static function getFiles()
    {
        return $_FILES;
    }

    /**
     * getUrl
     *
     * 公開パスなどのURLを取得
     *
     * @param string $query
     * @param string $type
     * @param boolean $relativePath
     *
     * @return string
     */
    public function getUrl($type='url', $query='', $relativePath = false): string
    {
        if ($relativePath === false) {
            $url = $this->url;
        } else {
            $url = '';
        }

        if ($type !== 'root') {
            $url .= rtrim($this->client, '/');
        }

        $urlPath = new \Path($url, '/');

        switch ($type) {
            case 'css':
                $query1 = $this->css;
                break;
            case 'js':
                $query1 = $this->js;
                break;
            case 'image':
                $query1 = $this->image;
                break;
            case 'csv':
                $query1 = $this->csv;
                break;
            default:
                $query1 = null;
                break;
        }

        $urlPath->setPathEnd();
        if ($query1) {
            $urlPath->add($query1);
        }

        $urlPath->setPathEnd();
        if ($query) {
            $urlPath->add($query);
        }

        return rtrim($urlPath->get());
    }
}
