<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// セッション開始
if (!isset($session)) {
    $session = new PublicSetting\Session();
}

// ページ数取得
$page = PublicSetting\Setting::GetQuery('page');

// 更新用ページに関する処理
$updatePage = PublicSetting\Setting::GetPost('update_page');

if (isset($updatePage) && is_numeric($updatePage)) {
    echo "{$updatePage}ページに移動しました。";
}

?>
<?php if ($readFlg === VIEW) :?>
  <div class='view-image'>
    <form
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
<?php endif;?>

  <form class='pageForm' method="POST">
  <?php
  ReadImage($readFlg);
  ?>
  <?php if ($readFlg === VIEW) :?>
    <input type='hidden' name='token' value="<?= $token = MakeToken(); ?>" />
  <?php endif;?>
</form>

<?php
if (isset($token)) {
  SetToken($token);
}
