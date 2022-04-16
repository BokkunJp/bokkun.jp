<?php

$commonWordPath = dirname(
    dirname(dirname(__DIR__))
);
$commonWordPath = AddPath($commonWordPath, 'common');
$commonWordPath = AddPath($commonWordPath, 'Word');
$commonWordPath = AddPath($commonWordPath, 'Message.php', false);
require_once $commonWordPath;

// CSRFクラス
function PrivateCSRFErrorMessage()
{
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

// 削除不可リスト
define('NOT_DELETE_FILE_LIST', ['MAIL', 'IMAGE', 'CSV', 'QR', 'liDAR-WebGL', 'webGL']);

// ログインパスワード
define("LOGIN_PASSWORD", "bokkunAdmin777");

// 画像アップロード・削除・コピー共通
define('NUMBER_OF_IMAGE', '枚の画像ファイル');
// 画像アップロード用の文言 (ファイル数の部分は処理時に定義)
define('FAIL_UPLOAD_IMAGE', "のアップロードに失敗しました。");

define('NOT_MATCH_IMAGE', "は、画像ファイルではないためアップロードできませんでした。");

define('ILLEGAL_UPLOAD_IMAGE', "は、不正な方法でのアップロードのため、アップロードを取り消しました。");

define('EMPTY_IMAGE_SIZE', "は、画像のファイルサイズが0バイトのためアップロードできませんでした。");

define('SUCCESS_UPLOAD_IMAGE', "のアップロードに成功しました。");

// 画像削除・コピー用の文言
// 画像削除関連
define('NOT_FOUND_DLETE_IMAGE', '削除対象が選択されていないか、画像がありません。');

define('FAIL_DELETE_IMAGE', '件の画像の削除に失敗しました。');

define('FAIL_REASON_SYSTEM', '・処理中に問題が発生したため、');

define('FAIL_DELETE_IMAGE_DETAIL', 'を削除できませんでした。');

define('SUCCESS_DELETE_IMAGE', '枚の画像の削除に成功しました。');

define('SUCCESS_DELETE_IMAGE_DETAIL', 'を削除しました。');

// 画像コピー関連
define('FAIL_COPY_IMAGE', "画像のコピーに失敗しました。");

define('NOT_FOUND_COPY_DIRECTORY', "対象のディレクトリがありません。");

define('NOT_SELECT_IMAGE', "画像が選択されていません。");

define('ILLEGAL_IMAGE_NAME', "に不正なファイル名が入力されました。");

define('FAIL_COPYING_IMAGE', "コピー処理に失敗しました。");

define('SUCCESS_COPY_IMAGE', "のコピーに成功しました。");

define('FAIL_COPY_IMAGE_COUNT', 1);

define('IMAGE_NAME_CHAR_SIZE', 8);

define('IMAGE_COUNT_MAX', 50);
define('IMAGE_COUNT_OVER', -1);

define('IMAGE_COUNT_OVER_ERROR', (IMAGE_COUNT_MAX + 1)."枚以上の画像をアップロードすることはできません。処理を中断します。");
