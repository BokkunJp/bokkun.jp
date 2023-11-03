<?php
// $privateDirPath = new \Path(dirname(__DIR__));

$commonPath = new \Path(dirname(__DIR__, 3));

$commonWordPath = new \Path($commonPath->get());
$commonWordPath->addArray(["common", "Word", "Message.php"]);
require_once $commonWordPath->get();

// CSRFクラス
function PrivateCSRFErrorMessage()
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
// define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('PRIVATE_DIR', dirname(__DIR__, 2));
define('PRIVATE_COMMON_DIR', dirname(__DIR__));

// パス
// 初期設定部分
$privateCleintDirWord = new Path(PRIVATE_DIR);
$privateCleintDirWord->add('client');
define('PRIVATE_CLIENT_DIR', $privateCleintDirWord->get());

$privateMessage = new PathApplication('private_dir', dirname(DOCUMENT_ROOT));
$privateMessage->setAll(
    [
        'private_css_dir' => PRIVATE_CLIENT_DIR,
        'private_js_dir' => '',
        'private_image_dir' => '',
        'priavate_component_dir' => PRIVATE_COMMON_DIR,
        'priavate_layout_dir' => '',
    ],
);

// 追加
$privateMessage->resetKey('private_css_dir');
$privateMessage->methodPath('AddArray', ['css']);
define('PRIVATE_CSS_DIR', $privateMessage->get());

$privateMessage->resetKey('private_js_dir');
$privateMessage->methodPath('AddArray', ['js']);
define('PRIVATE_JS_DIR', $privateMessage->get());

$privateMessage->resetKey('private_image_dir');
$privateMessage->methodPath('AddArray', ['image']);
define('PRIVATE_IMAGE_DIR', $privateMessage->get());

$privateMessage->resetKey('priavate_component_dir');
$privateMessage->methodPath('AddArray', ['Component']);
define('PRIVATE_COMPONENT_DIR', $privateMessage->get());


$privateMessage->resetKey('priavate_layout_dir');
$privateMessage->methodPath('AddArray', ['layout']);
define('PRIVATE_LAYOUT_DIR', $privateMessage->get());

define('PRIVATE_PREVIOUS', '画像管理ページへ戻る');

// 管理側の追加ソース
define('ADD_DESIGN', 'require_once __DIR__ . DIRECTORY_SEPARATOR . "design.php";');

// 公開側画像パス
$publicImageWord = new Path(DOCUMENT_ROOT);
$publicImageWord->addArray(['public', 'client', 'image']);
define('PUBLIC_IMAGE_DIR', $publicImageWord->get());

// デフォルトの画像ページ
define('DEFAULT_IMAGE', 'IMAGE');

// 削除不可リスト
define('NOT_DELETE_FILE_LIST', ['MAIL', 'IMAGE', 'CSV', 'QR', 'liDAR-WebGL', 'webGL']);

// ログインパスワード
define("LOGIN_PASSWORD", "bokkunAdmin777");

// 画像アップロード・削除・コピー共通
define('NUMBER_OF_FILE', '枚のファイル');
// 画像アップロード用の文言 (ファイル数の部分は処理時に定義)
define('FAIL_UPLOAD_IMAGE', "のアップロードに失敗しました。");

define('NOT_MATCH_IMAGE', "は、画像ファイルではないためアップロードできませんでした。");

define('ILLEGAL_UPLOAD_IMAGE', "は、不正な方法でのアップロードのため、アップロードを取り消しました。");

define('EMPTY_IMAGE_SIZE', "は、画像のファイルサイズが0バイトのためアップロードできませんでした。");

define('SUCCESS_UPLOAD_IMAGE', "のアップロードに成功しました。");

// 画像削除・コピー用の文言
// 画像削除関連
define('NOT_FOUND_DLETE_IMAGE', '削除対象が選択されていないか、現在の枚数表示では、そのページには画像はありません。');

define('FAIL_DELETE_IMAGE', '件の画像の削除に失敗しました。');

define('FAIL_REASON_SYSTEM', '・処理中に問題が発生したため、');

define('FAIL_DELETE_IMAGE_DETAIL', 'を削除できませんでした。');

define('SUCCESS_DELETE_IMAGE', '枚の画像の削除に成功しました。');

define('SUCCESS_DELETE_IMAGE_DETAIL', 'を削除しました。');

define('NOT_FOUND_DIRECTORY', "対象のページがありません。");

// 画像コピー関連
define('FAIL_COPY_IMAGE', "画像のコピーに失敗しました。");

define('NOT_SELECT_IMAGE', "画像が選択されていません。");

define('ILLEGAL_IMAGE_NAME', "に不正なファイル名が入力されました。");

define('FAIL_COPYING_IMAGE', "コピー処理に失敗しました。");

define('SUCCESS_COPY_IMAGE', "のコピーに成功しました。");

// 画像復元・完全削除用の文言
// 画像完全削除関連
define('NOT_FOUND_PERMANENT_DLETE_OR_RESTORE_IMAGE', '対象が選択されていないか、現在の枚数表示では、そのページには画像はありません。');

define('FAIL_PERMANENT_DELETE_IMAGE', '件の画像の削除に失敗しました。');

define('FAIL_PERMANENT_REASON_SYSTEM', '・処理中に問題が発生したため、');

define('FAIL_PERMANENT_DELETE_IMAGE_DETAIL', 'を削除できませんでした。');

define('SUCCESS_PERMANENT_DELETE_IMAGE', '枚の画像の削除に成功しました。');

define('SUCCESS_PERMANENT_DELETE_IMAGE_DETAIL', 'を削除しました。');

// 画像復元関連
define('FAIL_RESTORE', "画像の復元に失敗しました。");

define('ILLEGAL_RESTORE_IMAGE_NAME', "に不正なファイル名が入力されました。");

define('FAIL_RESTORE_IMAGE', "の復元に失敗しました。");

define('SUCCESS_RESTORE_IMAGE', "の復元に成功しました。");

define('FAIL_COPY_IMAGE_COUNT', 1);

define('FAIL_RESTORE_IMAGE_COUNT', 1);

define('IMAGE_NAME_CHAR_SIZE', 8);

define('IMAGE_COUNT_MAX', 50);
define('IMAGE_COUNT_OVER', -1);

define('IMAGE_COUNT_OVER_ERROR', (IMAGE_COUNT_MAX + 1)."枚以上の画像をアップロードすることはできません。処理を中断します。");
