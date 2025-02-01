<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'init.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'require.php';

use Private\Important\UseClass;
use Private\Important\Session;

$session = new Session('login');

$commonPath = new Path(PRIVATE_DIR);
$commonPath->setPathEnd();
$commonPath->add('common.php');
require_once $commonPath->get();
$adminUrl = explode('/', Private\Important\Setting::getUri());
$session->write('url', $adminUrl);

// ログイン成功時には自動遷移させる
$mode = 'movePage';

$tokenError = false;
// CSRFチェック
if (isset($post['login-token'])) {
    unset($post['login-token']);
    $privateLoginToken = new Private\Important\Token("login-token", $session);
    if (!$privateLoginToken->check()) {
        $tokenError = true;
        $session->write('token-Error', '<p>不正な遷移です。もう一度操作してください。</p>');
    }
}

if (!$tokenError && !empty($post) && !empty($post['login-id']) && !empty($post['password']) && isset(LOGIN_INFORMATION[$post['login-id']])) {
    $accounts = $session->read('login-account');
    if (!$accounts || !is_array($accounts) || !isset($accounts[$post['login-id']])) {
        $accountData = [
            'password' => password_hash(LOGIN_INFORMATION[$post['login-id']], PASSWORD_DEFAULT),
            'login-lock-timestamp' => null,
            'error-count' => 0,
            'login-auth' => false,
        ];
    } else {
        $accountData = $accounts[$post['login-id']];
    }

    $adminAuth = $accountData['login-auth'] = password_verify($post['password'], password_hash(LOGIN_INFORMATION[$post['login-id']], PASSWORD_DEFAULT));
    if ($accountData['login-auth'] === false) {
        $accountData['error-count']++;
    }

    // 5回ログインミスでロック設定してエラー内容の初期化
    if ($accountData['error-count'] >= LOGIN_LOCK_COUNT) {
        $accountData['login-lock-timestamp'] = new DateTime();
        $accountData['error-count'] = 0;
    } else {
        $nowDate = new DateTime();
        $lockDate = $accountData['login-lock-timestamp'];
        if ($lockDate instanceof DateTime) {
            $diffLockTime = ($nowDate->getTimestamp() - $lockDate->getTimestamp()) / HOUR_TO_MINUTE;
            if ($diffLockTime > LOGIN_UNLOCK_TIME) {
                // ロック後に規定時間経過したらロック解除
                $accountData['login-lock-timestamp'] = null;
            } else {
                // ロック解除前は認証成功しても遷移させない
                $adminAuth = false;
            }
        }
    }

    // ログインの履歴をセッションに保存
    $session->writeArray('login-account', $post['login-id'], $accountData);
} elseif (isset($post['login-id']) && !isset(LOGIN_INFORMATION[$post['login-id']])) {
    $adminAuth = false;
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

if (!$adminAuth) {
    // 入力値チェック
    if (isset($accountData) && !empty($accountData['login-lock-timestamp'])) {
        $session->write('password-Error', LOGIN_LOCK);
        alertAdmin('account_lock', $session->read('movePage'));
    } elseif ($adminAuth === false && ($tokenError === false && !empty($post))) {
        $session->write('password-Error', LOGIN_FAILURED);
        // ログイン警告メール (ログイン失敗時)
        alertAdmin('login', $session->read('movePage'));
    }
} elseif (isset($adminAuth)) {
    // アカウントロック情報の初期化
    $accountData['login-lock-timestamp'] = null;
    $accountData['error-count'] = 0;

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
        $session->write('password-Success', "<p>認証に成功しました。<a href={$urlData}>こちら</a>から移動できます。<br/></p>");
    }
}

require_once __DIR__ . '/Layout/layout.php';
exit;
