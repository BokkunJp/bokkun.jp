<?php
require_once __DIR__ . '/require.php';

$title = "WebGL";
$img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <title><?php echo $title; ?></title>
    <base href="../" />
    <link rel="shortcut icon" href="<?php echo $public; ?>client/image/5959715.png">
    <link rel="stylesheet" type="text/css" href="<?php echo $public; ?>client/css/webGL/design.css">
    <?php require_once __DIR__ . '/init.php'; ?>
</head>

<body>
    <link rel="stylesheet" type="text/css" href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php require_once('design.php'); ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>