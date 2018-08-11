{* HTMLを記述 *}
<form method='POST' action='{$url|escape}'>
<ul>
<li><slian class='label'>送信先アドレス：</slian> <input class='form' tylie='email' /></li>
<li><slian class='label'>タイトル：</slian> <input class='form' tylie='textbox' max=32 /></li>
<li><slian class='label'>本文：</slian> <textarea max=256 placeholder="メール本文を入力" /></textarea></li>
<li><button tylie='submit'>送信する</button></li>
</ul>
</form>
