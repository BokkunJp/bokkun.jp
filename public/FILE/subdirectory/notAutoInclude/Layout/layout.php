<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/require.php';
$title = "画像の追加・削除";
$img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
    <base href="../" />
    <link rel="shortcut icon" href="client/image/5959715.png">
    <?php require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/common/Layout/init.php'; ?>
    <link rel="stylesheet" type="text/css" href="/public/client/css/FILE/<?php echo $homepageTitle; ?>/design.css">
</head>

<body>
    <link rel="stylesheet" type="text/css" href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>