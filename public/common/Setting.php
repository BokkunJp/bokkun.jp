<?php

// サーバの設定
namespace PublicSetting;

if (isset($_SERVER['HTTPS'])) {
    $http = '//';
} else {
    $http = 'http://';
}

// 設定のベースとなる変数
$domain = Setting::GetServarName();
$url = $http . $domain;
$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$css = $client . 'css';
$js = $client . 'js';
$image = $client . 'image';


define('IMAGE_URL', $image);

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
    public function GetUrl($query='', $type='url') {
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

// パーミッション関係
class Permmision {

    private $filePath;
    private $mode;

    CONST WRITE = 02;
    CONST READ = 04;
    CONST EXECUTE = 01;

    function __construct() {
        $this->Initialize();
    }

    // パーミッション変数の初期化
    private function Initialize($filePathInit = true, $modeInit = true) {
        if ($filePathInit === true) {
            $this->filePath = '';
        }

        if ($modeInit === true) {
            $this->mode = 0;
        }
    }

    // パーミッション変更
    private function Convert($fileNamePath, $mode) {
        @chmod($fileNamePath, $mode);
    }

    // パーミッション許可
    public function Allow($filePath, $orderName, $mode) {
        if ($orderName) {

        }
        // $this->WhoCheck();
        $this->Convert($filePath, $mode);
    }

    // パーミッション拒否
    public function Deny($filePath, $orderName, $mode) {

    }

}

$commonPath = AddPath(dirname(dirname(__DIR__)), basename(__DIR__));

require_once(AddPath($commonPath, 'Setting.php', false));

// セッションクラス (公開側)
class Session extends \CommonSetting\Session {

}

// クッキークラス (公開側)
class Cookie extends \CommonSetting\Cookie {

}
