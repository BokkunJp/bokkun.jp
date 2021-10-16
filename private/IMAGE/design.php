<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
if (!isset($session)) {
    $session = new PrivateSetting\Session();
}

// ページ数取得
$page = PrivateSetting\Setting::GetQuery('page');

// 更新用ページに関する処理
$updatePage = PrivateSetting\Setting::GetPost('update_page');

// imageディレクトリ内のディレクトリリストを取得
$imgDirList = ['---'];
foreach (scandir(PUBLIC_IMAGE_DIR) as $_list) {
    if (is_dir(AddPath(PUBLIC_IMAGE_DIR, $_list)) && FindFileName($_list)) {
        $imgDirList[] = $_list;
    }
}

// tokenリスト用の配列を作成
$tokenList = [];

?>
<form method='POST' action ='./'>
    対象のページを選択
    <select name="image-type" class="image-type">
    <?php
        foreach ($imgDirList as $_list) {
            echo "<option value=\"{$_list}\">". $_list. "</option>";
        }

    ?>
    </select>
    ： <span class='view-image-type'><?=GetImagePageName()?></span>
    <input type='hidden' name='select-token' value="<?= $tokenList['select-token'] = MakeToken() ?>" />
</form>
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
    </form>
</div>
<form enctype="multipart/form-data" action="./subdirectory/notAutoInclude/server.php<?= $page != null ? "?page={$page}" : "" ?>" method='POST'>
    <input type='hidden' name='upload-token' value="<?= $tokenList['upload-token'] = MakeToken() ?>" />
    <input type='file' name='all-files[]' multiple /> <button type='submit' class='fileButton'>送信</button>
    <span>
        <div class='footer_char'>※同じ名前のファイルは複数保存されず、上書きされます。</div>
        <div class='notice'><?= $session->OnlyView('notice'); ?></div>
        <div class='warning'><?= $session->OnlyView('warning'); ?></div>
        <div class='success'><?= $session->OnlyView('success'); ?></div>
        <?php
            if (isset($updatePage) && is_numeric($updatePage)) {
                echo "<div>{$updatePage}ページに移動しました。</div>";
            }
        ?>
        <!-- <input type='checkbox' name='deb_flg' value=1 /> デバッグモード -->
    </span>
</form>

<form class='pageForm' action="./subdirectory/notAutoInclude/server.php?mode=edit<?= $page !== null ? "&page={$page}" : "" ?>" method='POST'>
    <?php
        ReadImage();
    ?>
    <input type='hidden' name='view-token' value="<?= $tokenList['view-token'] = MakeToken() ?>" />
    <p>
        <button type='submit' name='delete'>チェックした画像を削除する</button>
        <button type='submit' name='copy'>チェックした画像をコピーする</button>
        <input type='hidden' class='copy-image-name' name='copy-image-name' />
    </p>
</form>
<?php
foreach ($tokenList as $_key => $_token) {
    if (isset($_token)) {
        SetToken($_token, $_key);
    }
}
