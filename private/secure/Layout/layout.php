<?php

$img = "crown-vector.jpg";
$title = '認証';

$base = new \Private\Important\Setting();

?>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <title>管理側</title>
    <link rel="shortcut icon"
        href="<?= $base->getUrl() ?>/image/IMG_7592.PNG">
    <link rel="stylesheet" type="text/css"
        href="<?= $base->getUrl() ?>/css/design.css">
</head>

<body>
    <link rel="stylesheet" type="text/css"
        href="<?= $base->getUrl() ?>css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php require_once(PRIVATE_DIR . '/secure/design.php'); ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>