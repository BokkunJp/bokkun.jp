<?php
// require_once dirname(dirname(__DIR__)). '/common/Layout/init.php';  // 共通処理用
require_once __DIR__. '/init.php'; // 個別処理用 (別途init.phpが必要)

$title = "カスタムテンプレート";
$img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <base href="../" />
        <link rel="shortcut icon" href="client/image/5959715.png">
        <?php require_once(PUBLIC_COMMON_DIR. "/jQuery/include.php");?>
        <script src="client/js/<?php echo $homepageTitle; ?>/index.js"></script>
        <script src="client/js/<?php echo $homepageTitle; ?>/realtime.js"></script>
        <script src="client/js/<?php echo $homepageTitle; ?>/time.js"></script>
        <link rel="stylesheet" type="text/css" href="client/css/<?php echo $homepageTitle; ?>/design.css">
    </head>
    <body>
        <link rel="stylesheet" type="text/css" href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
        <div class="container">
            <?php require_once('header.php'); ?>
        <div class='contents'>
            <?php require_once('design.php'); ?>
        </div>
            <?php require_once('footer.php'); ?>
        </div>
    </body>
</html>
