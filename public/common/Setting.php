<?php
// サーバの設定
namespace PublicSetting;
require_once AddPath(dirname(__DIR__, 2), AddPath('common', 'Setting.php', false), false);

// 設定関係のクラス (共通クラスを親クラスとする)
class Setting extends \commonSetting\Setting {

    protected $public, $client;

    function __construct() {
        // 基本設定(親クラスのコンストラクタにアクセス)
        parent::__construct();

        // 公開パス関係
        $this->css = AddPath($this->client, 'css', false, '/');
        $this->js = AddPath($this->client, 'js', false, '/');
        $this->image = AddPath($this->client, 'image', false, '/');
        $this->csv = AddPath($this->client, 'csv', false, '/');
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

// 設定のベースとなる変数
$domain = Setting::GetServarName();
$url = $http . $domain;
$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$image = $client . 'image';


define('IMAGE_URL', $image);

