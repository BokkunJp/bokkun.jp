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
// define('PLUGIN_DIR', AddPath(dirname(dirname(DOCUMENT_ROOT)), 'Plugin', false));
define('PRIVATE_COMMON_DIR', dirname(__DIR__));
define('PRIVATE_DIR', AddPath(DOCUMENT_ROOT, 'private', false));
define('PRIVATE_CLIENT_DIR', AddPath(PRIVATE_DIR, 'client', false));
define('PRIVATE_CSS_DIR', AddPath(PRIVATE_CLIENT_DIR, 'css', false));
define('PRIVATE_JS_DIR', AddPath(PRIVATE_CLIENT_DIR, 'js', false));
define('PRIVATE_IMAGE_DIR', AddPath(PRIVATE_CLIENT_DIR, 'image', false));

// define('PRIVATE_CSV_DIR', AddPath(PRIVATE_CLIENT_DIR, 'csv', false));
define('PRIVATE_COMPONENT_DIR', AddPath(PRIVATE_COMMON_DIR, 'Component', false));
define('PRIVATE_LAYOUT_DIR', AddPath(PRIVATE_COMMON_DIR, 'Layout', false));
// define('DEBUG_CODE', __FILE__ . ':' . __LINE__);
// define('NOW_PAGE', basename(getcwd()));
// define('SECURITY_LENG', 32);
define('PRIVATE_PREVIOUS', '画像管理ページへ戻る');

// 管理側の追加ソース
define('ADD_DESIGN', 'require AddPath(__DIR__, "design.php", false);');

// 公開側画像パス
define('PUBLIC_IMAGE_DIR', AddPath(AddPath(DOCUMENT_ROOT, 'public'), AddPath('client', 'image'), false));

// デフォルトの画像ページ
define('DEFAULT_IMAGE', 'IMAGE');

// デフォルトの可視フラグ
define('DEFAULT_VIEW', VIEW);

// 削除不可リスト
define('NOT_DELETE_FILE_LIST', ['IMAGE']);

// ログインパスワード
define("LOGIN_PASSWORD", "bokkunAdmin777");
