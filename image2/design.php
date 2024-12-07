<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// 画像表示関係のメソッドを読み込み
$filePath = new \Path(PUBLIC_COMMON_DIR);
$filePath->addArray(["image", "File.php"]);
require_once $filePath->get();

// セッション開始
if (!isset($session)) {
    $session = new Public\Important\Session('image2');
}

// ページ数取得
$page = Public\Important\Setting::getQuery('page');

// 更新用ページに関する処理
$updatePage = Public\Important\Setting::getPost('update_page');

// Tokenクラスをセット
$publicImageToken = new Public\Important\Token('public-image-token', $session, true);
?>
<div class='view-image'>
    <form method='POST'
        action='./<?= $page != null ? "?page={$page}" : "" ?>'>
        <span class='notice-image-range'></span>
        <input type='range' name="image-range" class="image-range" min=<?=MAX_VIEW ?> max=<?= MAX_VIEW * PAGER ?> value=<?= getCountPerPage(); ?> step=<?= PAGER ?> />
        <span>現在の表示枚数:<span class='page-value'><?= getCountPerPage(); ?></span>枚</span>
        <?php
            if (isset($updatePage) && is_numeric($updatePage)) {
                echo "<div class='page-moved'>{$updatePage}ページに移動しました。</div>";
            }
        ?>

    </form>
</div>

<form class='pageForm' method="POST">
    <?php
        readImage();
        $publicImageToken->set();

    ?>
</form>