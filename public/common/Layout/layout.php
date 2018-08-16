<?php
session_regenerate_id();
require_once __DIR__. '/init.php';
// トークンセット
// SetToken();
// $title = "テンプレート";
$img = "crown-vector.jpg";

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow">
        <title><?php echo $title; ?></title>
        <base href="../" />
        <link rel="shortcut icon" href="client/image/5959715.png">
        <?php require_once(COMMON_DIR. "/jQuery/include.php");?>
        <script src="client/js/common/time/realtime.js"></script>
        <script src="client/js/common/time/time.js"></script>
        <script src="client/js/<?php echo $homepageTitle; ?>/index.js"></script>
        <link rel="stylesheet" type="text/css" href="client/css/<?php echo $homepageTitle; ?>/design.css">
    </head>
    <body>
        <link rel="stylesheet" type="text/css" href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
        <div class="container">
            <?php require_once('header.php'); ?>
        <div class='contents'>
            <?php require_once(getcwd().'/design.php'); ?>
        </div>
            <?php require_once('footer.php'); ?>
        </div>
    </body>
</html>
