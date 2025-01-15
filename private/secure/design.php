<?php
$token = new Private\Important\Token('private-login-token', $session, true);
?>
管理ページに進まれる場合はID・パスワードを入力してください。<br />
詳細はサイト管理人にお問い合わせください。<br/>
<span class='login-notice'>※フッターにあるtwitterリンクからリプライができます。</span><br />

<form method='POST' action='<?=$base->getUri()?>'>
    <div class='notice'><?= $session->onlyView('token-Error'); ?></div>
    <p class='admin-login-id'>ID <input type='text' name='login-id' class='id' maxLength='20' /></p>
    <p class='admin-password'>PASSWORD <input type='password' name='password' class='pass' maxLength='20' /></p>
    <button type='submit' class='send'>送信</button>
    <div class='notice'><?= $session->onlyView('password-Error'); ?></div>
    <div class='success'><?= $session->onlyView('password-Success'); ?></div>
    <?php $token->set(); ?>
</form>
