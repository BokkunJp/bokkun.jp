<?php

// サーバの設定

namespace public;

$commonSettingPath = new \Path(dirname(__DIR__, 2));
$commonSettingPath->AddArray(['common', 'Setting.php']);
$commonPath = $commonSettingPath->Get();
require_once $commonPath;

// 設定関係のクラス (共通クラスを親クラスとする)
class Setting extends \common\Setting
{
    public function __construct()
    {
        // 基本設定(親クラスのコンストラクタにアクセス)
        parent::__construct();

        // 公開パス関係
        $client = new \PathApplication("css", $this->client);
        $client->SetAll([
            'js' => '',
            'image' => '',
            'csv' => ''
        ]);

        $client->MethodPath("ResetKey", "/");
        $client->ResetKey('css');
        $client->MethodPath("Add", "css");
        $this->css = $client->Get();
        $client->ResetKey('js');
        $client->MethodPath("Add", "js");
        $this->js = $client->Get();
        $client->ResetKey('image');
        $client->MethodPath("Add", "image");
        $this->image = $client->Get();
        $client->ResetKey('csv');
        $client->MethodPath("Add", "csv");
        $this->csv = $client->Get();
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

// 設定のベースとなる変数
$domain = Setting::GetServerName();
$url = $http . $domain;
$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$image = $client . 'image';


define('IMAGE_URL', $image);
