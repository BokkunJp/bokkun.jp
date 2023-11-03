<?php

// セッションスタート
if (!isset($_SESSION)) {
    if (PHP_OS === 'WINNT') {
        $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/";
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0755);
            $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/session/";
            if (!is_dir($sessionDir)) {
                mkdir($sessionDir, 0755);
            } else {
                $sessionDir .= '/session/';
            }
        }
        session_save_path($sessionDir);
    }
    session_start();
} else {
    session_reset();
}

?>
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
        href="<?= $base->GetUrl('', 'client') ?>/image/IMG_7592.PNG">
    <?php if (!isset($contents)) : ?>
        <link rel="stylesheet" type="text/css"
            href="<?= $base->GetUrl('', 'client') ?>/css<?= createClient('') ?>design.css">
        <?php else : ?>
        <link rel="stylesheet" type="text/css"
            href="<?= $base->GetUrl('', 'client') ?>/css/common/ajax.css">
    <?php endif; ?>
</head>

<body>
    <link rel="stylesheet" type="text/css"
        href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php
                if (!isset($contents)) {
                    if (file_exists(getcwd(). '/design.php')) {
                        $contentsPath = getcwd();
                    }else {
                        $contentsPath = dirname(getcwd());
                    }
                    require_once($contentsPath . '/design.php');
                } elseif ($contents) {
                    output($contents);
                }
            ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>