<?php
require_once __DIR__ . "/common/Setting.php";
require_once 'common.php';
require_once dirname(__DIR__) . '/common/Component/Function.php';
$title = '管理側コンテンツ一覧';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset='utf-8' />
    <title>管理画面</title>
    <script src="<?php echo $url; ?>/js/JavaScript/time/time.js"></script>
    <script src="<?php echo $url; ?>/js/JavaScript/time/realtime.js"></script>
    <script src="<?php echo $url; ?>/js/JavaScript/time/convert.js"></script>

    <script>
        var t = new Time();
    </script>
    <?php require_once "common_css.php"; ?>
</head>

<body>
    <!-- #wrapper -->
    <div class="wrapper">
        <!-- header -->
        <?php require_once "./common/header.php" ?>
        <!-- container -->
        <div class="container">
            <!-- content -->
            <div class="content">
                <form>
                    <?php
                    $notList = ['.', '..', 'Sample', 'client', 'common', 'admin.php', 'common.php', 'common_css.php', 'reset.php', 'secure.php'];
                    $dirList = scandir(__DIR__);
                    $titleList = ['FILE' => '画像投稿', 'create' => 'ページ作成'];
                    $notList = ListAdd($notList, $dirList, '.', 1);
                    $notList = ListAdd($notList, $dirList, '_', 1);

                    foreach ($dirList as $index => $_dir) {
                        if (!in_array($_dir, $notList)) {
                            echo "<li><a href=\"./$_dir/\" target=\"_new\">{$titleList[$_dir]}画面へ移動</a></li>";
                        }
                    }
                    ?>
                </form>
                <form method="POST" action="./reset.php">
                    <button type='submit'>セッションのリセット</button>
                </form>
            </div>
            <!-- conent end -->
        </div>
        <!-- continer end -->
        <?php require_once './common/footer.php'; ?>
    </div>
    <!-- #wrapper end -->
</body>

</html>