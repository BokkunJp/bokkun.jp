<!DOCTYPE html>
<?php
require_once __DIR__ . "/common/require.php";
require_once dirname(__DIR__) . '/common/Component/Function.php';

$title = '管理側コンテンツ一覧';

// アクセス警告メール
alertAdmin('access', $title);
?>
<!DOCTYPE <html lang="ja">

<head>
    <meta charset='utf-8' />
    <title>管理側</title>
    <link rel="shortcut icon"
        href="<?= $base->getUrl('image') ?>IMG_7592.PNG">
    <link rel="stylesheet" type="text/css" href="./client/css/common.css">
    <link rel="stylesheet" type="text/css"
        href="<?= $base->getUrl('css') ?>common/<?php echo $agentCode; ?>.css">
    <link rel="stylesheet" type="text/css"
        href="<?= $base->getUrl('css') ?>design.css">
</head>

<body>
    <!-- container -->
    <div class="container">
        <!-- header -->
        <?php require_once "./common/header.php" ?>
        <!-- content -->
        <div class="content">
            <h2>管理メニュー</h2>
            <form>
                <?php
                $notList = ['.', '..', 'sample', 'test', 'client', 'common', 'admin.php', 'common.php', 'common_css.php', 'secure', 'logout'];
                $dirList = scandir(__DIR__);
                $titleList = ['create' => 'ページ調整', 'edit' => 'ソース調整','image' => '画像投稿', 'log' => 'ログ'];
                $notList = addList($notList, $dirList, '.', 1);
                $notList = addList($notList, $dirList, '_', 1);

                foreach ($dirList as $index => $_dir) {
                    if (!searchData($_dir, $notList) && isset($titleList[$_dir])) {
                        echo "<li><a href=\"./$_dir/\" target=\"_new\">{$titleList[$_dir]}画面へ移動</a></li>";
                    }
                }
                ?>
            </form>
            <div class='logout'>
                <form method="POST" action="./logout">
                    <button type='submit'>ログアウト</button>
                </form>
            </div>
            <div class='logout-with-session-reset'>
                <form method="POST" action="./logout-with-session-reset">
                    <button type='submit'>ログアウト(全セッション削除)</button>
                </form>
            </div>
        </div>
        <!-- conent end -->
        <?php require_once './common/footer.php'; ?>
    </div>
    <!-- continer end -->
</body>

</html>