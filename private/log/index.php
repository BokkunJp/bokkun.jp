<?php
require_once dirname(__DIR__) . "/common/require.php";
require_once dirname(__DIR__, 2) . '/common/Component/Function.php';
$title = 'ログ一覧';
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
    <link rel="stylesheet" type="text/css" href="../client/css/common.css">
    <link rel="stylesheet" type="text/css" href="../client/css/<?php echo "test"; ?>/common.css">
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
                $notList = ListAdd($notList, $dirList, '.', 1);
                $notList = ListAdd($notList, $dirList, '_', 1);

                foreach ($dirList as $index => $_dir) {
                    if (!in_array($_dir, $notList) && isset($titleList[$_dir])) {
                        echo "<li><a href=\"./$_dir/\" target=\"_new\">{$titleList[$_dir]}ログを見る</a></li>";
                    }
                }
                ?>
            </form>
        </div>
        <!-- conent end -->
    </div>
    <?php require_once dirname(__DIR__) . '/common/footer.php'; ?>
    </div>
    <!-- continer end -->
</body>

</html>