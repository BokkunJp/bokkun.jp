<?php
// require_once dirname(dirname(__DIR__)). '/common/Layout/init.php';  // 共通処理用
require_once __DIR__. '/init.php'; // 個別処理用 (別途init.phpが必要)

$title = "画像の追加・削除";
$img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <base href="../" />
        <link rel="shortcut icon" href="client/image/5959715.png">
        <?php require_once(PUBLIC_COMMON_DIR. "/Load/include.php");?>
        <script src="/public/client/js/FILE/<?php echo $homepageTitle; ?>/realtime.js"></script>
        <script src="/public/client/js/FILE/<?php echo $homepageTitle; ?>/index.js"></script>
        <script src="/public/client/js/FILE/<?php echo $homepageTitle; ?>/time.js"></script>
        <link rel="stylesheet" type="text/css" href="/public/client/css/FILE/<?php echo $homepageTitle; ?>/design.css">
    </head>
    <body>
        <link rel="stylesheet" type="text/css" href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
        <div class="container">
            <?php require_once('header.php'); ?>
