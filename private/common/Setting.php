<?php
namespace PrivateSetting;
if (isset($_SERVER['HTTPS'])) {
    $http = '//';
} else {
    $http = 'http://';
}
$domain = $_SERVER['SERVER_NAME'];

$url = $http.$domain;
$private = $url. '/private/';

// 定数などの定義
require dirname(__DIR__). DIRECTORY_SEPARATOR . 'common'. DIRECTORY_SEPARATOR . 'InitFunction.php';
$messagePath = AddPath(dirname(__DIR__), 'common');
$messagePath = AddPath($messagePath, 'Word');
$messagePath = AddPath($messagePath, 'Message.php', false);
require $messagePath;

require_once AddPath(COMMON_DIR, "Config.php", false);
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];

$Agent = $_SERVER['HTTP_USER_AGENT'];
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
}
function GetSelf_Admin() {
    return $_SERVER['PHP_SELF'];
}

function GetPrevPage_Admin() {
    return filter_input(INPUT_SERVER, 'HTTP_REFERER');
}


function getURI_Admin() {
    return $_SERVER['REQUEST_URI'];
}

class Setting {

    private $domain, $url, $public;
    private $client, $css, $js, $image;

    function __construct()
    {
        // 基本設定
        $this->InitSSL($this->url);
        $this->domain = $this->GetSERVER('SERVER_NAME');
        $this->url = $this->url . $this->domain;
        $this->public = $this->url . '/public/';

        // 公開パス関係
        $this->client = $this->public . 'client/';
        $this->css = $this->client . 'css';
        $this->js = $this->client . 'js';
        $this->image = $this->client . 'image';
        $this->csv = $this->client . 'csv';
    }

    private function InitSSL(&$http)
    {
        $http_flg = $this->GetSERVER('HTTPS');
        if (isset($http_flg)) {
            $http = '//';
        } else {
            $http = 'http://';
        }
    }

    static private function GetSERVER($elm)
    {
        if (isset($_SERVER[$elm])) {
            return Sanitize($_SERVER[$elm]);
        } else {
            return null;
        }
    }

    static public function GetServarName($elm)
    {
        return self::GetSERVER($elm);
    }

    static public function GetPropaty($elm)
    {
        if (property_exists('PublicSetting\Setting', $elm) !== false) {
            return self::elm;
        } else {
            return null;
        }
    }

    static public function GetURI()
    {
        return self::GetSERVER('REQUEST_URI');
    }

    static public function GetScript()
    {
        return self::GetSERVER('SCRIPT_NAME');
    }

    static public function GetPosts()
    {
        return Sanitize($_POST);
    }

    // 指定した要素のPost値を取得
    static public function GetPost($elm = '')
    {
        $_post = Sanitize($_POST);
        if (key_exists($elm, $_post)) {
            return $_post[$elm];
        } else {
            return null;
        }
    }

    static public function GetRemoteADDR()
    {
        return self::GetSERVER('REMOTE_ADDR');
    }

    // すべてのGet値を取得
    static public function GetRequest()
    {
        return Sanitize($_GET);
    }

    // 指定した要素のGet値を取得
    static public function GetQuery($elm = '')
    {
        $_get = Sanitize($_GET);
        if (key_exists($elm, $_get)) {
            return $_get[$elm];
        } else {
            return null;
        }
    }

    static public function GetFiles()
    {
        return $_FILES;
    }

    // 公開パスなどのURLを取得
    public function GetUrl($query = '', $type = 'url')
    {
        switch ($type) {
            case 'client':
                $url = $this->client;
                break;
            case 'css':
                $url = $this->css;
                break;
            case 'js':
                $url = $this->js;
                break;
            case 'image':
                $url = $this->image;
                break;
            case 'csv':
                $url = $this->csv;
                break;
            default:
                $url = $this->url;
                break;
        }
        return $url . '/' . $query;
    }
}

class Admin extends Setting {
    function __construct()
    {
    }

    public static function GetSelf()
    {
        return $_SERVER['PHP_SELF'];
    }

    public static function GetPrevPage()
    {
        return filter_input(INPUT_SERVER, 'HTTP_REFERER');
    }


    public static function getURI()
    {
        return $_SERVER['REQUEST_URI'];
    }
}