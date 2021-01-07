<!-- デザイン用ファイル (PHPで処理を記述)-->
<form method='POST' action='./' name='Input'>
    <h2>入力・編集フォーム</h2>
    <div class='db-exec'><?= $sess->OnlyView('db-exec'); ?></div>
    番号：<input type='text' name='edit-id' /> <br/>
    コンテンツ：<input type='text' name='edit-contents' />
    <input type='hidden' name='token' value="<?= $token = MakeToken(); ?>" /><br/>
    <button>送信する</button>
    <button name='delete-num' value='On' >指定した番号のデータを削除する</button>
    <button name='delete-all' value='On'>すべてのデータを削除する</button>
</form>

<form method='POST' action='./' name='Search'>
<h2>検索フォーム</h2>
    番号：<input type='text' name='search-id' /> <br/>
    コンテンツ：<input type='text' name='search-contents' />
    <input type='hidden' name='searchToken' value="<?= $searchToken = MakeToken(); ?>" /><br/>
    <button>検索する</button>
</form>

<form method='POST' action='./' name='Output'>
<h2>出力フォーム</h2>
</form>
<?php
if (isset($token)) {
    SetToken($token);
}

if (isset($searchToken)) {
    SetToken($searchToken, 'searchToken');
}
