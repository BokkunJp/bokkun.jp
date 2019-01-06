<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once (__DIR__. '/Setting.php');
require_once (__DIR__. '/Component/UA.php');
$agent = new UA\UA();

define('Phone', 2);
define('PC', 1);
$statusCode = $agent->designJudege();
switch ($statusCode) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}
?>
<link rel="stylesheet" type="text/css" href="<?=$base->GetURL('common', 'css')?>/<?php echo $agentCode; ?>.css">
<link rel="stylesheet" type="text/css" href="<?=$base->GetURL('', 'css')?>/design.css">
<div class="container">
    <?php require_once('header.php'); ?>
    <div class='contents'>
        <?php require_once(getcwd().'/design.php'); ?>
    </div>
    <?php require_once('footer.php'); ?>
</div>
