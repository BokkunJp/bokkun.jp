<?php
require_once __DIR__ . '/require.php';
require_once __DIR__ . '/init.php';

// $title = "テンプレート";
$img = "crown-vector.jpg";

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo basename(getcwd()); ?></title>
    <link rel="shortcut icon" href="<?= $base->GetURL('', 'client') ?>image/5959715.png">
    <link rel="stylesheet" type="text/css" href="<?= $base->GetURL('', 'client') ?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?= $base->GetURL('', 'client') ?>css<?= CreateClient('public') ?>design.css"></head>
<body>
    <link rel="stylesheet" type="text/css" href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php require_once(getcwd() . '/design.php'); ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>