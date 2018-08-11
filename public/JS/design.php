<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
require_once (DOCUMENT_ROOT. '/API/smarty/core.php');

echo CallFunc('ReturnFunc'). '<br />';

echo CallFunc('Add', 1, 2);
function Add($x, $y) {
    return $x + $y;
}

$smarty->assign('name', 'guest');
$smarty->display('index.tpl');
?>

<form class='form'>
<p> テキストボックス： <input type='textbox' class='textbox' /> </p>
<p> ラジオボタン： <input type='radio' class='radio' name='radio' /> <input type='radio' class='radio' name='radio' /> </p>
<div class='hidden'>ラジオボタンを選択しています</div>
<p> セレクトボタン： <select class='select'> <?php for ($i = 1; $i < 10; $i++) { ?><option class='option<?php echo $i ?>'>Option<?php echo $i ?></option> <?php } ?></select> </p>
<p> チェックボックス： <input type='checkbox' class='checkbox' /> </p>
<button type='submit'>決定する</button>
</form>
