<?php
session_regenerate_id();
require_once __DIR__ . '/require.php';

$title = "カスタムテンプレート";
$img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
    <base href="../" />
    <link rel="shortcut icon" href="/public/client/image/5959715.png">
    <?php require_once dirname(dirname(__DIR__)) . '/public/common/Layout/init.php'; ?>
    <link rel="stylesheet" type="text/css" href="/public/client/css/<?php echo $homepageTitle; ?>/design.css">
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