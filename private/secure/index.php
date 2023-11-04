<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'require.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'init.php';

use Private\Important\UseClass;

if (empty($session)) {
    $session = new Private\Important\Session();
}

if (!$session->judge('admin')) {
    $session->write('admin', []);
}
if (!isset($adminURL) || empty($adminURL) && $session->read('admin')['send'] !== true) {
    $commonPath = new Path(PRIVATE_DIR);
    $commonPath->setPathEnd();
    $commonPath->add('common.php');
    require_once $commonPath->get();
    $adminURL = explode('/', Private\Important\Setting::getURI());
    $session->writeArray('admin', 'adminURL', $adminURL);
} else {
    $adminURL = $session->read('admin')['adminURL'];
}

// ログイン成功時には自動遷移させる
$mode = 'movePage';

$tokenError = false;
// CSRFチェック
if (isset($post['private-login-token'])) {
    unset($post['private-login-token']);
    $privateLoginToken = new Private\Important\Token("private-login-token", $session);
    if (!$privateLoginToken->check()) {
        $tokenError = true;
        $session->write('token-Error', '<p>不正な遷移です。もう一度操作してください。</p>');
    }
}

if (!$tokenError && !empty($post) && !empty($post['id']) && !empty($post['pass'])) {
    $adminAuth = ($post['id'] === 'admin' && password_verify($post['pass'], password_hash(LOGIN_PASSWORD, PASSWORD_DEFAULT)));
} else {
    $adminAuth = null;
}

// アクセスしてきたページを保存
$adminSession = $session->read("admin");
$moveURL = $adminSession['adminURL'];

if ($moveURL[2] === 'secure' || $moveURL[2] === 'logout') {
    unset($moveURL[2]);
}

$movePage = implode('/', $moveURL);

$session->writeArray('admin', 'movePage', $url . $movePage);

if (!isset($adminSession['movePage'])) {
    $adminSession['movePage'] = $session->read('admin')['movePage'];
}

if ((!($adminAuth))) {
    // 入力値チェック
    if ($tokenError === false && !empty($post)) {
        $session->write('password-Error', '<p>IDまたはパスワードが違います。</p>');
        // ログイン警告メール (ログイン失敗時)
        alertAdmin('login', $adminSession['movePage']);
    }
} else {
    // ログイン警告メール (ログイン成功時)
    alertAdmin('login_success', '');

    // script読み込み
    print_r("<script src='{$url}/private/client/js/common/jquery-3.1.1.min.js'></script>
    <script src='{$url}/private/client/js/secure.js'></script>");

    if (!$session->judge('old_id')) {
        $session->write('old_id', $session->read('id'));
    }

    // セッション書き込み
    $session->writeArray('admin', 'secure', true);
    $session->write('old_id', $session->read('id'));
    $session->write('id', str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'));

    // 直接遷移
    if ($mode === 'movePage') {
        // ページ遷移
        $script = new UseClass();
        $script->alert("認証に成功しました。自動で遷移します。");
        $script->movePage($adminSession['movePage']);

    // リンクから遷移
    } else {
        $session->write('password-Success', "<p>認証に成功しました。<a href={$adminSession['movePage']}>リンク</a>から移動できます。<br/></p>");
    }
}

require_once __DIR__ . '/Layout/layout.php';
die;
