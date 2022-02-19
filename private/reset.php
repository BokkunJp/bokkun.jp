<!DOCTYPE html>
<?php
require __DIR__. "/common/require.php";
require_once "common.php";

$title = 'ログアウト';
// アクセス警告メール
AlertAdmin('access', $title);
?>
<html lang="ja">
    <head>
        <meta charset='utf-8' />
        <title><?=$title ?></title>
        <link rel="stylesheet" type="text/css" href="./client/css/common.css">
    </head>
    <?php require_once "./common/header.php" ?>
    <body>
    <div class="adminContents">
    <?php

//  exit;   // テスト中断
    CountReset();
    function CountReset()
    {
        echo "<div align='center'><strong>ログアウトしました。</strong></div>";

        session_destroy();
    }
    ?>
    </div>
    <?php require_once "./common/footer.php"; ?>
    </body>
</html>