<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/component/require.php';
require_once dirname(__DIR__) . '/File.php';
$files = PublicSetting\Setting::GetFiles();


// ページ数取得
$page = PublicSetting\Setting::GetQuery('page');
$str = 'public/FILE';
$str .= !empty($page) ? "?page={$page}" : "";

// セッション開始
if (!isset($session)) {
    $session = new PublicSetting\Session();
}

// tokenチェック
$checkToken = CheckToken('token', false);
// 不正tokenの場合は、エラーを出力して処理を中断。
if ($checkToken === false) {
    $session->Write('notice', '不正な遷移です。もう一度操作してください。', 'Delete');
    $url = new PublicSetting\Setting();
    header('Location:' . $url->GetUrl($str));
    exit;
}

if (!empty(PublicSetting\Setting::GetQuery('mode')) && PublicSetting\Setting::GetQuery('mode') === 'del') {
    $count = 0;
    foreach (PublicSetting\Setting::getPosts() as $post_key => $post_value) {
        $count++;
    }
    if ($count > COUNT_START) {
        DeleteImage();
    } else {
        $session->Write('notice', '削除対象が選択されていないか、画像がありません。', 'Delete');
    }
    if (!$session->Judge('notice')) {
        $session->Write('success', ($count - COUNT_START) . '件の画像の削除に成功しました。', 'Delete');
    }
} else {
    $result = ImportImage($files);

    // アップロードに成功したファイルがなかった場合
    if (empty($result['success'])) {
        if (empty($result)) {
            $session->Write('notice', FILE_NONE);
        } else if ($result == FILE_COUNT_OVER) {
            $session->Write('notice', FILE_COUNT_OVER_ERROR);
        } else {
            // 1枚以上アップロードに成功したファイルがあった場合
            // 一度も発生しなかった結果パターンを除外
            foreach ($result as $_key => $_filter) {
                if (empty($_filter['count'])) {
                    unset($result[$_key]);
                }
            }
        }
    } else {
        if (!empty($result['-1']['count'])) {
            define('FILE_ERR_CONST', "{$result['-1']['count']}枚のファイル");
            $session->Write('notice', FILE_ERR_CONST . FILE_NO_MATCH_FAIL);
        }

        if (!empty($result['fail']['count'])) {
            define('FILE_FAIL_CONST', "{$result['fail']['count']}枚のファイル");
            $session->Write('notice', FILE_FAIL_CONST . FILE_UPLOAD_FAIL);
        }
        if (!empty($result['success']['count'])) {
            define('FILE_SUCCESS_CONST', "{$result['success']['count']}枚のファイル");
            $session->Write('success', FILE_SUCCESS_CONST . FILE_UPLOAD_SUCCESS);
        }
    }
}
@session_regenerate_id();
$session->Add('token', sha1(session_id()));
// $session->FinaryDestroy();
$url = new PublicSetting\Setting();
header('Location:' . $url->GetUrl($str));
?>
<!-- <script>window.location.href = 'https://bokkun.jp/public/FILE/';</script> -->