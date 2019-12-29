<?php
    $commonWordPath = dirname(
    dirname(dirname(__DIR__)));
    $commonWordPath = AddPath($commonWordPath, 'common');
    $commonWordPath = AddPath($commonWordPath, 'Word');
    $commonWordPath = AddPath($commonWordPath, 'Message.php', false);
    require_once $commonWordPath;
// CSRFクラス
function PrivateCSRFErrorMessage() {
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
// define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
// define('API_DIR', DOCUMENT_ROOT . '/API');
define('PRIVATE_COMMON_DIR', dirname(__DIR__));
define('PRIVATE_DIR', DOCUMENT_ROOT . '/private');
define('PRIVATE_CLIENT_DIR', PRIVATE_DIR . '/client');
define('PRIVATE_CSS_DIR', PRIVATE_CLIENT_DIR . '/css');
define('PRIVATE_JS_DIR', PRIVATE_CLIENT_DIR . '/js');
define('PRIVATE_IMAGE_DIR', PRIVATE_CLIENT_DIR . '/image');
// define('CSV_DIR', CLIENT_DIR . '/csv');
define('PRIVATE_COMPONENT_DIR', PRIVATE_COMMON_DIR . '/Component');
define('PRIVATE_LAYOUT_DIR', PRIVATE_COMMON_DIR . '/Layout');
// define('DEBUG_CODE', __FILE__ . ':' . __LINE__);
// define('NOW_PAGE', basename(getcwd()));
// define('SECURITY_LENG', 32);
