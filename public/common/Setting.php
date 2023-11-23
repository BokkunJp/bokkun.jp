<?php

// サーバの設定
namespace Public\Important;

$commonSettingPath = new \Path(dirname(__DIR__, 2));
$commonSettingPath->addArray(['common', 'Setting.php']);
$commonPath = $commonSettingPath->get();
require_once $commonPath;

$test = new \Common\Important\Setting();

// 設定関係のクラス (共通クラスを親クラスとする)
class Setting extends \Common\Important\Setting
{
    public function __construct()
    {
        // 基本設定(親クラスのコンストラクタにアクセス)
        parent::__construct();

        // 公開パス関係
        $client = new \PathApplication("client", $this->client);
        $pathList = [
            'css' => 'css',
            'js' => 'js',
            'image' => 'image',
            'csv' => 'csv'
        ];

        $client->setAll($pathList);

        $client->methodPath("ResetKey", "/");
        foreach ($pathList as $_path) {
            $client->resetKey($_path);
            $this->$_path = $client->get();
        }
    }

    /**
     * Judge
     *
     * AjaxかどうかをHTTP_X_REQESTED_WITHで判断する。
     *
     * @return ?string
     */
    public static function judgeAjax(): ?string
    {
        return self::getServer('HTTP_X_REQUESTED_WITH');
    }
}

// パーミッション関係
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
    private function initialize($filePathInit = true, $modeInit = true)
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

// 設定のベースとなる変数
$domain = Setting::getServerName();
$url = $http . $domain;
$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$image = $client . 'image';


define('IMAGE_URL', $image);
