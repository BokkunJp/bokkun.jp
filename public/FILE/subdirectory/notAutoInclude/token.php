<?php
$post = PublicSetting\GetPost();
$get = PublicSetting\GetQuery();

// トークンチェック
function CheckToken($tokenName='token', $errMessage='不正な値が送信されました。<br/>', $pageMessage='<br /><a href=\'javascript:location.href = location;\'>ファイルページへ戻る</a>') {
    $post = PublicSetting\GetPost();
    $get = PublicSetting\GetQuery();
    $session = new PublicSetting\Session();
    if (isset($post['deb_flg'])) {
        echo 'デバッグ用<br/>';
        echo 'post: '. $post[$tokenName]. '<br/>';
        echo 'session: '. $session->Read($tokenName). '<br/><br/>';
    }
    if (!isset($post[$tokenName]) || $post[$tokenName] !== $session->Read($tokenName)) {
        echo $errMessage;
        echo $pageMessage;
        echo '        <div>';
        require_once __DIR__. '/Layout/footer.php';
        echo '</div>
        </body>
    </html>';
        die;
    }

}
