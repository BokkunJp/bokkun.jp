<?
if (!isset($_SESSION)) {
    session_start();
}
?>
<script>
    history.replaceState('', '', '/public/FILE/');
</script>
<?php
$homepageTitle = htmlspecialchars(basename(__DIR__));

require_once __DIR__. '/Layout/layout.php';
require_once PUBLIC_COMMON_DIR. '/Token.php';
require_once dirname(__DIR__). '/File.php';

echo '<div class=\'contents\' />';

CheckToken('token', '不正な値が送信されました。<br/>', false);

if (!isset($session)) {
    $session = new PublicSetting\Session();
}

if (!empty(PublicSetting\Setting::GetQuery('mode')) && PublicSetting\Setting::GetQuery('mode') === 'del') {
    $count = 0;
    foreach (PublicSetting\Setting::getPosts() as $post_key => $post_value) {
        $count++;
    }
    if ($count > COUNT_START) {
        DeleteImage();
    } else {
        $session->Delete();
        $session->Add('notice', '削除対象が選択されていないか、画像がありません。');
    }
    if (!$session->Judge('notice')) {
        $session->Add('success', ($count - COUNT_START). '件の画像の削除に成功しました。');
    }

} else {
    if (isset($file['file']) && is_uploaded_file($file['file']['tmp_name'])) {
        $result = ImportImage($file);
        if ($result === true) {
            $session->Add('success', '画像のアップロードに成功しました。');
        } else if ($result === false) {
            $session->Add('notice', '画像のアップロードに失敗しました。');
        } else if ($result === -1) {
            $session->Add('notice', '画像ファイル以外はアップロードできません。');
        }
    } else {
        $session->Add('notice', 'ファイルが存在しません。');
    }
}
@session_regenerate_id();
$session->Add('token', sha1(session_id()));
// $session->FinaryDestroy();
$url = new PublicSetting\Setting();
header('Location:' . $url->GetUrl('public/FILE'));
?>
