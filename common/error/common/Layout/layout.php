<?php

// セッションスタート
if (!isset($_SESSION)) {
    if (PHP_OS === 'WINNT') {
        $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/session/";
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0755);
        }
        session_save_path($sessionDir);
    }
    session_start();
} else {
    session_reset();
}

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
        href="<?=$base->GetURL('')?>common/error/client/image/IMG_7592.PNG">
    <link rel="stylesheet" type="text/css"
        href="<?=$base->GetURL('')?>common/error/client/css/common/<?php echo $agentCode; ?>.css">
    <link rel="stylesheet" type="text/css"
        href="<?=$base->GetURL('')?>common/error/client/css/common.css">
    <link rel="stylesheet" type="text/css"
        href="<?=$base->GetURL('')?>common/error/client/css/<?php echo $errCode; ?>/design.css">
    <script
        src="<?=$base->GetURL('')?>common/error/client/js/common/time/realtime.js">
    </script>
    <script
        src="<?=$base->GetURL('')?>common/error/client/js/common/time/time.js">
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