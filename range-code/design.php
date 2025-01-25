<?php
$session = new Public\Important\Session('post');

$post = Public\Important\Setting::getPost('data');

?>
<form method='POST'>
    <input type='text' name='data' />
    <!-- <button type='button' class='jsSend'>送信(JS)</button> -->
    <button class='send'>送信(PHP)</button>
    <p><output class='jsForm'></output></p>
    <p><output><?= $session->onlyView('output') ?></output></p>

</form>