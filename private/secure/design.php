ご訪問ありがとうございます。<br />
管理ページに進まれる場合はID・パスワードを入力してください。<br />
詳細はサイト管理人にお問い合わせください(フッターのtwitterリンクからリプライができます)。<br />

<link rel="stylesheet" type="text/css"
    href="<?= $base->GetURL('', 'client') ?>css/design.css">
<form method='POST' action='<?=$base->GetUri()?>'>
    <p>ID <input type='text' name='id' class='id' maxLength='20' /></p>
    <p>PASS <input type='password' name='pass' class='pass' maxLength='20' /></p>
    <button type='submit' class='send'>送信</button>
    <div class='notice'><?= $session->OnlyView('password-Error'); ?>
    </div>
    <div class='warning'><?= $session->OnlyView('password-Success'); ?>
    </div>
</form>