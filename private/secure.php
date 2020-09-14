<?php
require_once __DIR__. '/common/require.php';
if (empty($session)) {
    $session = new PrivateSetting\Session();
}

if (!$session->Judge('admin')) {
    $session->Write('admin', []);
}
if (!isset($adminURL) || empty($adminURL) && $session->Read('admin')['send'] !== true) {
    $adminURL = explode('/', PrivateSetting\GetSelf_Admin());
    $session->WriteArray('admin', 'adminURL', $adminURL);
} else {
    $adminURL = $session->Read('admin')['adminURL'];
}
$page = MovePage();
$refererArray = [$adminURL];
$referer = end($refererArray);
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf8" />
    <title>管理者認証</title>
    <script src="<?php echo $url; ?>/js/JavaScript/time/time.js"></script>
    <script src="<?php echo $url; ?>/js/JavaScript/time/realtime.js"></script>
    <script src="<?php echo $url; ?>/js/JavaScript/time/convert.js"></script>

</head>

<body>
    ご訪問ありがとうございます。<br />
    管理ページに進まれる場合はID・パスワードを入力してください。<br />
    詳細はサイト管理人にお問い合わせください(トップページのtwitter参照)。<br />
    <form method='POST' action='<?php echo $private; ?>secure.php'>
        <p>ID <input type='text' name='id' maxLength='20' /></p>
        <p>PASS <input type='password' name='pass' maxLength='20' /></p>
        <button type='submit'>送信</button>
    </form>
</body>

</html>
<?php

    if (!empty($post)) {
    $adminAuth = ($post['id'] === 'admin' && $post['pass'] === 'bokkunAdmin777');
    $guestAuth = ($post['id'] === 'guest' && $post['pass'] === 'guestPass1234');
} else {
    $adminAuth = $guestAuth = null;
}
if ((!($adminAuth) && !($guestAuth))) {
    if (!empty($post)) {
        echo '<p>IDまたはパスワードが違います。</p>';
        $session->Delete('admin');
    }

    // ログイン警告メール
    AlertAdmin('login', '');

    exit;
} else {
    if (!$session->Judge('id')) {
        $session->Write('id', $post['id']);
    }

    if (!$session->Judge('old_id')) {
        $session->Write('old_id', $session->Read('id'));
    }
    echo "<p>認証に成功しました。以下のリンクから$page[message]へ移動できます。<br/>";
    echo "<a href='$url/private/$page[URL]'>$page[message]へ</a></p>";
    $session->WriteArray('admin', 'secure', true);
    $session->Write('old_id', $session->Read('id'));
    $session->Write('id', str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'));

    AlertAdmin('login', 'login_success');
}
