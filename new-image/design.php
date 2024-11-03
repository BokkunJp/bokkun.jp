<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
if (!isset($session)) {
    $session = new Public\Important\Session('image');
}

// ページ数取得
$page = Public\Important\Setting::getQuery('page');

// 更新用ページに関する処理
$updatePage = Public\Important\Setting::getPost('update_page');

// Tokenクラスをセット
$publicImageToken = new Public\Important\Token('public-image-token', $session, true);
?>
<div class='view-image'>
    <form method='POST' action='./<?= $page != null ? "?page={$page}" : "" ?>'>
        <select name='image-value'>
            <?php
            for ($i = 1; $i <= MAX_VIEW; $i++) {
                $_val = $i * PAGER;
                echo "<option value={$_val}>" . $_val . "</option>";
            }
            ?>
        </select>
        <span><button value='editView'>表示枚数の変更</button></span>
        <span>現在の表示枚数:<?= getCountPerPage(); ?>枚</span>
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