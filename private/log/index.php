<!DOCTYPE html>
<html lang="ja">
<?php
require_once dirname(__DIR__) . "/common/require.php";
require_once dirname(__DIR__, 2) . '/common/Component/Function.php';
$title = 'ログ一覧';
?>

<head>
    <meta charset='utf-8' />
    <title>管理画面</title>

    <link rel="shortcut icon"
        href="<?= $base->GetURL('', 'client') ?>/image/IMG_7592.PNG">
    <link rel="stylesheet" type="text/css" href="../client/css/common.css">
    <link rel="stylesheet" type="text/css"
        href="<?= $base->GetURL('', 'client') ?>/css<?= createClient('log') ?>/design.css">
</head>

<body>
    <!-- container -->
    <div class="container">
        <!-- header -->
        <?php require_once dirname(__DIR__) . "/common/header.php" ?>
        <!-- content -->
        <div class="content">
            <form>
                <?php
                $notList = [];
                $dirList = scandir(__DIR__);
                $titleList = ['error' => 'エラー', 'access' => 'アクセス'];
                $notList = addList($notList, $dirList, '.', 1);
                $notList = addList($notList, $dirList, '_', 1);

                foreach ($dirList as $index => $_dir) {
                    if (!searchData($_dir, $notList) && isset($titleList[$_dir])) {
                        echo "<li><a href=\"./$_dir/\" target=\"_new\">{$titleList[$_dir]}ログを見る</a></li>";
                    }
                }
                ?>
            </form>
        </div>
        <!-- content end -->
    </div>
    <?php require_once dirname(__DIR__) . '/common/footer.php'; ?>
    </div>
    <!-- continer end -->
</body>

</html>