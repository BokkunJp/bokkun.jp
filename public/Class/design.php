<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
require_once (DOCUMENT_ROOT. '/API/smarty/core.php');

$token='';
if (PublicSetting\GetQuery() != null) {
  $checkToken = CheckToken('token', CSRFErrorMessage(), "<br /><a href='./'>トップへ戻る</a>");
  if ($checkToken === false) {
    return false;
  }
} else {
  $token = MakeToken();
  SetToken($token);
}

// deb_dump('デバッグ');
$smarty->assign('name', 'guest');
$smarty->display('index.tpl');
$test = new SubClass();
$test->ActionFunction('SetData', 5);
echo $test->ActionFunction('GetData');


?>
<form action='./Class/?post=on' method='POST'>
  <input type='hidden' name='token' value="<?=$token?>" />
  <input type='submit' class='checkToken' />
</form>
