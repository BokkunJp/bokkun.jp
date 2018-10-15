<?php
$session = $_SESSION;
if (!isset($session)) {
    session_start();
}

/*
// トークン作成
function MakeToken() {
    return sha1(session_id());
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
        まだ何もありせんが、少しずつコンテンツを増やす予定です！！<br/>
        <small>
                スマートフォン・タブレットからのアクセス非推奨。<br/>
                また、ガラケーは未対応です。
        </small>

			<nav>
                <h1>サイト一覧</h1>
                <ul>
                  <li>PHP</li>
                </ul>
                <ol>
                <?php
                        $notList = ['.', '..', '.htaccess', 'index.php', 'client', 'common',
                        'template', 'template_base', 'custom', 'custom_base'];
                        $dirList = scandir(__DIR__. '/public');
                        $notList = ListAdd($notList, $dirList, '_');

                        foreach ($dirList as $index => $_dir) {
                            if (!in_array($_dir, $notList)) {
                                echo "<li><a href=\"./public/$_dir/\" target=\"_new\">$_dir</a></li>";
                            }
                        }
                ?>
              </ol>
              <ul>
                <li>CakePHP3</li>
              </ul>
              <ol>
                <?php
                        $notList = ['.', '..', 'Cell', 'Element', 'Email', 'Error', 'Layout',
                        'Pages'];
                        $dirList = scandir(__DIR__. '/cake/src/Template');
                        $notList = ListAdd($notList, $dirList, '_');

                        foreach ($dirList as $index => $_dir) {
                            if (!in_array($_dir, $notList)) {
                                echo "<li><a href=\"./cake/$_dir/\" target=\"_blank\">$_dir</a></li>";
                            }
                        }
                        ?>
              </ol>
              <ul>
                <li>Zend Framework2</li>
              </ul>
              <ol>
                      <?php
                        $notList = ['.', '..', 'index'];
                        $dirList = scandir(__DIR__. '/cg/module/Application/view/application');
                        $notList = ListAdd($notList, $dirList, '_');
                        foreach ($dirList as $index => $_dir) {
                            if (!in_array($_dir, $notList)) {
                                echo "<li><a href=\"./cg/public/cgApps/$_dir/\" target=\"_blank\">$_dir</a></li>";
                            }
                        }
                    ?>
				</ol>
				<?php require_once "history.php" ?>
      </nav>
