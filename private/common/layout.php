<?php

// セッションスタート
if (!isset($_SESSION)) {
    session_start();
} else {
    session_reset();
}
?>
<!DOCTYPE html>
<?php
require_once __DIR__ . '/require.php';

// アクセス警告メール
if (!isset($title)) {
    $title = '管理側';
}
AlertAdmin('access', $title);

$img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <title>管理側</title>
    <link rel="shortcut icon"
        href="<?= $base->GetURL('', 'client') ?>image/5959715.png">
    <link rel="stylesheet" type="text/css"
        href="<?= $base->GetURL('', 'client') ?>css/common.css">

    <link rel="stylesheet" type="text/css"
        href="/private/client/css/common/<?php echo $agentCode; ?>.css">

    <!-- ログファイル以外を含めて正常に動作(ログファイル以外は最初でループが止まる) -->
    <link rel="stylesheet" type="text/css"
        href="<?= $base->GetURL('', 'client') ?>css<?= CreateClient('log') ?>design.css">
</head>

<body>
    <div class='container'>
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php require_once(getcwd() . '/design.php'); ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>