<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__. '/component/require.php';
require_once dirname(__DIR__). '/File.php';

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
    if (isset($file['file']) && is_uploaded_file($file['file']['tmp_name'])) {
        $result = ImportImage($file);
        if ($result === true) {
            $session->Write('success', '画像のアップロードに成功しました。', 'Delete');
        } else if ($result === false) {
            $session->Write('notice', '画像のアップロードに失敗しました。', 'Delete');
        } else if ($result === -1) {
            $session->Write('notice', '画像ファイル以外はアップロードできません。', 'Delete');
        }
    } else {
        $session->Write('notice', 'ファイルが存在しません。', 'Delete');
    }
}
@session_regenerate_id();
$session->Add('token', sha1(session_id()));
// $session->FinaryDestroy();
$url = new PublicSetting\Setting();
header('Location:' . $url->GetUrl($str));
?>
<!-- <script>window.location.href = 'https://bokkun.jp/public/FILE/';</script> -->