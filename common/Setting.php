<?php
namespace Common\Important;

require_once 'InitFunction.php';
require_once 'Session.php';

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
        $public = new \Path('', '/');
        $public->add('public');
        $this->public = $public->get();

        $client = new \Path($public->get(), '/');
        $client->add('client');
        $this->client = $client->get();
    }

    /**
     * InitSSL
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
     * GetSERVER
     *
     * $_SERVERの内容を安全に取得する。
     *
     * @param mixed $elm
     * @return mixed
     */
    protected static function getServer($elm): mixed
    {
        return sanitize(filter_input_fix(INPUT_SERVER, $elm));
    }

    /**
     * GetDocumentRoot
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
     * GetServerName
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
     * GetPropaty
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
     * GetURI
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
     * GetPosts
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
     * GetPostArray
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
        return sanitize(filter_input_fix(INPUT_POST, $elm, $filter, $options));
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
     * GetRequest
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
    public static function getQuery($elm = '', $filter = FILTER_DEFAULT, $options = null)
    {
        return sanitize(filter_input_fix(INPUT_GET, $elm, $filter, $options));
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
    public static function getQueryArray($var): ?array
    {
        return self::getQuery($var, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    }

    /**
     * GetFiles
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
    public function getUrl($query='', $type='url', $relativePath = false): string
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

        $urlPath = new \Path($url, '/');
        $urlPath->setPathEnd();
        $urlPath->add($query);
        return rtrim($urlPath->get(), '/');
    }
}
