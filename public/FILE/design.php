<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
if (!isset($session)) {
  $session = new PublicSetting\Session();
}
?>
<form enctype="multipart/form-data" action='./FILE/subdirectory/notAutoInclude/server.php' method='POST'>
  <input type='hidden' name='token' value="<?= $token = MakeToken() ?>" />
  <input type='file' name='file' /> <button type='submit' class='fileButton'>送信</button>
  <span>
    <select>
      <?php
      for ($i = 1; $i <= 10; $i++) {
        echo '<option>' . $i * PAGING . '</option>';
      }
      ?>
    </select>
  </span>
  <div class='footer_char'>※同じ名前のファイルは複数保存されず、上書きされます。</div>
  <div class='notice'>
    <?php
    if ($session->Judge(('notice')) === true) {
      $session->View('notice');
      $session->Delete('notice');
    }
    ?>
  </div>
  <div class='warning'>
    <?php
    if ($session->Judge(('notice')) === true) {
      $session->View('notice');
      $session->Delete('notice');
    }
    ?>
  </div>

  <div class='success'>
    <?php
    if ($session->Judge(('success')) === true) {
      $session->View('success');
      $session->Delete('success');
    }
    ?>
  </div>

  <!-- <input type='checkbox' name='deb_flg' value=1 /> デバッグモード -->
</form>

<form action='./FILE/subdirectory/notAutoInclude/server.php?mode=del' method='POST'>
  <?php
  ReadImage(1);
  ?>
  <input type='hidden' name='token' value="<?= $token ?>" />
  <div>
    <button type='submit' name='delete'>チェックした画像を削除する</button>
  </div>
</form>

<?php
SetToken($token);
