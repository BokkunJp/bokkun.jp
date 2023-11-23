<?php

$img = "crown-vector.jpg";
?>

<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="refresh" content="0.5; url=<?=$base->getUrl('private', relativePath:false)?>">
    <title>管理側</title>
    <link rel="shortcut icon" href="<?= $base->getUrl() ?>/image/IMG_7592.PNG">
    <link rel="stylesheet" type="text/css" href="<?= $base->getUrl() ?>/css/design.css">
</head>

<body>
    <link rel="stylesheet" type="text/css"
        href="<?= $base->getUrl() ?>/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php require_once(getcwd() . '/design.php'); ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>