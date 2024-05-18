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
alertAdmin('access', $title);

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
        href="<?= $base->getUrl() ?>image/IMG_7592.PNG">
    <link rel="stylesheet" type="text/css"
        href="<?= $base->getUrl() ?>css/common.css">

    <link rel="stylesheet" type="text/css"
        href="/private/client/css/common/<?php echo $agentCode; ?>.css">

    <!-- ログファイル以外は最初でループが止まる -->
    <link rel="stylesheet" type="text/css"
        href="<?= $base->getUrl('css') ?><?= ltrim(createClient('log'), '/') ?>design.css">
</head>

<body>
    <div class='container'>
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php
                if (!isset($contents)) {
                    require_once(getcwd() . '/design.php');
                } elseif ($contents) {
                    output($contents);
                }
            ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>