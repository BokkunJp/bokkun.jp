<?php
$xmlToken = new Public\Important\Token('xml-token', $session, true);
?>
<form enctype="multipart/form-data" method='POST'>
    XMLファイルのアップロード：<input type='file' class='xml' name='xml' />
    <?php $xmlToken->set(); ?>
 <button>アップロード</button>
</form>
