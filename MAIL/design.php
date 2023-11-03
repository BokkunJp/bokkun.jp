<?php
// セッションセット
$session = new public\Session();
// Tokenクラスをセット
$publicMailToken = new Public\Token('public-mail-token', $session, true);

//sendMail(['bokkun.moeanimecharacter.byt@gmail.com', 'テスト送信', 'ウェザサイトからの送信です。', 'From: private.mail@bokkun.jp' . "\r\n"]);
?>
<form enctype="multipart/form-data" action="./" method='POST'>
    <?php $publicMailToken->Set(); ?>
    <input type='textbox' name='title' />
    <br />
    <textarea name='body'></textarea>
    <button type='send' name='send' value=1>送信する</button>
</form>