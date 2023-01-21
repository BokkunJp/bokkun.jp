<?php
$token = new Private\Token('private-login-token', $session, true);
?>
管理ページに進まれる場合はID・パスワードを入力してください。<br />
詳細はサイト管理人にお問い合わせください。<br/>
<span class='login-notice'>※フッターにあるtwitterリンクからリプライができます。</span><br />

<link rel="stylesheet" type="text/css" href="<?= $base->GetUrl('', 'client') ?>css/design.css">
<form method='POST' action='<?=$base->GetURI()?>'>
    <div class='notice'><?= $session->OnlyView('token-Error'); ?></div>
    <p>ID <input type='text' name='id' class='id' maxLength='20' /></p>
    <p>PASS <input type='password' name='pass' class='pass' maxLength='20' /></p>
    <button type='submit' class='send'>送信</button>
    <div class='notice'><?= $session->OnlyView('password-Error'); ?></div>
    <div class='warning'><?= $session->OnlyView('password-Success'); ?></div>
    <?php $token->UpdateToken(); ?>
</form>
