<?php
require_once __DIR__. "/common/Setting.php";
require_once 'common.php';
$fp = fopen( "../count/count.txt", "r+" ); // ファイル開く
$count = fgets( $fp, 10 ); // 9桁分値読み取り
rewind( $fp ); // ファイルポインタを先頭に戻す
fclose( $fp ); // ファイル閉じる
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
                    <?php
                    echo "<div align='right'><strong>カウンタファイル: ".$count."</strong></div>";
                    echo "<div align='right'><strong>セッションカウンタ: ".$session."</strong></div>";
                    ?>
                    <form>
                        <a href="<?php $url ?>/private/create/">ページ作成画面へ移動</a>
                    </form>
                    <form method="POST" action="./reset.php">
                        <button type='submit'>カウンタのリセット</button>
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