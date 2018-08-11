<?php
require_once dirname(dirname(__DIR__)). "/common/Setting.php";
require_once '../common.php';
?>

<?php 
    $title = "管理画面";
    $img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" href="./client/image/5959715.png">
        <title><?php echo $title; ?></title>
        <base href="../" />
        <script src="client/js/common/jquery-3.1.1.js"></script>
        <script src="client/js/<?php echo basename(__DIR__); ?>/index.js"></script>
    </head>
    <body>
        <?php require_once('../common/layout.php'); ?>
    </body>
</html> 