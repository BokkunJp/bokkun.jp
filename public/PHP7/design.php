<!-- デザイン用ファイル (PHPで処理を記述)-->
<form method='POST' action=''>
    <input type='text' name='test' max=32 min=0 />
    <input type='file' name='file' />
    <button type='submit'>送信</button>
</form>
<?php
// APIPath();
// Make('directory', 'moved');
$method = new Method();
$method->ViewPost('test');
$val = 32;

