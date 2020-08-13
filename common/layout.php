<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once (__DIR__. '/Setting.php');
require_once (__DIR__. '/Component/UA.php');
$agent = new UA\UA();

define('Phone', 2);
define('PC', 1);
$statusCode = $agent->DesignJudge();
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
<link rel="stylesheet" type="text/css" href="<?=AddPath($base->GetURL('', 'client', false), AddPath(AddPath('css', 'common', false, '/'), $agentCode. ".css", false, '/'), false, '/') ?>">
<link rel="stylesheet" type="text/css" href="<?=AddPath($base->GetURL('', 'client', false), 'css', true, '/')?>design.css">
<div class="container">
    <?php require_once('header.php'); ?>
    <main class="contents">
        <?php require_once(getcwd().'/design.php'); ?>
    </main>
    <?php require_once('footer.php'); ?>
</div>
