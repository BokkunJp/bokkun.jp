<?php
namespace CommonSetting;
require_once 'InitFunction.php';

$base = new Setting();
//Publicであればpublic/Setting.PHPを
//Adminであればadmin/Seetting.phpを
// どちらもなければこのページを参照(Topページ)
if (strpos($base->GetURL(), 'public')) {
    require_once ('public/Setting.php');
    return true;
} else if (strpos($base->GetURL(), 'admin')) {
    require_once ('admin/Setting.php');
    return true;
}

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

    private $domain, $url, $public;
    private $client, $css, $js, $image;

    function __construct() {
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

    private function InitSSL(&$http) {
        $http_flg = $this->GetSERVER('HTTPS');
        if (isset($http_flg)) {
            $http = '//';
        } else {
            $http = 'http://';
        }
    }

    static private function GetSERVER($elm) {
        if (isset($_SERVER[$elm])) {
            return Sanitize($_SERVER[$elm]);
        } else {
            return null;
        }
    }

    static public function GetServarName($elm) {
        return self::GetSERVER($elm);
    }

    static public function GetPropaty($elm) {
        if (property_exists('PublicSetting\Setting', $elm) !== false) {
            return self::$elm;
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

    static public function GetPosts() {
        return Sanitize($_POST);
    }

    // 指定した要素のPost値を取得
    static public function GetPost($elm = '') {
        $_post = Sanitize($_POST);
        if (key_exists($elm, $_post)) {
            return $_post[$elm];
        } else {
            return null;
        }
    }

    static public function GetRemoteADDR() {
        return self::GetSERVER('REMOTE_ADDR');
    }

    // すべてのGet値を取得
    static public function GetRequest() {
        return Sanitize($_GET);
    }

    // 指定した要素のGet値を取得
    static public function GetQuery($elm = '') {
        $_get = Sanitize($_GET);
        if (key_exists($elm, $_get)) {
            return $_get[$elm];
        } else {
            return null;
        }
    }

    static public function GetFiles() {
        return $_FILES;
    }

    // 公開パスなどのURLを取得
    public function GetUrl($query='', $type = 'url') {
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
