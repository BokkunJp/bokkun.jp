<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
if (!isset($session)) {
  $session = new PublicSetting\Session();
}

// ページ数取得
$page = PublicSetting\Setting::GetQuery('page');

// 更新用ページに関する処理
$update_page = PublicSetting\Setting::GetPost('update_page');

if (isset($update_page) && is_numeric($update_page)) {
  echo "{$update_page}ページに移動しました。";
}
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

  <form class='pageForm' method="POST">
  <?php
  ReadImage(1);
  ?>
  <input type='hidden' name='token' value="<?= $token = MakeToken(); ?>" />
</form>

<?php
SetToken($token);
