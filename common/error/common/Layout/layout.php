<?php

require_once __DIR__. '/init.php';

if (is_array($title)) {
    foreach ($title as $key => $value) {
        $$key = $value;
    }
}

$img = "crown-vector.jpg";
?>
<html lang="ja">

<head>
    <title><?php echo $headerTitle; ?>
    </title>
    <base href="../" />
    <link rel="shortcut icon"
        href="<?=$base->getUrl('')?>common/error/client/image/IMG_7592.PNG">
    <link rel="stylesheet" type="text/css"
        href="<?=$base->getUrl('')?>common/error/client/css/common/<?php echo $agentCode; ?>.css">
    <link rel="stylesheet" type="text/css"
        href="<?=$base->getUrl('')?>common/error/client/css/common.css">
    <link rel="stylesheet" type="text/css"
        href="<?=$base->getUrl('')?>common/error/client/css/<?php echo $errCode; ?>/design.css">
    <script
        src="<?=$base->getUrl('')?>common/error/client/js/common/time/realtime.js">
    </script>
    <script
        src="<?=$base->getUrl('')?>common/error/client/js/common/time/time.js">
    </script>
</head>

<body>
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php require_once('design.php'); ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>