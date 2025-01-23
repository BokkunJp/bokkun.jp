<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
$session = new Private\Important\Session('image');

// ページ数取得
$page = Private\Important\Setting::getQuery('page');

// 更新用ページに関する処理
$updatePage = Private\Important\Setting::getPost('update_page');

// imageディレクトリ内のディレクトリリストを取得
$imgDirList = ['---'];
foreach (scandir(PUBLIC_DIR_LIST['image']) as $_list) {
    $imagDirPath = new \Path(PUBLIC_DIR_LIST['image']);
    $imagDirPath->add($_list);
    if (is_dir($imagDirPath->get()) && findFileName($_list)) {
        $imgDirList[] = $_list;
    }
}

// tokenクラスをセット
$selectToken = new Private\Important\Token('select-token', $session, true);
$uploadToken = new Private\Important\Token('upload-token', $session, true);
$viewToken = new Private\Important\Token('view-token', $session, true);

?>
<form method='POST' action='./'>
    対象のページを選択
    <select name="image-type" class="image-type">
        <?php
        foreach ($imgDirList as $_list) {
            echo "<option value=\"{$_list}\">". $_list. "</option>";
        }

    ?>
    </select>
    ： <span class='view-image-type'><?=getImagePageName()?></span>
    <?php $selectToken->set(); ?>
</form>
<div class='select-notice'></div>
<div class='view-image'>
    <form method='POST'
        action='./<?= $page != null ? "?page={$page}" : "" ?>'>
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
    </form>
</div>
<form enctype="multipart/form-data"
    action="./subdirectory/notAutoInclude/server.php<?= $page != null ? "?page={$page}" : "" ?>"
    method='POST'>
    <?php $uploadToken->set(); ?>
    <input type='file' name='all-files[]' multiple /> <button type='submit' class='fileButton'>送信</button>
    <span>
        <div class='footer_char'>※同じ名前のファイルは複数保存されず、上書きされます。</div>
        <div class='notice'><?= $session->onlyView('notice'); ?>
        </div>
        <div class='warning'><?= $session->onlyView('warning'); ?>
        </div>
        <div class='success'><?= $session->onlyView('success'); ?>
        </div>
        <?php
            if (isset($updatePage) && is_numeric($updatePage)) {
                echo "<div class='page-moved'>{$updatePage}ページに移動しました。</div>";
            }
        ?>
        <!-- <input type='checkbox' name='deb_flg' value=1 /> デバッグモード -->
    </span>
</form>

<form class='pageForm'
    action="./subdirectory/notAutoInclude/server.php?mode=edit<?= $page !== null ? "&page={$page}" : "" ?>"
    method='POST'>
    <?php
        readImage();
    ?>
    <?php $viewToken->set(); ?>
    <p>
        <button type='submit' name='delete'>チェックした画像を削除する</button>
        <button type='submit' name='copy'>チェックした画像をコピーする</button>
        <input type='hidden' class='copy-image-name' name='copy-image-name' />
    </p>
    <a href='./delete'>削除したデータ</a>
</form>