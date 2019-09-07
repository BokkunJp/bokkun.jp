<?php
session_regenerate_id();
require_once __DIR__ . '/require.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo basename(getcwd()); ?></title>
    <base href="../" />
    <link rel="shortcut icon" href="client/image/5959715.png">
    <?php PUBLIC_DIR . '/init.php'; // 共通処理用 ?>
    <!--<?php require_once __DIR__ . '/init.php'; // 個別処理用 (別途init.phpが必要) ?> -->
    <link rel="stylesheet" type="text/css" href="client/css/<?php echo $homepageTitle; ?>/design.css">
</head>

<body>
    <?php
    $title = "カスタムテンプレート";
    $img = "crown-vector.jpg";
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <div class='contents'>
            <?php require_once('design.php'); ?>
        </div>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>