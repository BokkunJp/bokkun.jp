<?php

// サーバの設定

namespace Error\Important;

$http_flg = filterInputFix(INPUT_SERVER, 'HTTPS');
if (isset($http_flg)) {
    $http = '//';
} else {
    $http = 'http://';
}

// 定数などの定義
$agent = filterInputFix(INPUT_SERVER, 'HTTP_USER_AGENT');
$referer = filterInputFix(INPUT_SERVER, 'HTTP_REFERER');

$configPath = new \Path(COMMON_DIR);
$configPath->setPathEnd();
$configPath->add('Config.php');
require_once $configPath->get();
$siteConfig = ['header' => new \Header(), 'footer' => new \Footer()];

// 設定関係のクラス
class Setting extends \Common\Important\Setting
{
    // 公開パスなどのURLを取得
    public function getUrl($query='', $type = 'url', $relativePath = false): string
    {
        if ($relativePath === false) {
            $url = $this->url;
        } else {
            $url = '';
        }

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
            case 'error':
                $url = $this->error;
                break;
            default:
                $url = $this->url;
                break;
        }
        return $url . '/' . $query;
    }
}

class Permmision
{
    private $filePath;
    private $mode;
    public const WRITE = 02;
    public const READ = 04;
    public const EXECUTE = 01;

    public function __construct()
    {
        $this->initialize();
    }

    // パーミッション変数の初期化
    private function initialize($filePathInit=true, $modeInit=true)
    {
        if ($filePathInit === true) {
            $this->filePath = '';
        }

        if ($modeInit === true) {
            $this->mode = 0;
        }
    }

    // パーミッション変更
    private function convert($fileNamePath, $mode)
    {
        @chmod($fileNamePath, $mode);
    }

    // パーミッション許可
    public function allow($filePath, $orderName, $mode)
    {
        if ($orderName) {
        }
        // $this->WhoCheck();
        $this->convert($filePath, $mode);
    }

    // パーミッション拒否
    public function deny($filePath, $orderName, $mode)
    {
    }
}

class Session
{
    private $init;
    private $session;
    public function __construct()
    {
        $this->read();
        $this->init = $this->session;
    }

    private function write()
    {
        $_SESSION = $this->session;
    }

    public function add($sessionElm, $sessionVal)
    {
        $this->session[$sessionElm] = $sessionVal;
        $this->write();
    }

    public function read($sessionElm=null)
    {
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

    public function delete($sessionElm=null)
    {
        if (!isset($_SESSION)) {
            trigger_error('Session is already deleted.', E_USER_ERROR);
            exit;
        }
        if (isset($sessionElm)) {
            unset($this->session[$sessionElm]);
            $this->write();
        } else {
            unset($this->session);
            $this->session = $this->init;
        }
    }

    // セッション閲覧用
    public function view($id=null)
    {
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

    // セッションの完全な破棄s
    public function destroy()
    {
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

// インスタンスの定義
$base = new Setting();
$includePath = new \Path(__DIR__);
$includePath->setPathEnd();
$includePath->add('Include.php');
require_once $includePath->get();
