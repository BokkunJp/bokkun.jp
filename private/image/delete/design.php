<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
$session = new Private\Important\Session('image');

// ページ数取得
$page = Private\Important\Setting::getQuery('page');

// 更新用ページに関する処理
$updatePage = Private\Important\Setting::getPost('update_page');

// tokenクラスをセット
$selectToken = new Private\Important\Token('delete-select-token', $session, true);
$viewToken = new Private\Important\Token('delete-view-token', $session, true);

?>
<form method='POST' action='./'>
    対象のページ:<span class='view-image-type'><?=getImagePageName()?></span>
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
<div class='notice'><?= $session->onlyView('delete-page-notice'); ?></div>
<div class='success'><?= $session->onlyView('delete-page-success'); ?></div>
<form class='pageForm'
    action="./subdirectory/notAutoInclude/server.php?mode=edit<?= $page !== null ? "&page={$page}" : "" ?>"
    method='POST'>
    <?php
        readImage();
    ?>
    <?php $viewToken->set(); ?>
    <p>
        <button type='submit' name='restore'>チェックした画像を復元する</button>
        <button type='submit' name='delete'>チェックした画像を削除する</button>
    </p>
    <a href='../'>戻る</a>
</form>