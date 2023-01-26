<?php
// セッションセット
$session = new PublicSetting\Session();
// Tokenクラスをセット
$publicMailToken = new Public\Token('public-mail-token', $session, true);

//SendMail(['bokkun.moeanimecharacter.byt@gmail.com', 'テスト送信', 'ウェザサイトからの送信です。', 'From: private.mail@bokkun.jp' . "\r\n"]);
?>
<form enctype="multipart/form-data" action="./" method='POST'>
    <?php $publicMailToken->SetToken(); ?>
    <input type='textbox' name='title' />
    <br />
    <textarea name='body'></textarea>
    <button type='send' name='send'>送信する</button>
</form>