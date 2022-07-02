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
if (!isset($ajaxFlg)) {
    require_once __DIR__ . '/require.php';
}

// タイトル・ヘッダータイトル設定
if (isset($homepageTitle)) {
    $title = $homepageTitle;
} elseif (!isset($title)) {
    $title = '管理側';
}

// アクセス警告メール
AlertAdmin('access', $title);

$img = "crown-vector.jpg";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $title ?>
    </title>
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
            <?php
                if (!isset($contents)) {
                    require_once(getcwd() . '/design.php');
                } elseif ($contents) {
                    Output($contents);
                }
            ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>