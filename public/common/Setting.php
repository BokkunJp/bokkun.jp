<?php

// サーバの設定

namespace PublicSetting;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'InitFunction.php';
if (isset($_SERVER['HTTPS'])) {
    $http = '//';
} else {
    $http = 'http://';
}

// 設定のベースとなる変数
$domain = Setting::GetServarName('SERVER_NAME');
$url = $http . $domain;
$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$css = $client . 'css';
$js = $client . 'js';
$image = $client . 'image';

// 定数などの定義
require_once AddPath(__DIR__, "Config.php", false);

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

    static public function GetServarName($elm) {
        return self::GetSERVER($elm);
    }

    static public function GetPropaty($elm) {
        if (property_exists('PublicSetting\Setting', $elm) !== false) {
            return $this->$elm;
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

// セッションクラス
class Session {

    private $init;
    private $session;

    function __construct() {
        $this->Read();
        $this->init = $this->session;
    }

    private function SessionStart() {
        if (!isset($_SESSION) || session_status() === PHP_SESSION_DISABLED) {
            session_start();
        }
    }

    private function Write() {
        $_SESSION = $this->session;
    }

    public function Add($sessionElm, $sessionVal) {
        $this->session[$sessionElm] = $sessionVal;
        $this->Write();
    }

    public function Read($sessionElm = null) {
        if (!isset($_SESSION)) {
            $this->SessionStart();
        }

        $this->session = $_SESSION;

        if (isset($sessionElm)) {
            return $this->session[$sessionElm];
        } else {
            return $this->session;
        }
    }

    public function Delete($sessionElm = null) {
        if (!isset($_SESSION)) {
            trigger_error('Session is already deleted.', E_USER_ERROR);
            exit;
        }
        if (isset($sessionElm)) {
            unset($this->session[$sessionElm]);
            $this->Write();
        } else {
            unset($this->session);
            $this->session = $this->init;
        }
    }

    // セッション閲覧用
    public function View($id = null) {
        if (isset($id)) {
            if (isset($this->session[$id])) {
                echo $this->session[$id];
            } else {
                return false;
            }
        } else {
            var_dump($this->session);
        }
        return true;
    }

    // セッションの完全な破棄
    public function FinaryDestroy() {
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
