<?php
namespace PrivateSetting;
require_once AddPath(dirname(__DIR__, 2), AddPath('common', 'Setting.php', false), false);
function GetSelf_Admin() {
    return $_SERVER['PHP_SELF'];
}

function GetPrevPage_Admin() {
    return filter_input(INPUT_SERVER, 'HTTP_REFERER');
}


function getURI_Admin() {
    return $_SERVER['REQUEST_URI'];
}

class Setting extends \commonSetting\Setting {

    protected $domain, $url, $public;
    protected $client, $css, $js, $image;

    function __construct()
    {
        // 基本設定(親クラスのコンストラクタにアクセス)
        parent::__construct();

        // 基本設定
        $this->private = AddPath('', 'private');
        $this->client = AddPath($this->private, 'client', true, '/');


        // 公開パス関係
        $this->css = AddPath($this->client, 'css', false, '/');
        $this->js = AddPath($this->client, 'js', false, '/');
        $this->image = AddPath($this->client, 'image', false, '/');
        $this->csv = AddPath($this->client, 'csv', false, '/');
        $this->filepageImage = AddPath($this->public, 'image', false, '/');
    }

}

class Admin extends Setting {
    function __construct()
    {
    }

    public static function GetSelf()
    {
        return $_SERVER['PHP_SELF'];
    }

    public static function GetPrevPage()
    {
        return filter_input(INPUT_SERVER, 'HTTP_REFERER');
    }


    public static function getURI()
    {
        return $_SERVER['REQUEST_URI'];
    }
}

$commonPath = AddPath(dirname(__DIR__, 2), basename(__DIR__));

require_once(AddPath($commonPath, 'Setting.php', false));

// セッションクラス (公開側)
class Session extends \CommonSetting\Session
{
}

// クッキークラス (公開側)
class Cookie extends \CommonSetting\Cookie
{
}

$domain = $_SERVER['SERVER_NAME'];

$url = $http.$domain;
$private = $url. '/private/';

// 定数などの定義
$Agent = $_SERVER['HTTP_USER_AGENT'];
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
}

$public = $url . '/public/';

// 公開ファイル関係
$client = $public . 'client/';
$image = $client . 'image';

define('IMAGE_URL', $image);

