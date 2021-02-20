<?php
$commonWordPath = dirname(dirname(dirname(__DIR__)));
$commonWordPath = AddPath($commonWordPath, 'common');
$commonWordPath = AddPath($commonWordPath, 'Word');
$commonWordPath = AddPath($commonWordPath, 'Message.php', false);
require_once $commonWordPath;
// CSRFクラス
function Public_CSRFErrorMessage() {
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
define('PUBLIC_DIR', AddPath(DOCUMENT_ROOT, 'public', false));
define('PUBLIC_COMMON_DIR', AddPath(PUBLIC_DIR, 'common', false));
define('PUBLIC_CLIENT_DIR', AddPath(PUBLIC_DIR, 'client', false));
define('PUBLIC_CSS_DIR', AddPath(PUBLIC_CLIENT_DIR, '/css', false));
define('PUBLIC_JS_DIR', AddPath(PUBLIC_CLIENT_DIR, '/js', false));
define('PUBLIC_IMAGE_DIR', AddPath(PUBLIC_CLIENT_DIR, '/image', false));
define('PUBLIC_3D_DIR', AddPath(PUBLIC_CLIENT_DIR, '3d', false));
define('PUBLIC_CSV_DIR', AddPath(PUBLIC_CLIENT_DIR, 'csv', false));
define('PUBLIC_COMPONENT_DIR', AddPath(PUBLIC_COMMON_DIR, 'Component', false));
define('PUBLIC_LAYOUT_DIR', AddPath(PUBLIC_COMMON_DIR, 'Layout', false));
// 画像閲覧ページ
define('PUBLIC_PREVIOUS', '画像閲覧ページへ戻る');
define('DEFAULT_VIEW', VIEW);
