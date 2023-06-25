<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once(__DIR__. '/Setting.php');
require_once(__DIR__. '/Component/UA.php');
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

$css = new \Path($base->GetUrl('', 'client', false), '/');
$design = new \Path($css->Get(), '/');
$css->AddArray(['css', 'common', "{$agentCode}.css"]);
$design->AddArray(['css', "design.css"]);
?>
<link rel="stylesheet" type="text/css"
    href="<?=$css->Get() ?>">
<link rel="stylesheet" type="text/css"
    href="<?=$design->Get()?>">
<div class="container">
    <?php require_once('header.php'); ?>
    <main class="contents">
        <?php require_once(getcwd().'/design.php'); ?>
    </main>
    <?php require_once('footer.php'); ?>
</div>