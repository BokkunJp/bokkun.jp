<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// $pathClass = new \Path('aaa');
// $pathClass->Add('test');

// $cache = new \Cache('test');

use Public\Session;
use Public\Token;

$name = "cache-csrf";
$session = new Session($name);
$csrf = new Token($name, $session);
if (!$csrf->Check()) {
    echo "<div class='warning'>不正な遷移です。</div>";
}
?>
<form action='./' method='POST'>
      <button>ボタンを押してね！</button>
      <?=$csrf->GetTag()?>
</form>