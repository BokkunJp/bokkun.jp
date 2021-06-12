<!DOCTYPE html>
<?php
require_once __DIR__ . "/common/require.php";
require_once dirname(__DIR__) . '/common/Component/Function.php';

$title = '管理側コンテンツ一覧';

// アクセス警告メール
AlertAdmin('access', $title);

?>
<html lang="ja">

<head>
    <meta charset='utf-8' />
    <title>管理画面</title>
    <link rel="shortcut icon" href="<?= $base->GetURL('', 'client') ?>image/5959715.png">
    <link rel="stylesheet" type="text/css" href="./client/css/common.css">
    <link rel="stylesheet" type="text/css" href="<?= $base->GetURL('', 'client') ?>css/design.css">
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
                $notList = ['.', '..', 'Sample', 'Test', 'client', 'common', 'admin.php', 'common.php', 'common_css.php', 'reset.php', 'secure.php'];
                $dirList = scandir(__DIR__);
                $titleList = ['create' => 'ページ調整', 'edit' => 'ソース調整','IMAGE' => '画像投稿', 'log' => 'ログ'];
                $notList = AddList($notList, $dirList, '.', 1);
                $notList = AddList($notList, $dirList, '_', 1);

                foreach ($dirList as $index => $_dir) {
                    if (!in_array($_dir, $notList)) {
                        echo "<li><a href=\"./$_dir/\" target=\"_new\">{$titleList[$_dir]}画面へ移動</a></li>";
                    }
                }
                ?>
            </form>
            <form method="POST" action="./reset.php">
                <button type='submit'>ログアウト</button>
            </form>
        </div>
        <!-- conent end -->
    </div>
    <?php require_once './common/footer.php'; ?>
    </div>
    <!-- continer end -->
</body>

</html>