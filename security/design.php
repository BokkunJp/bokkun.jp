<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php

use Public\Important\Cache;
use Public\Important\Session;
use Public\Important\Setting;
use Public\Important\Token;

const POST_FLG_ON = true;
const POST_FLG_OFF = false;

$name = "security-token";
$session = new Session($name);
$csrf = new Token($name, $session, true);
$posts = Setting::getPosts();

$checkCsrfFlg = checkCsrf();

if (!is_nulL($posts) && !$checkCsrfFlg) {
    echo "<div class='warning'>不正な遷移です。</div>";
}

function checkCsrf()
{
    $name = "security-token";
    $session = new Session($name);
    $csrf = new Token($name, $session, true);

    return $csrf->check();
}


function Out()
{
    $post = \Public\Important\Setting::getPosts();
    if (isset($post['input']) && !empty($post['input'])) {
        $input = $post['input'];
    } elseif (!is_string($post)) {
        return false;
    }
    $securityData = new \Security\Security($input);
    $encrypt = $securityData->encrypt();

    output("");
    output("ecrypt: ". $encrypt);

    output("decrypt: ". $securityData->decrypt());
    output("decrypt: ". $securityData->decrypt());

    $e2 = $securityData->encrypt(rowFlg:true);
    output("bin: ". bin2hex($e2));

    output("hash: ". $securityData->createHash("sha256"));
    output("hmac: ". $securityData->createHmacHash("sha256"));
}
?>
<form action='./' method='POST'>
    <input type='textbox' name='input' />
    <button>送信</button>
    <?php if (!is_null($posts) && $checkCsrfFlg): ?>
        <output name='crypt'><?=Out()?></output>
    <?php endif; ?>
    <?=$csrf->set() ?>
</form>