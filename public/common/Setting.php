<?php
// サーバの設定
namespace PublicSetting;
require_once __DIR__. DIRECTORY_SEPARATOR. 'InitFunction.php';
if (isset($_SERVER['HTTPS'])) {
    $http = '//';
} else {
    $http = 'http://';
}

// 設定のベースとなる変数
$domain = GetSERVER('SERVER_NAME');
$url = $http.$domain;
$public = $url.'/public/';

// 公開ファイル関係
$client = $public. 'client/';
$css = $client. 'css';
$js = $client. 'js';
$image = $client. 'image';

// 定数などの定義
require_once AddPath(__DIR__, "Config.php", false);

define('IMAGE_URL', $image);
$Agent = GetSERVER('HTTP_USER_AGENT');
$referer = GetSERVER('HTTP_REFERER');

// 設定関係のクラス化(実装中)
class Setting {
  private $domain, $url, $public;
  private $client, $css, $js, $image;

  function __construct() {
    // 基本設定
    $this->InitSSL($this->url);
    $this->domain = $this->GetSERVER('SERVER_NAME');
    $this->url = $this->url.$this->domain;
    $this->public = $this->url.'/public/';

    // 公開パス関係
    $this->client = $this->public. 'client/';
    $this->css = $this->client. 'css';
    $this->js = $this->client. 'js';
    $this->image = $this->client. 'image';
  }

  private function InitSSL(&$http) {
    $http_flg = $this->GetSERVER('HTTPS');
    if (isset($http_flg)) {
        $http = '//';
    } else {
        $http = 'http://';
    }
  }

  private function GetSERVER($elm) {
      if (isset($_SERVER[$elm])) {
          return Sanitize($_SERVER[$elm]);
      } else {
          return null;
      }
  }

  public function GetPropaty($elm) {
    if (property_exists('PublicSetting\Setting', $elm) === false) {
      return null;
    }

    return $this->$elm;
  }

  public function GetURI() {
      return $this->GetSERVER('REQUEST_URI');
  }

  public function GetPost() {
      return $_POST;
  }

  public function GetQuery() {
      return $_GET;
  }

  public function GetRequest() {
      return $_GET;
  }

  public function GetFiles() {
      return $_FILES;
  }

  public function MakeUrl($query) {
      return $url. '/'. $query;
  }

}

// 設定関係の関数の定義
function GetSERVER($elm) {
    if (isset($_SERVER[$elm])) {
        return $_SERVER[$elm];
    } else {
        return null;
    }
}

function GetURI() {
    return GetSERVER('REQUEST_URI');
}

function GetPost() {
    return $_POST;
}

function GetQuery() {
    return $_GET;
}

function GetRequest() {
    return $_GET;
}

function GetFiles() {
    return $_FILES;
}

function MakeUrl($query) {
    return $url. '/'. $query;
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

    public function Read($sessionElm=null) {
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
    public function FinaryDestroy() {
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
