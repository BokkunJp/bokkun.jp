<?php

use PrivateTag\UseClass;

require_once __DIR__ . '/common/require.php';
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
$mode = 'movePage';
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf8" />
    <title>管理者認証</title>

</head>

<body>
    ご訪問ありがとうございます。<br />
    管理ページに進まれる場合はID・パスワードを入力してください。<br />
    詳細はサイト管理人にお問い合わせください(トップページのtwitter参照)。<br />
    <form method='POST' action='<?php echo $private; ?>secure.php'>
        <p>ID <input type='text' name='id' class='id' maxLength='20' /></p>
        <p>PASS <input type='password' name='pass' class='pass' maxLength='20' /></p>
        <button type='submit' class='send'>送信</button>
    </form>
</body>

</html>
<?php
// 初期遷移か判定
if (!empty($post)) {
    $adminAuth = ($post['id'] === 'admin' && $post['pass'] === 'bokkunAdmin777');
    $guestAuth = ($post['id'] === 'guest' && $post['pass'] === 'guestPass1234');
} else {
    $adminAuth = $guestAuth = null;
}

// アクセスしてきたページを保存
$adminSession = $session->Read("admin");
$moveURL = $adminSession['adminURL'];

// 特定のページの際の処理
if (isset($moveURL[3])) {
    unset($moveURL[3]);
}

if ($moveURL[2] === 'secure.php' || $moveURL[2] === 'reset.php') {
    $moveURL[2] = 'admin.php';
}

$movePage = implode('/', $moveURL);

$session->WriteArray('admin', 'movePage', $url . $movePage);

if ((!($adminAuth) && !($guestAuth))) {
    if (!empty($post)) {
        echo '<p>IDまたはパスワードが違います。</p>';
        // ログイン警告メール (ログイン失敗時)
        AlertAdmin('login', $adminSession['movePage']);
    }

    exit;
} else {
    // ログイン警告メール (ログイン成功時)
    AlertAdmin('login_success', '');

    // script読み込み
    print_r("<script src='{$url}/private/client/js/common/jquery-3.1.1.min.js'></script>
    <script src='{$url}/private/client/js/secure.js'></script>");

    if (!$session->Judge('id')) {
        $session->Write('id', $post['id']);
    }

    if (!$session->Judge('old_id')) {
        $session->Write('old_id', $session->Read('id'));
    }

    // セッション書き込み
    $session->WriteArray('admin', 'secure', true);
    $session->Write('old_id', $session->Read('id'));
    $session->Write('id', str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'));

    // 直接遷移
    if ($mode === 'movePage') {
        // ページ遷移
        $script = new UseClass();
        $script->Alert('認証に成功しました。自動で遷移します。');
        $script->MovePage($adminSession['movePage']);

        // リンクから遷移
    } else {
        echo "<p>認証に成功しました。<a href={$adminSession['movePage']}>リンク</a>から移動できます。<br/>
        </p>";
    }
}
