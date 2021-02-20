<?php
$session = new PublicSetting\Session();
?>
<form enctype="multipart/form-data" method='POST'>
    XMLファイルのアップロード：<input type='file' class='xml' name='xml' />
    <input type='hidden' name='xml-token' value="<?= $token = MakeToken() ?>" /> <button>アップロード</button>
</form>
<?php
SetToken($token, 'xml-token');
