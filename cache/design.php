<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// $pathClass = new \Path('aaa');
// $pathClass->add('test');

// $cache = new \Cache('test');

use Public\Important\Setting;
use Public\Important\Session;
use Public\Important\Token;

$name = "cache-csrf";
$session = new Session($name);
$csrf = new Token($name, $session, true);

if (!is_null(Setting::getPosts())) {
    if (!$csrf->check()) {
        echo "<div class='warning'>不正な遷移です。</div>";
    } else {
        echo "<div class='success'>正常に送信されました。</div>";
    }
}

?>
<form action='./' method='POST'>
    <button>ボタンを押してね！</button>
    <?=$csrf->set()?>
</form>