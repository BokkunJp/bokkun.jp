<?php
$session = new Public\Important\Session('python');
?>
<form action='./subdirectory/server.py' method='POST'>
    <h1>Pythonフォーム</h1>
    <p><input type='textbox' name='python-input' class='python-input' /> <button class='python-button'>送信する</button></p>
    <output class='python-output'></output>
</form>

<form method='POST'>
    <h1>PHPフォーム</h1>
    <p><input type='textbox' name='data' class='php-input' /> <button class='php-button'>送信する</button></p>
    <output  class='php-output'><?= $session->onlyView('output') ?></output>
</form>