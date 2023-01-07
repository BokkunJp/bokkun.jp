<?php

require_once __DIR__ . '/Layout/require.php';
require_once __DIR__ . '/Layout/init.php';

use PrivateTag\UseClass;

if (empty($session)) {
    $session = new PrivateSetting\Session();
}

if (!$session->Judge('admin')) {
    $session->Write('admin', []);
}
if (!isset($adminURL) || empty($adminURL) && $session->Read('admin')['send'] !== true) {
    require_once AddPath(PRIVATE_DIR, 'common.php', false);
    $adminURL = explode('/', PrivateSetting\Setting::getURI());
    $session->WriteArray('admin', 'adminURL', $adminURL);
} else {
    $adminURL = $session->Read('admin')['adminURL'];
}

// ログイン成功時には自動遷移させる
$mode = 'movePage';

$tokenError = false;
// CSRFチェック
if (isset($post['private-login-token'])) {
    unset($post['private-login-token']);
    if (!CheckToken('private-login-token')) {
        $tokenError = true;
        $session->Write('token-Error', '<p>不正な遷移です。もう一度操作してください。</p>');
    }
}

if (!$tokenError && !empty($post) && !empty($post['id']) && !empty($post['pass'])) {
    $adminAuth = ($post['id'] === 'admin' && password_verify($post['pass'], password_hash(LOGIN_PASSWORD, PASSWORD_DEFAULT)));
} else {
    $adminAuth = null;
}

// アクセスしてきたページを保存
$adminSession = $session->Read("admin");
$moveURL = $adminSession['adminURL'];

if ($moveURL[2] === 'secure' || $moveURL[2] === 'logout') {
    unset($moveURL[2]);
}

$movePage = implode('/', $moveURL);

$session->WriteArray('admin', 'movePage', $url . $movePage);

if (!isset($adminSession['movePage'])) {
    $adminSession['movePage'] = $session->Read('admin')['movePage'];
}

if ((!($adminAuth))) {
    // 入力値チェック
    if ($tokenError === false && !empty($post)) {
        $session->Write('password-Error', '<p>IDまたはパスワードが違います。</p>');
        // ログイン警告メール (ログイン失敗時)
        AlertAdmin('login', $adminSession['movePage']);
    }
} else {
    // ログイン警告メール (ログイン成功時)
    AlertAdmin('login_success', '');

    // script読み込み
    print_r("<script src='{$url}/private/client/js/common/jquery-3.1.1.min.js'></script>
    <script src='{$url}/private/client/js/secure.js'></script>");

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
        $script->Alert("認証に成功しました。自動で遷移します。");
        $script->MovePage($adminSession['movePage']);

    // リンクから遷移
    } else {
        $session->Write('password-Success', "<p>認証に成功しました。<a href={$adminSession['movePage']}>リンク</a>から移動できます。<br/></p>");
    }
}

require_once __DIR__ . '/Layout/layout.php';
die;
