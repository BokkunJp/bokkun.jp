<?php
require_once 'Model.php';
require_once PUBLIC_COMMON_DIR . '/Token.php';


$posts = PublicSetting\Setting::getPosts();

if (!isset($posts) || empty($posts)) {
    return -1;
}

CheckToken('token', '不正な値が送信されました。<br/>');
$session = $_SESSION;
if ($session['mail']['send_flg']) {
    echo '';
    CheckToken('closed
    ', '本日はもうメール送信できません。<br/>');

} 

$session['mail']['send_flg'] = true;
$_SESSION = $session;

SendMail(['private.mail@bokkun.jp', $posts['title'], $posts['body'], 'From: from.mail@bokkun.jp' . "\r\n"]);

 echo '<script>メールを送信しました</script>';