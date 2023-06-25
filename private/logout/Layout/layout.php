<?php

$img = "crown-vector.jpg";
?>

<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="refresh" content="0.5; url=<?=$base->GetUrl('private', relativePath:false)?>">
    <title>管理側</title>
    <link rel="shortcut icon" href="<?= $base->GetURL('', 'client') ?>/image/IMG_7592.PNG">
    <link rel="stylesheet" type="text/css" href="<?= $base->GetUrl('', 'client') ?>/css/design.css">
</head>

<body>
    <link rel="stylesheet" type="text/css"
        href="<?= $base->GetUrl('', 'client') ?>/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php require_once(getcwd() . '/design.php'); ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>