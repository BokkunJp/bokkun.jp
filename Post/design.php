<?php
$session = new public\Session();
?>
<form method='POST'>
    <input type='text' name='data' />
    <button type='button' class='jsSend'>送信(JS)</button>
    <button class='send'>送信(PHP)</button>
    <p><output class='jsForm'></output></p>
    <p><output><?= $session->OnlyView('output') ?></output></p>

</form>