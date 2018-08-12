<?php
require_once(__DIR__. "/common/Setting.php");
require_once 'common.php';
if (empty($session['admin'])) {
    $session['admin'] = array();
}

if (empty($adminURL)) {
    $adminURL = explode('/', GetSelf_Admin());
    $session['admin']['adminURL'] = $adminURL;
} else {
    $adminURL = $session['admin']['adminURL'];
    unset($session['admin']['adminURL']);
}
SessionWrite($session);
$page = MovePage();
$referer = end($adminURL);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <title>セキュアなページ</title>
        <script src="<?php echo $url; ?>/js/JavaScript/time/time.js"></script>
        <script src="<?php echo $url; ?>/js/JavaScript/time/realtime.js"></script>
        <script src="<?php echo $url; ?>/js/JavaScript/time/convert.js"></script>

    </head>
    <body>
        ご訪問ありがとうございます。<br/>
        管理ページに進まれる場合はID・パスワードを入力してください。<br/>
        詳細はサイト管理人にお問い合わせください(トップページのtwitter参照)。<br/>
        <form method='POST' action='<?php echo $private; ?>secure.php'>
            <p>ID <input type='text' name='id' maxLength='20' /></p>
            <p>PASS <input type='password' name='pass' maxLength='20' /></p>
            <button type='submit'>送信</button>
        </form>
    </body>
</html>
<?php
    if (!empty($post)) {
        $adminAuth = ($post['id'] === 'admin' && $post['pass'] === 'bokkunAdmin777');
        $guestAuth = ($post['id'] === 'guest' && $post['pass'] === 'guestPass1234');
    } else {
        $adminAuth = $guestAuth = null;
    }
    if (!($adminAuth) &&
    !($guestAuth)) {
        if (!empty($post)) {
            echo '<p>IDまたはパスワードが違います。</p>';
            unset($session['admin']);
        }
        exit; 
    } else {
        $session['admin']['secure'] = true;
        SessionWrite($session);
        echo "<p>認証に成功しました。以下のリンクから$page[message]へ移動できます。<br/>";
        echo "<a href='$url/private/$page[URL]'>$page[message]へ</a></p>";
    }
 ?>