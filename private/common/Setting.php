<?php

namespace private;

if (!class_exists("Path")) {
    require_once dirname(__DIR__, 2). "/common/Initialize/Path.php";
}

$sesttingPath = new \Path(dirname(__DIR__, 2));
$sesttingPath->AddArray(['common', 'Setting.php']);
require_once $sesttingPath->Get();
class Setting extends \common\Setting
{
    protected \Path|string $private;
    protected string $domain;
    protected ?string $url;
    protected string $public;
    protected \Path|string $client;
    protected string $css;
    protected string $js;
    protected string $image;
    protected string $csv;
    protected string $filepageImage;

    public function __construct()
    {
        // 基本設定(親クラスのコンストラクタにアクセス)
        parent::__construct();

        // 基本設定
        $this->private = new \Path('', '/');
        $this->private->Add('private');
        $this->client = new \Path($this->private->Get(), '/');
        $this->private = $this->private->Get();
        $this->client->Add('client');
        $this->client = $this->client->Get();

        // 公開パス関係
        $publicPath = new \PathApplication('css', $this->client, '/');
        $publicPath->SetAll([
            'js' => '',
            'image' => '',
            'csv' => '',
            'filepageImage' => $this->public
        ]);
        $publicPath->ResetKey('css');
        $publicPath->MethodPath('Add', 'css');
        $this->css = $publicPath->Get();


        $publicPath->ResetKey('js');
        $publicPath->MethodPath('Add', 'js');
        $this->js = $publicPath->Get();


        $publicPath->ResetKey('image');
        $publicPath->MethodPath('Add', 'image');
        $this->image = $publicPath->Get();

        $publicPath->ResetKey('csv');
        $publicPath->MethodPath('Add', 'csv');
        $this->csv = $publicPath->Get();

        $publicPath->ResetKey('filepageImage');
        $publicPath->MethodPath('Add', 'image');
        $this->image = $publicPath->Get();
    }

    /**
     * GetDomain
     *
     * URLのドメインを取得
     *
     * @return mixed
     */
    public static function GetDomain(): string
    {
        return parent::GetSERVER('HTTP_HOST');
    }


    /**
     * GetHostIp
     *
     * IPアドレスを取得
     *
     * @return mixed
     */
    public static function GetHostIp($hostName = null): string
    {
        if (is_null($hostName)) {
            $ret = parent::GetSERVER('REMOTE_ADDR');
        } else {
            $ret = gethostbyname($hostName);
        }
        return $ret;
    }

    /**
     * GetHostName
     *
     * IPアドレスからホスト名を取得
     *
     * @return mixed
     */
    public static function GetHostName($ipName = null): mixed
    {
        if (is_null($ipName)) {
            $ipName = self::GetHostIp();
        }

        return gethostbyaddr($ipName);
    }

    /**
     * GetSelf
     *
     * PHP_SELFを取得する。
     *
     * @return mixed
     */
    public static function GetSelf(): mixed
    {
        return parent::GetSERVER('PHP_SELF');
    }

    /**
     * GetPrevPage
     *
     * リファラを取得する。
     *
     * @return mixed
     */
    public static function GetPrevPage(): mixed
    {
        return parent::GetSERVER('HTTP_REFERER');
    }


    /**
     * getRUI
     *
     * URIを取得する。
     *
     * @return string
     */
    public static function GetURI(): string
    {
        return self::GetSERVER('REQUEST_URI');
    }

    /**
     * Judge
     *
     * AjaxかどうかをHTTP_X_REQESTED_WITHで判断する。
     *
     * @return string|null
     */
    public static function JudgeAjax(): string|null
    {
        return self::GetSERVER('HTTP_X_REQUESTED_WITH');
    }
}

$commonPath = new \Path(dirname(__DIR__, 2));
$commonPath->Add('common');
$settingPath = new \Path($commonPath->Get());
$settingPath->SetPathEnd();
$settingPath->Add('Setting.php');
require_once $settingPath->Get();

$traitPath = new \Path($commonPath->Get());
$traitPath->AddArray(['Trait', 'SessionTrait.php']);
require_once $traitPath->Get();

$domain = filter_input_fix(INPUT_SERVER, 'SERVER_NAME');

$url = $http.$domain;
$private = $url. '/private/';

// 定数などの定義
$agent = filter_input_fix(INPUT_SERVER, 'HTTP_USER_AGENT');
$referer = filter_input_fix(INPUT_SERVER, 'HTTP_REFERER');

$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$image = $client . 'image';

define('IMAGE_URL', $image);
