<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$session = new Public\Important\Session();
$dbInputToken = new Public\Important\Token('db-input-token', $session, true);
$dbSearchToken = new Public\Important\Token('db-search-token', $session, true);
?>
<form method='POST' action='./' name='Input'>
    <h2>入力・編集フォーム</h2>
    <div class='db-exec'><?= $session->onlyView('db-exec'); ?></div>
    <div class='warning'><?= $session->onlyView('db-error'); ?></div>
    番号：<input type='text' name='edit-id' /> <br />
    コンテンツ：<input type='text' name='edit-contents' />
    <?php $dbInputToken->set(); ?><br />
    <button>送信する</button>
    <button name='delete-num' value='On'>指定した番号のデータを削除する</button>
    <button name='delete-all' value='On'>すべてのデータを削除する</button>
</form>

<form method='POST' action='./' name='Search'>
    <h2>検索フォーム</h2>
    番号：<input type='text' name='search-id' /> <br />
    コンテンツ：<input type='text' name='search-contents' />
    <?php $dbSearchToken->set(); ?><br />
    <button>検索する</button>
</form>

<form method='POST' action='./' name='output'>
    <h2>出力フォーム</h2>
</form>
