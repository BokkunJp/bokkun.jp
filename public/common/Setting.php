<?php

// サーバの設定

namespace public;

require_once AddPath(dirname(__DIR__, 2), AddPath('common', 'Setting.php', false), false);

// 設定関係のクラス (共通クラスを親クラスとする)
class Setting extends \common\Setting
{
    protected $public;
    protected $client;

    public function __construct()
    {
        // 基本設定(親クラスのコンストラクタにアクセス)
        parent::__construct();

        // 公開パス関係
        $this->css = AddPath($this->client, 'css', false, '/');
        $this->js = AddPath($this->client, 'js', false, '/');
        $this->image = AddPath($this->client, 'image', false, '/');
        $this->csv = AddPath($this->client, 'csv', false, '/');
    }

    /**
     * Judge
     *
     * AjaxかどうかをHTTP_X_REQESTED_WITHで判断する。
     *
     * @return ?string
     */
    public static function JudgeAjax(): ?string
    {
        return self::GetSERVER('HTTP_X_REQUESTED_WITH');
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
        $this->Initialize();
    }

    // パーミッション変数の初期化
    private function Initialize($filePathInit = true, $modeInit = true)
    {
        if ($filePathInit === true) {
            $this->filePath = '';
        }

        if ($modeInit === true) {
            $this->mode = 0;
        }
    }

    // パーミッション変更
    private function Convert($fileNamePath, $mode)
    {
        @chmod($fileNamePath, $mode);
    }

    // パーミッション許可
    public function Allow($filePath, $orderName, $mode)
    {
        if ($orderName) {
        }
        // $this->WhoCheck();
        $this->Convert($filePath, $mode);
    }

    // パーミッション拒否
    public function Deny($filePath, $orderName, $mode)
    {
    }
}

$commonPath = AddPath(dirname(__DIR__, 2), basename(__DIR__));

require_once(AddPath($commonPath, 'Setting.php', false));

// 設定のベースとなる変数
$domain = Setting::GetServerName();
$url = $http . $domain;
$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$image = $client . 'image';


define('IMAGE_URL', $image);
