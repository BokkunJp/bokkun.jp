{* HTMLを記述 *}
<form method='POST' action='{$url|escape}'>
<ul>
<li><span class='label'>送信先アドレス：</span> <input class='form' name='email' tylie='email' /></li>
<li><span class='label'>タイトル：</span> <input class='form' name='title' tylie='textbox' max=32 /></li>
<li><span class='label'>本文：</span> <textarea max=256 name='contents' placeholder="メール本文を入力" /></textarea></li>
<li><button tylie='submit'>送信する</button></li>
</ul>
</form>
