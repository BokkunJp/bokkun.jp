<?php


$commonWordPath = dirname(
    dirname(__DIR__, 2)
);
$commonWordPath = AddPath($commonWordPath, 'common');
$commonWordPath = AddPath($commonWordPath, 'Word');
$commonWordPath = AddPath($commonWordPath, 'Message.php', false);
require_once $commonWordPath;

// 定数などの定義
define('ERROR_COMMON_DIR', __DIR__);
define('PUBLIC_DIR', AddPath(DOCUMENT_ROOT, 'public'));
define('FUNCTION_DIR', COMMON_DIR. '/Function');
define('LAYOUT_DIR', COMMON_DIR. '/Layout');

// エラーは配列
$ret = array();

$ret['headerTitle'] = $title;
switch ($errCode) {
    case '400':
        $ret['title'] = 'Bad Request';
        $ret['message'] = 'リクエストが不正です。';
        break;
    case '401':
        $ret['title'] = 'Unauthorized';
        $ret['message'] = '本サイトの閲覧には認証が必要です。';
        break;
    case '402':
        $ret['title'] = 'Payment Required';
        $ret['message'] = '本サイトの閲覧のための利用料をお支払いください。';
        break;
    case '403':
        $ret['title'] = 'Forbidden';
        $ret['message'] = 'アクセスが許可されていません。';
        break;
    case '404':
        $ret['title'] = 'NotFound';
        $ret['message'] = 'ページが見つかりません。';
        break;
    case '405':
        $ret['title'] = 'Method Not Allowed';
        $ret['message'] = '本ページでは、POSTメソッドは許可されておりません。';
        break;
    case '406':
        $ret['title'] = 'Not Acceptable';
        $ret['message'] = '受理できませんでした。文字コードなどの設定が誤っていないか、再度ご確認ください。';
        break;
    case '408':
        $ret['title'] = 'Request Timeout';
        $ret['message'] = 'タイムアウトしました。しばらく経ってから再度アクセスをお試しください。';
        break;
    case '410':
        $ret['title'] = 'Gone';
        $ret['message'] = '本サーバーには、該当ページは存在しません。';
        break;
    case '421':
        $ret['title'] = 'Misdirected Request';
        $ret['message'] = 'リクエストが不正です。';
        break;
    case '423':
        $ret['title'] = 'Locked';
        $ret['message'] = 'リソースがロックされています。';
        break;
    case '500':
        $ret['title'] = 'InternalServerError';
        $ret['message'] = 'サーバ内部でエラーが発生しました。管理者までご連絡お願いします。';
        break;
    case '502':
        $ret['title'] = 'Gateway Timeout';
        $ret['message'] = '不正なゲートウェイです。管理者までご連絡お願いします。';
        break;
    case '503':
        $ret['title'] = 'Service Unavailable';
        $ret['message'] = 'メンテナンス中により、本サイトはご利用いただけません。しばらくお待ちください。';
        break;
    case '507':
        $ret['title'] = 'Insufficient Storage';
        $ret['message'] = 'リクエストを処理できませんでした。管理者までご連絡お願いします。';
        break;
    default:
        $errCode = 'default';
        $ret['title'] = 'Other Error';
        $ret['message'] = 'エラーが発生しました。管理者までご連絡お願いします。';
        break;
}

$title = $ret;
