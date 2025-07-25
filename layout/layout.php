<!DOCTYPE html>
<?php

require_once __DIR__ . '/require.php';
require_once __DIR__ . '/init.php';
$img = "crown-vector.jpg";
?>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo $title; ?></title>
    <link rel="shortcut icon"
        href="<?= $base->getUrl('image') ?>IMG_7592.PNG">
    <?php if (!isset($contents)) : ?>
        <link rel="stylesheet" type="text/css"
            href="<?= $base->getUrl('css') ?><?= ltrim(createClient(''), '/') ?>design.css">
        <?php else : ?>
        <link rel="stylesheet" type="text/css"
            href="<?= $base->getUrl('css') ?>common/default.css">
    <?php endif; ?>
</head>

<body>
    <link rel="stylesheet" type="text/css"
        href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php
                if (isset($contents)) {
                    // コンテンツが指定されている
                    output($contents);
                }

                // index.phpと同階層にあるファイルを読み込む
                $contentsPath = getcwd(). DIRECTORY_SEPARATOR;
                IncludeFiles($contentsPath);
            ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>