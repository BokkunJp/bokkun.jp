<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php

use Public\Important\Cache;
use Public\Important\Session;
use Public\Important\Setting;
use Public\Important\Token;

const POST_FLG_ON = true;
const POST_FLG_OFF = false;

$name = "cache-csrf";
$session = new Session($name);
$csrf = new Token($name, $session, true);

if (!checkCsrf(POST_FLG_ON)) {
    echo "<div class='warning'>不正な遷移です。</div>";
}

function checkCsrf(bool $postFlg)
{
    $name = "cache-csrf";
    $session = new Session($name);
    $csrf = new Token($name, $session, true);
    $c = new Cache('test', $session);
    
    $posts = Setting::getPosts();

    $result = true;
    if (!$csrf->check()) {
        $result = false;
    }

    if (is_null($posts) && $postFlg) {
        $result = true;
    }

    return $result;
}


function Out()
{
    $post = \Public\Important\Setting::getPosts();
    $input = $post['input'];
    $securityData = new \Security\Security();
    $encrypt = $securityData->encrypt($input);

    output("");
    output("ecrypt: ". $encrypt);

    output("decrypt: ". $securityData->decrypt());
    output("decrypt: ". $securityData->decrypt());

    $e2 = $securityData->encrypt($input, rowFlg:true);
    output("bin: ". bin2hex($e2));

    output("hash: ". $securityData->createHash("sha256"));
    output("hmac: ". $securityData->createHmacHash("sha256"));
}
?>
<form action='./' method='POST'>
    <input type='textbox' name='input' />
    <button>送信</button>
    <?php if (checkCsrf(POST_FLG_OFF)): ?>
        <output name='crypt'><?=Out()?></output>
    <?php endif; ?>
    <?=$csrf->set() ?>
</form>