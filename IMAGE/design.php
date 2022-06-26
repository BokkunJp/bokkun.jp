<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// 画像表示関係のメソッドを読み込み
require_once AddPath(AddPath(PUBLIC_COMMON_DIR, "IMAGE"), 'File.php', false);

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
    <form method='POST'
        action='./<?= $page != null ? "?page={$page}" : "" ?>'>
        <span class='notice-image-range'></span>
        <input type='range' name="image-range" class="image-range" min=<?=MAX_VIEW ?> max=<?= MAX_VIEW * PAGER ?> value=<?= GetCountPerPage(); ?> step=<?= PAGER ?> />
        <span>現在の表示枚数:<span class='page-value'><?= GetCountPerPage(); ?></span>枚</span>
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
    <input type='hidden' name='token'
        value="<?= $token = MakeToken(); ?>" />
</form>

<?php
if (isset($token)) {
        SetToken($token);
    }
