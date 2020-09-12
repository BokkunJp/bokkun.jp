<?php
// CSRFクラス
function CSRFErrorMessage() {
    $addr = PublicSetting\Setting::GetRemoteADDR();
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
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('API_DIR', DOCUMENT_ROOT . '/API');
define('COMMON_DIR', dirname(__DIR__));
// define('PUBLIC_DIR', DOCUMENT_ROOT . '/public');
// define('CLIENT_DIR', PUBLIC_DIR . '/client');
// define('CSS_DIR', CLIENT_DIR . '/css');
// define('JS_DIR', CLIENT_DIR . '/js');
// define('IMAGE_DIR', CLIENT_DIR . '/image');
// define('CSV_DIR', CLIENT_DIR . '/csv');
// define('COMPONENT_DIR', COMMON_DIR . '/Component');
// define('LAYOUT_DIR', COMMON_DIR . '/Layout');
define('NL', nl2br(PHP_EOL));
define('DEBUG_CODE', __FILE__ . ':' . __LINE__);
define('NOW_PAGE', basename(getcwd()));
define('SECURITY_LENG', 32);

// FILEページの文言
define('VIEW', 1);
define('NOT_VIEW', -1);

define('PAGER', 10);
define('MAX_VIEW', 10);
define('MIN_PAGE_COUNT', 1);
define('SPACE_ON', 1);
define('COUNT_START', 3);
define('ERRMessage', 'エラーが発生しました。');


define('FILE_COUNT_MAX', 50);
define('FILE_COUNT_OVER', -1);

define('FILE_NONE', "ファイルが選択されていません。");
define('FILE_COUNT_OVER_ERROR', (FILE_COUNT_MAX + 1)."枚以上の画像をアップロードすることはできません。処理を中断します。");
// アップロード後の文言 (ファイル数の部分は処理時に定義)
define('FILE_NO_MATCH_FAIL', "は、画像ファイルではないためアップロードできませんでした。");
define('FILE_UPLOAD_FAIL', "のアップロードに失敗しました。");
define('FILE_UPLOAD_SUCCESS', "のアップロードに成功しました。");

