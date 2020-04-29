<?php
require_once 'Model.php';
require_once PUBLIC_COMMON_DIR . '/Token.php';


$posts = PublicSetting\Setting::getPosts();

if (!isset($posts) || empty($posts)) {
    return -1;
}

CheckToken('token', true, '不正な値が送信されました。<br/>');
$session = $_SESSION;

if (isset($session['mail']['send_flg']) && $session['mail']['send_flg'] === true) {
    echo '';
    CheckToken('closed
    ', true, '本日はもうメール送信できません。<br/>');
}

$session['mail']['send_flg'] = true;
$_SESSION = $session;

SendMail(['private.mail@bokkun.jp', $posts['title'], $posts['body'], 'ぼっくん', 'from.mail@bokkun.jp']);

 echo '<script>alert("メールを送信しました。");</script>';