<?php

namespace private;

use SessionTrait;

require_once AddPath(dirname(__DIR__, 2), AddPath('common', 'Setting.php', false), false);
class Setting extends \commonSetting\Setting
{
    protected $private;
    protected $domain;
    protected $url;
    protected $public;
    protected $client;
    protected $css;
    protected $js;
    protected $image;
    protected $csv;
    protected $filepageImage;

    public function __construct()
    {
        // 基本設定(親クラスのコンストラクタにアクセス)
        parent::__construct();

        // 基本設定
        $this->private = AddPath('', 'private', separator:'/');
        $this->client = AddPath($this->private, 'client', true, '/');


        // 公開パス関係
        $this->css = AddPath($this->client, 'css', false, '/');
        $this->js = AddPath($this->client, 'js', false, '/');
        $this->image = AddPath($this->client, 'image', false, '/');
        $this->csv = AddPath($this->client, 'csv', false, '/');
        $this->filepageImage = AddPath($this->public, 'image', false, '/');
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

$commonPath = AddPath(dirname(__DIR__, 2), basename(__DIR__));

require_once(AddPath($commonPath, 'Setting.php', false));

$traitPath = AddPath($commonPath, 'Trait', false);

require_once(AddPath($traitPath, 'SessionTrait.php', false));

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
