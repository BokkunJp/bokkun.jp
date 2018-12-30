<script>
    history.replaceState('', '', '/public/FILE/');
</script>
<?php
$homepageTitle = htmlspecialchars(basename(__DIR__));
require_once __DIR__. '/Layout/layout.php';
require_once COMMON_DIR. '/Token.php';
require_once dirname(__DIR__). '/File.php';

echo '<div class=\'contents\' />';

CheckToken('token', '不正な値が送信されました。<br/>');

$session = new PublicSetting\Session();
$posts = PublicSetting\Setting::getPosts();
$querys = PublicSetting\Setting::GetRequest();
var_dump($posts['mode']);die;
if (isset($posts['mode'])) {
    $mode = $posts['mode'];
} else {
    $mode = '';
}
if ($mode === 'delete') {
    $count = 0;
    foreach (PublicSetting\Setting::getPosts() as $post_key => $post_value) {
        if (count($post_key)) {
            $count++;
        }
    }
    if ($count > COUNT_START) {
        DeleteImage();
    } else {
        echo '削除対象が選択されていないか、画像がありません。<br/>';
    }
} else if ($mode === 'restore') {
    var_dump(PublicSetting\Setting::getPosts());
//    RestoreImage($fileNmae);
} else {
    if (isset($file['file']) && is_uploaded_file($file['file']['tmp_name'])) {
        ImportImage($file);
    } else {
        echo 'ファイルが存在しません。<br/>';
    }
}

session_regenerate_id();
$session->Add('token', sha1(session_id()));
// $session->FinaryDestroy();
?>

<br />
<a href='javascript:location.href = location;'>ファイル選択ページへ戻る</a>
</div>
        <div>
            <?php require_once __DIR__. '/Layout/footer.php'; ?>
        </div>
    </body>
</html>
