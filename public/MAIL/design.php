<?php
//SendMail(['bokkun.moeanimecharacter.byt@gmail.com', 'テスト送信', 'ウェザサイトからの送信です。', 'From: private.mail@bokkun.jp' . "\r\n"]);
?>
<form enctype="multipart/form-data" action=".//" method='POST'>
    <input type='hidden' name='token' value="<?= $token = MakeToken() ?>" />
    <input type='textbox' name='title' />
    <br />
    <textarea name='body'></textarea>
    <button type='send' name='send'>送信する</button>
</form>
<?php
SetToken($token);
