<?php
$commonWordPath = new \Path(dirname(__DIR__, 3));
$commonWordPath->addArray(['common', 'Word', 'Message.php']);
require_once $commonWordPath->get();

// 定数などの定義
define('ERROR_COMMON_DIR', __DIR__);
$publicPath = new \Path(DOCUMENT_ROOT);
$publicPath->add('public');
define('PUBLIC_DIR', $publicPath->get());
define('FUNCTION_DIR', COMMON_DIR. '/Function');
define('LAYOUT_DIR', COMMON_DIR. '/layout');

// エラー配列
$ret = array();

$ret['headerTitle'] = $title;
switch ($errCode) {
    case '400':
        $ret['title'] = 'Bad Request';
        $ret['message'] = 'リクエストが不正です。';
        break;
    case '401':
        $ret['title'] = 'Unauthorized';
        $ret['message'] = '当サイトの閲覧には認証が必要です。';
        break;
    case '402':
        $ret['title'] = 'Payment Required';
        $ret['message'] = '当サイトを閲覧するための利用料をお支払いください。';
        break;
    case '403':
        $ret['title'] = 'Forbidden';
        $ret['message'] = 'アクセスが許可されていません。他のページをご参照ください。';
        break;
    case '404':
        $ret['title'] = 'Not Found';
        $ret['message'] = 'ページが見つかりません。URLが間違っていないかご確認ください。';
        break;
    case '405':
        $ret['title'] = 'Method Not Allowed';
        $ret['message'] = 'POSTメソッドは許可されておりません。';
        break;
    case '406':
        $ret['title'] = 'Not Acceptable';
        $ret['message'] = 'リクエストを受理できませんでした。文字コードなどの設定が誤っていないか、再度ご確認ください。';
        break;
    case '408':
        $ret['title'] = 'Request Timeout';
        $ret['message'] = 'タイムアウトしました。しばらく経ってから再度アクセスをお試しください。';
        break;
    case '409':
        $ret['title'] = 'Confrict';
        $ret['message'] = 'リクエストを完了できませんでした。URLをご確認いただくか、ブラウザのキャッシュクリアをお試しください。';
        break;
        case '410':
        $ret['title'] = 'Gone';
        $ret['message'] = '該当ページは削除されており存在しません。他のページをご確認ください。';
        break;
    case '418':
        $ret['title'] = 'I\'m a teapot';
        $ret['message'] = '私はティーポッド🫖';
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
    case '501':
        $ret['title'] = 'Not Implemented';
        $ret['message'] = '必要な機能をサポートしておりません。管理者までご連絡お願いします。';
        break;
    case '502':
        $ret['title'] = 'Bad Gateway';
        $ret['message'] = '不正なゲートウェイです。管理者までご連絡お願いします。';
        break;
    case '503':
        $ret['title'] = 'Service Unavailable';
        $ret['message'] = 'メンテナンス中により、当サイトはご利用いただけません。しばらくお待ちください。';
        break;
    case '504':
        $ret['title'] = 'Gateway Timeout';
        $ret['message'] = 'ゲートウェイがタイムアウトしました。管理者までご連絡お願いします。';
        break;
    case '505':
        $ret['title'] = 'HTTP Version Not Supported';
        $ret['message'] = 'サポートされていないHTTPバージョンです。管理者までご連絡お願いします。';
        break;
    case '506':
        $ret['title'] = 'Variant Also Negotiates';
        $ret['message'] = '適切なネゴシエーションエンドポイントではありません。管理者までご連絡お願いします。';
        break;
    case '507':
        $ret['title'] = 'Insufficient Storage';
        $ret['message'] = 'リクエストを処理するための容量が足りません。管理者までご連絡お願いします。';
        break;
    case '508':
        $ret['title'] = 'Loop Detected';
        $ret['message'] = 'リクエストを処理できませんでした。管理者までご連絡お願いします。';
        break;
    case '510':
        $ret['title'] = 'Not Extended';
        $ret['message'] = 'リクエストを処理できませんでした。管理者までご連絡お願いします。';
        break;
    case '511':
        $ret['title'] = 'Network Authentication Required';
        $ret['message'] = '本ページアクセスするには、ネットワーク認証が必要です。管理者までご連絡お願いします。';
        break;
    default:
        $errCode = 'default';
        $ret['title'] = 'Other Error';
        $ret['message'] = 'エラーが発生しました。管理者までご連絡お願いします。';
        break;
}

$title = $ret;