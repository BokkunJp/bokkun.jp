<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
if (!isset($session)) {
    $session = new PublicSetting\Session();
}

// ページ数取得
$page = PublicSetting\Setting::GetQuery('page');

// 更新用ページに関する処理
$updatePage = PublicSetting\Setting::GetPost('update_page');
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
        <span>現在の表示枚数:<?= GetCountPerPage(); ?>枚</span>
        <?php
            if (isset($updatePage) && is_numeric($updatePage)) {
                echo "<div class='page-moved'>{$updatePage}ページに移動しました。</div>";
            }
        ?>
    </form>
</div>

    <form class='pageForm' method="POST">
    <?php
    ReadImage();
    ?>
    <input type='hidden' name='token' value="<?= $token = MakeToken(); ?>" />
</form>

<?php
if (isset($token)) {
    SetToken($token);
}
