<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
if (!isset($session)) {
  $session = new PublicSetting\Session();
}
$posts = PublicSetting\Setting::GetPosts();

// ページ数取得
$page = PublicSetting\Setting::GetQuery('page');
?>
<div class='view-image'>
  <form method='POST' action='./<?= $page != null ? "?page={$page}" : "" ?>'>
    <select name='image-value'>
      <?php
      for ($i = 1; $i <= MAX_VIEW; $i++) {
        $_val = $i * PAGING;
        echo "<option value={$_val}>" . $_val . "</option>";
      }
      ?>
    </select>
    <span><button value='editView'>表示枚数の変更</button>
      <span>現在の表示枚数:<?= GetPaging(); ?>枚</span>
  </form>
</div>
<form>
  <?php
  ReadImage(1);
  ?>
  <input type='hidden' name='token' value="<?= $token = MakeToken(); ?>" />
</form>

<?php
SetToken($token);
