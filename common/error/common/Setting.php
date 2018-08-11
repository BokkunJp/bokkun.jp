<?php
// サーバの設定
namespace ErrorSetting;
if (isset($_SERVER['HTTPS'])) {
    $http = '//';
} else {
    $http = 'http://';
}
$domain = $_SERVER['SERVER_NAME'];

$url = $http.$domain;

$public = $url.'/public/';

// 定数などの定義
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('COMMON_DIR', __DIR__);
define('PUBLIC_DIR', dirname(__DIR__));
define('FUNCTION_DIR', COMMON_DIR. '/Function');
define('LAYOUT_DIR', COMMON_DIR. '/Layout');
define('ROOT', '/');
$Agent = $_SERVER['HTTP_USER_AGENT'];
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
}

// 設定関係の関数の定義
function GetURI() {
    return $_SERVER['REQUEST_URI'];
}

function GetPost() {
    return $_POST;
    }

// 設定関係のクラス化(実装中)
class Base {
    public $a;

    function __construct() {
        $this->a = 0;
    }

    public function getA() {
        $this->a;
    }
}

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
    private function Initialize($filePathInit=true, $modeInit=true) {
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

class Session {
    private $init;
    private $session;
    function __construct() {
        $this->Read();
        $this->init = $this->session;
    }

    private function Write() {
        $_SESSION = $this->session;
    }

    public function Add($sessionElm, $sessionVal) {
        $this->session[$sessionElm] = $sessionVal;
        $this->Write();
    }

    public function Read($sessionElm=null) {
        if (isset($_SESSION)) {
            $this->session = $_SESSION;
        } else {
            trigger_error('Session is not found.', E_USER_ERROR);
            exit;
        }
        if (isset($sessionElm)) {
            return $this->session[$sessionElm];
     } else {
         return $this->session;
        }
    }
    
    public function Delete($sessionElm=null) {
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
    public function View($id=null) {
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
    public function Destroy() {
        session_unset();

        // セッションを切断するにはセッションクッキーも削除する。
        // Note: セッション情報だけでなくセッションを破壊する。
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
        }

        // 最終的に、セッションを破壊する
        session_destroy();
    }
}
