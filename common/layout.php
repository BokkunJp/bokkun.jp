<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once(__DIR__. '/Setting.php');
require_once(__DIR__. '/Component/Ua.php');
$agent = new \Common\Important\UA();

define('Phone', 2);
define('PC', 1);
$statusCode = $agent->judgeDevice();
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

$css = new \Path($base->getUrl(), '/');
$design = new \Path($css->get(), '/');
$css->addArray(['css', 'common', "{$agentCode}.css"]);
$design->addArray(['css', "design.css"]);
?>
<link rel="stylesheet" type="text/css"
    href="<?=$css->get() ?>">
<link rel="stylesheet" type="text/css"
    href="<?=$design->get()?>">
<div class="container">
    <?php require_once('header.php'); ?>
    <main class="contents">
        <?php require_once(getcwd().'/design.php'); ?>
    </main>
    <?php require_once('footer.php'); ?>
</div>