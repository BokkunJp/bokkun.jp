<?php

namespace Private\Important;

require_once dirname(__DIR__, 2). "/common/Initialize/Path.php";

$sesttingPath = new \Path(dirname(__DIR__, 2));
$sesttingPath->addArray(['common', 'Setting.php']);
require_once $sesttingPath->get();

class Setting extends \Common\Important\Setting
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
        $this->private->add('private');
        $this->client = new \Path($this->private->get(), '/');
        $this->private = $this->private->get();
        $this->client->add('client');
        $this->client = $this->client->get();

        // 公開パス関係
        $publicPath = new \PathApplication('private', $this->client, '/');
        $publicList = [
            'css' => 'css',
            'js' => 'js',
            'image' => 'image',
            'csv' => 'csv',
            'filepageImage' => 'filepageImage',
        ];
        $publicPath->setAll($publicList);

        foreach ($publicList as $_public) {
            $publicPath->resetKey($_public);
            $this->$_public = $publicPath->get();
            }
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
        return parent::getServer('HTTP_HOST');
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
            $ret = parent::getServer('REMOTE_ADDR');
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
        return parent::getServer('PHP_SELF');
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
        return parent::getServer('HTTP_REFERER');
    }


    /**
     * getRUI
     *
     * URIを取得する。
     *
     * @return string
     */
    public static function getUri(): string
    {
        return self::getServer('REQUEST_URI');
    }

    /**
     * Judge
     *
     * AjaxかどうかをHTTP_X_REQESTED_WITHで判断する。
     *
     * @return string|null
     */
    public static function judgeAjax(): string|null
    {
        return self::getServer('HTTP_X_REQUESTED_WITH');
    }
}

$commonPath = new \Path(dirname(__DIR__, 2));
$commonPath->add('common');
$settingPath = new \Path($commonPath->get());
$settingPath->setPathEnd();
$settingPath->add('Setting.php');
require_once $settingPath->get();

$traitPath = new \Path($commonPath->get());
$traitPath->addArray(['Trait', 'SessionTrait.php']);
require_once $traitPath->get();

$domain = filterInputFix(INPUT_SERVER, 'SERVER_NAME');

$url = $http.$domain;
$private = $url. '/private/';

// 定数などの定義
$agent = filterInputFix(INPUT_SERVER, 'HTTP_USER_AGENT');
$referer = filterInputFix(INPUT_SERVER, 'HTTP_REFERER');

$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$image = $client . 'image';

define('IMAGE_URL', $image);
