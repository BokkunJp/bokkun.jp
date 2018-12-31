<!-- デザイン用ファイル (PHPで処理を記述)-->
<form enctype="multipart/form-data" action='./FILE/subdirectory/notAutoInclude/server.php' method='POST'>
    <input type='hidden' name='token' value="<?= MakeToken() ?>" />
    <input type='file' name='file' /> <button type='submit' class='fileButton'>送信</button>
    <div class='footer_char'>※同じ名前のファイルは複数保存されず、上書きされます。</div> <br/>
    <!-- <input type='checkbox' name='deb_flg' value=1 /> デバッグモード -->
</form>

<form action='./FILE/subdirectory/notAutoInclude/server.php' method='POST'>
    <input type='hidden' name='token' value="<?= MakeToken() ?>" />

    <?php
    ReadImage(1);
    ?>

    <?php if (PublicSetting\Setting::GetPost('mode') === 'restore') : ?>
        <p>復元するファイル名：<input type='text' name='fileName' />
        <p><button type='submit'>画像を復元する</button></p>
        <input type='hidden' name='mode' value='restore' />
    </form>
    <form action='POST'>
        <input type='hidden' name='mode' value='delete' />
        <a href='javascript:location.href = location;'>削除モード</a>
    </form>
<?php else : ?>
    <p><button type='submit'>チェックした画像を削除する</button></p>
    <input type='hidden' name='mode' value='delete' />
    </form>
    <form action='POST'>
        <input type='hidden' name='mode' value='restore' />
        <a href='javascript:location.href = location;'>復元モード</a>
    </form>
<?php endif; ?>
<?php
SetToken();
