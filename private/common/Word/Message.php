<?php

// 公開側・管理側共通の文言をセット
$commonWordPath = new \Path(dirname(__DIR__, 3));
$commonWordPath->addArray(["common", "Word", "Message.php"]);
require_once $commonWordPath->get();

// 公開側の文言をセット
$publicCommonWordPath = new \Path(dirname(__DIR__, 3));
$publicCommonWordPath->addArray(["public", "common", "Word", "Message.php"]);
require_once $publicCommonWordPath->get();

// 共通部分
// const DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT']);
define('PRIVATE_DIR', dirname(__DIR__, 2));
define('PRIVATE_COMMON_DIR', dirname(__DIR__));

// パス
// 初期設定部分
$privateCleintDirWord = new \Path(PRIVATE_DIR);
$privateCleintDirWord->add('client');
define('PRIVATE_CLIENT_DIR', $privateCleintDirWord->get());

// 各ディレクトリパスの定義
$privateMessage = new \PathApplication('private_dir', dirname(DOCUMENT_ROOT));
$privateDirPathList = [
    'css' => PRIVATE_CLIENT_DIR,
    'js' => '',
    'image' => '',
    'Component' => PRIVATE_COMMON_DIR,
    'layout' => '',
];
$privateMessage->setAll($privateDirPathList);

$privateDirList = [];

foreach ($privateDirPathList as $_key => $_value) {
    $privateMessage->setKey($_key);
    $privateMessage->methodPath('addArray', [$_key]);
    $privateDirList[$_key] = $privateMessage->get();
}

// まとめて配列で定義
define("PRIVATE_DIR_LIST", $privateDirList);

// ここから文言の定義
// 画像投稿ページのバックのリンク
const PRIVATE_PREVIOUS = '画像管理ページへ戻る';

// 管理側の追加ソース
const ADD_DESIGN = 'require_once __DIR__ . DIRECTORY_SEPARATOR . "design.php";';

// デフォルトの画像ページ
const DEFAULT_IMAGE = 'image';

// 削除不可リスト
const NOT_DELETE_FILE_LIST = ['mail', 'image', 'csv', 'php-qrcode', 'liDAR-WebGL', 'webGL'];

// ログイン情報
define("LOGIN_INFORMATION", ["admin" => "bokkunAdmin777", "admin2" => "bokkunAdmin1234"]);

// パスワードミス
define("LOGIN_FAILURED", "<p>IDまたはパスワードが違います。</p>");

// アカウントロックまでの回数
define("LOGIN_LOCK_COUNT", 5);
// ロック解除までの時間
define("LOGIN_UNLOCK_TIME", 30);
// 1時間
define("HOUR_TO_MINUTE", 60);
// アカウントロックの文言
define("LOGIN_LOCK", "<p>アカウントがロックされています。しばらく経ってからやり直してください。</p>");

// 画像アップロード・削除・コピー共通
const NUMBER_OF_FILE = '枚のファイル';
// 画像アップロード用の文言 (ファイル数の部分は処理時に定義)
const FAIL_UPLOAD_IMAGE = "のアップロードに失敗しました。";

const NOT_MATCH_IMAGE = "は、画像ファイルではないためアップロードできませんでした。";

const ILLEGAL_UPLOAD_IMAGE = "は、不正な方法でのアップロードのため、アップロードを取り消しました。";

const EMPTY_IMAGE_SIZE = "は、画像のファイルサイズが0バイトのためアップロードできませんでした。";

const SUCCESS_UPLOAD_IMAGE = "のアップロードに成功しました。";

// 画像削除・コピー用の文言
// 画像削除関連
const NOT_FOUND_DLETE_IMAGE ='削除対象が選択されていないか、現在の枚数表示では、そのページには画像はありません。';

const FAIL_DELETE_IMAGE ='件の画像の削除に失敗しました。';

const FAIL_REASON_SYSTEM ='・処理中に問題が発生したため、';

const FAIL_DELETE_IMAGE_DETAIL ='を削除できませんでした。';

const SUCCESS_DELETE_IMAGE ='枚の画像の削除に成功しました。';

const SUCCESS_DELETE_IMAGE_DETAIL ='を削除しました。';

const NOT_FOUND_DIRECTORY = "対象のページがありません。";

// 画像コピー関連
const FAIL_COPY_IMAGE = "画像のコピーに失敗しました。";

const NOT_SELECT_IMAGE = "画像が選択されていません。";

const ILLEGAL_IMAGE_NAME = "に不正なファイル名が入力されました。";

const FAIL_COPYING_IMAGE = "コピー処理に失敗しました。";

const SUCCESS_COPY_IMAGE = "のコピーに成功しました。";

// 画像復元・完全削除用の文言
// 画像完全削除関連
const NOT_FOUND_PERMANENT_DLETE_OR_RESTORE_IMAGE ='対象が選択されていないか、現在の枚数表示では、そのページには画像はありません。';

const FAIL_PERMANENT_DELETE_IMAGE ='件の画像の削除に失敗しました。';

const FAIL_PERMANENT_REASON_SYSTEM ='・処理中に問題が発生したため、';

const FAIL_PERMANENT_DELETE_IMAGE_DETAIL ='を削除できませんでした。';

const SUCCESS_PERMANENT_DELETE_IMAGE ='枚の画像の削除に成功しました。';

const SUCCESS_PERMANENT_DELETE_IMAGE_DETAIL ='を削除しました。';

// 画像復元関連
const FAIL_RESTORE = "画像の復元に失敗しました。";

const ILLEGAL_RESTORE_IMAGE_NAME = "に不正なファイル名が入力されました。";

const FAIL_RESTORE_IMAGE = "の復元に失敗しました。";

const SUCCESS_RESTORE_IMAGE = "の復元に成功しました。";

const FAIL_COPY_IMAGE_COUNT = 1;

const FAIL_RESTORE_IMAGE_COUNT = 1;

const IMAGE_NAME_CHAR_SIZE = 8;

const IMAGE_COUNT_MAX = 50;
const IMAGE_COUNT_OVER = -1;

const IMAGE_COUNT_OVER_ERROR = (IMAGE_COUNT_MAX + 1)."枚以上の画像をアップロードすることはできません。処理を中断します。";
