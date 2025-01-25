<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php

use Public\Important\Cache;
use Public\Important\Session;
use Public\Important\Setting;
use Public\Important\Token;

// $allSession = new Common\Important\Session();
// $allSession->delete();

$sessionName = 'cache';
$tokenName = "cache-csrf";
$session = new Session($sessionName);
$c = new Cache('test', $session);
$posts = Setting::getPosts();

$csrf = new Token($tokenName, $session, true);

if (!is_null($c)) {
    if (!$csrf->check()) {
        echo "<div class='warning'>リロードされたか、不正な遷移です。</div>";
    } else {
        echo "<div class='success'>正常に送信されました。</div>";
    }
}

?>
<form action='./' method='POST'>
    <button>ボタンを押してね！</button>
    <?=$csrf->set()?>
</form>