<?php
$xmlToken = new Public\Token('xml-token', $session, true);
?>
<form enctype="multipart/form-data" method='POST'>
    XMLファイルのアップロード：<input type='file' class='xml' name='xml' />
    <?php $xmlToken->Set(); ?>
 <button>アップロード</button>
</form>
