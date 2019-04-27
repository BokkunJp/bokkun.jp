<?php
require_once __DIR__ . '/init.php';

$title = "WebGL";
$img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
    <base href="../" />
    <link rel="shortcut icon" href="<?= $base->GetUrl("5959715.png", 'image') ?>">
    <?php require_once(PUBLIC_COMMON_DIR . "/Load/include.php"); ?>
    <script src="/public/client/js/common/time/realtime.js"></script>
    <script src="/public/client/js/common/time/time.js"></script>
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