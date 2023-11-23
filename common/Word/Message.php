<?php

// CSRFクラス
function setCsrfErrorMessage()
{
    $addr = Public\Important\Setting::getRemoteAddr();
    $errMessage = "<p><strong>". gethostbyaddr($addr). "(". $addr. ")". "様のアクセスは禁止されています。</strong></p><p>以下の要因が考えられます。</p>";
    $errList = ["指定回数以上アクセスした。", "直接アクセスした。", "不正アクセスした。"];
    $errMessage .='<ul>';
    $errLists = '';
    foreach ($errList as $_errList) {
        $errLists .= "<li>{$_errList}</li>";
    }
    $errMessage .= $errLists;
    $errMessage .='</ul>';

    return $errMessage;
}

// 共通部分
$root = filterInputFix(INPUT_SERVER, 'DOCUMENT_ROOT');
if (!preg_match('/^(.*)\.(.*)/', basename(dirname(__DIR__, 2)))) {
    $root .= '/'. basename(dirname(__DIR__, 2));
}
define('DOCUMENT_ROOT', $root);

define('COMMON_DIR', dirname(__DIR__));
define('COMPONENT_DIR', dirname(__DIR__). DIRECTORY_SEPARATOR. "Component");
define('NL', nl2br(PHP_EOL));
define('DEBUG_CODE', __FILE__ . ':' . __LINE__);
define('NOW_PAGE', basename(getcwd()));
define('SECURITY_LENG', 32);

// imageページの文言
define('VIEW', 1);
define('NOT_VIEW', -1);

define('PAGER', 10);
define('MAX_VIEW', 10);
define('IMAGE_MAX_VALUE', 1024);
define('MIN_PAGE_COUNT', 1);
define('SPACE_ON', 1);
define('ERROR_MESSAGE', 'エラーが発生しました。');

// デバッグ表示エラー
define(
    'DEBUG_MESSAGE_SOURCE',
    array(
        "ERR_DEBUG_COND" => "デバッグに必要な要件を満たせていません。(modeとlayerの引数が必要です。)",
        "ERR_DEBUG_FEW_TRACE_LAYER" => "階層の指定が不正です。",
        "ERR_DEBUG_TOO_TRACE_LAYER" => "その階層にはデバッグリソースが存在しません。",
        "SETTING_DEBUG_TRACE" => "デバッグトレース表示の調整：mode引数にfile, line, functionのいずれかを指定してください。それ以外の文字列入力で全項目表示されます。(2項目を指定したりはできません。)"
    )
);

// プラグインパス
$pluginWord = new Path(dirname(DOCUMENT_ROOT, 2));
$pluginWord->add('Plugin');
define("PLUGIN_DIR", $pluginWord->get());

// FINISHフラグ
define("FINISH", 1);
