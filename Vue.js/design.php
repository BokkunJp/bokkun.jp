<!-- デザイン用ファイル (PHPで処理を記述)-->
<span id='test'>{{message}}<span>
<?php
use function setFunc\is_true;

$hash = password_hash('password01', PASSWORD_ARGON2ID);
$verify = password_verify('password01', $hash);
var_dump($verify);
