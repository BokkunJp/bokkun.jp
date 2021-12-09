<?php
$session = $_SESSION;
if (!isset($session)) {
  session_start();
}

/*
// トークン作成
function MakeToken() {
    return CreateRandom(SECURITY_LENG);
}

// トークンセット
function SetToken() {
    $post = CommonSetting\GetPost();
    $get = CommonSetting\GetQuery();
    $session = new CommonSetting\Session();
    // \\\\ Setting.phpに移動検討 ////

    $token = MakeToken();
    $session->Add('token', $token);

    session_regenerate_id();

}
*/

?>
<div class="thanks">ご閲覧ありがとうございます！</div>
まだ何もありせんが、少しずつコンテンツを増やす予定です！！<br />
<small>
  スマートフォン・タブレットからのアクセス非推奨。<br />
  また、ガラケーは未対応です。
</small>
<div>
  <h1>サイト一覧</h1>
  <ul>
    <li>PHP</li>
  </ul>
  <ol>
    <?php
    $notList = ['.', '..', '.htaccess', 'client', 'common', 'template', 'template_base', 'custom', 'custom_base', 'public', 'cake', 'private', 'php.yml'];
    $dirList = scandir(__DIR__ . '/');
    $notList = AddList($notList, $dirList, '.', 1);
    $notList = AddList($notList, $dirList, '_', 1);

    foreach ($dirList as $index => $_dir) {
      if (!SearchData($_dir, $notList) && !preg_match("/\.php$|\.html$/", $_dir)) {
        echo "<li><a href=\"./$_dir/\" target=\"_new\">$_dir</a></li>";
      }
    }
    ?>
  </ol>
  <ul>
    <li>CakePHP4</li>
  </ul>
  <ol>
    <?php
    $notList = [
      '.', '..', 'cell', 'element', 'email', 'Error', 'layout',
      'Pages'
    ];
    $dirList = scandir(dirname(__DIR__) . '/cake.bokkun.jp/templates');
    $notList = AddList($notList, $dirList, '_', 1);
    foreach ($dirList as $index => $_dir) {
      if (!SearchData($_dir, $notList)) {
        echo "<li><a href=\"https://cake.{$domain}/{$_dir}/\" target=\"_blank\">$_dir</a></li>";
      }
    }
    ?>
  </ol>
  <?php require_once "history.php" ?>
  </div>