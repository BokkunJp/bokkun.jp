<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'init.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'require.php';

use Private\Important\UseClass;

$session = new Private\Important\Session('login');

if (!isset($adminUrl) || empty($adminUrl) && $session->read('send') !== true) {
    $commonPath = new Path(PRIVATE_DIR);
    $commonPath->setPathEnd();
    $commonPath->add('common.php');
    require_once $commonPath->get();
    $adminUrl = explode('/', Private\Important\Setting::getUri());
    $session->write('url', $adminUrl);
} else {
    $adminUrl = $session->read('url');
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
$movePage = $session->read("url");

if (is_array($movePage)) {
    if ($movePage[2] === 'secure' || $movePage[2] === 'logout' || $movePage[2] === 'logout-with-session-reset') {
        unset($movePage[2]);
    }
    
    $movePage = implode('/', $movePage);
} else {
    $movePage = '';
}
$urlData = $url . $movePage;

$session->write('url', $urlData);

if ((!($adminAuth))) {
    // 入力値チェック
    if ($tokenError === false && !empty($post)) {
        $session->write('password-Error', '<p>IDまたはパスワードが違います。</p>');
        // ログイン警告メール (ログイン失敗時)
        alertAdmin('login', $session->read('movePage'));
    }
} else {
    // ログイン警告メール (ログイン成功時)
    alertAdmin('login_success', '');

    // script読み込み
    print_r("<script src='//code.jquery.com/jquery-3.7.1.min.js'></script>
    <script src='{$url}/private/client/js/secure.js'></script>");

    // セッション書き込み
    $session->write('secure', true);

    // 直接遷移
    if ($mode === 'movePage') {
        // ページ遷移
        $script = new UseClass();
        $script->alert("認証に成功しました。自動で遷移します。");
        $script->movePage($urlData);

    // リンクから遷移
    } else {
        $session->write('password-Success', "<p>認証に成功しました。<a href={$adminSession['movePage']}>リンク</a>から移動できます。<br/></p>");
    }
}

require_once __DIR__ . '/Layout/layout.php';
die;
