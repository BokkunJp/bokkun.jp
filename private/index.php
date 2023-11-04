<?php

if (!isset($_SESSION)) {
    if (PHP_OS === 'WINNT') {
        $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/";
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0755);
            $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/session/";
            if (!is_dir($sessionDir)) {
                mkdir($sessionDir, 0755);
            } else {
                $sessionDir .= '/session/';
            }
        }
        session_save_path($sessionDir);
    }
    session_start();
} else {
    session_reset();
}

?>
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
        href="<?= $base->GetURL('', 'client') ?>/image/IMG_7592.PNG">
    <link rel="stylesheet" type="text/css" href="./client/css/common.css">
    <link rel="stylesheet" type="text/css"
        href="./client/css/common/<?php echo $agentCode; ?>.css">
    <link rel="stylesheet" type="text/css"
        href="<?= $base->GetURL('', 'client') ?>/css/design.css">
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
                $notList = ['.', '..', 'Sample', 'Test', 'client', 'common', 'admin.php', 'common.php', 'common_css.php', 'secure', 'logout'];
                $dirList = scandir(__DIR__);
                $titleList = ['create' => 'ページ調整', 'edit' => 'ソース調整','IMAGE' => '画像投稿', 'log' => 'ログ'];
                $notList = addList($notList, $dirList, '.', 1);
                $notList = addList($notList, $dirList, '_', 1);

                foreach ($dirList as $index => $_dir) {
                    if (!searchData($_dir, $notList) && isset($titleList[$_dir])) {
                        echo "<li><a href=\"./$_dir/\" target=\"_new\">{$titleList[$_dir]}画面へ移動</a></li>";
                    }
                }
                ?>
            </form>
            <form method="POST" action="./logout">
                <button type='submit'>ログアウト</button>
            </form>
        </div>
        <!-- conent end -->
        <?php require_once './common/footer.php'; ?>
    </div>
    <!-- continer end -->
</body>

</html>