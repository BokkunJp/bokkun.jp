<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
if (!isset($session)) {
  $session = new PrivateSetting\Session();
}
$posts = PrivateSetting\Setting::GetPosts();

// ページ数取得
$page = PrivateSetting\Setting::GetQuery('page');
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
  </form>
</div>
<form enctype="multipart/form-data" action="./subdirectory/notAutoInclude/server.php<?= $page != null ? "?page={$page}" : "" ?>" method='POST'>
  <input type='hidden' name='token' value="<?= $token = MakeToken() ?>" />
  <input type='file' name='all-files[]' multiple /> <button type='submit' class='fileButton'>送信</button>
  <span>
    <div class='footer_char'>※同じ名前のファイルは複数保存されず、上書きされます。</div>
    <div class='notice'><?= $session->OnlyView('notice'); ?></div>
    <div class='warning'><?= $session->OnlyView('warning'); ?></div>
    <div class='success'><?= $session->OnlyView('success'); ?></div>

    <!-- <input type='checkbox' name='deb_flg' value=1 /> デバッグモード -->
  </span>
</form>

<form action="./subdirectory/notAutoInclude/server.php?mode=del<?= $page !== null ? "&page={$page}" : "" ?>" method='POST'>
  <?php
  ReadImage(1);
  ?>
  <input type='hidden' name='token' value="<?= $token ?>" />
  <p>
    <button type='submit' name='delete'>チェックした画像を削除する</button>
  </p>
</form>
<?php
SetToken($token);
