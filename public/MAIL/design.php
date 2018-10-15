<!-- デザイン用ファイル -->
<?php
use Model\Mail as Mail;

require_once (DOCUMENT_ROOT. '/API/smarty/core.php');

$mail = new Mail();
if ($mail->CheckDataType($base->getPost())) {
  $mail->SetAddress($base->getPost());
  $mail->SendMail();
}

$smarty->assign('url', $url. $base->GetURI());
$smarty->display('index.tpl');
