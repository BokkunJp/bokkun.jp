<?php
/////////////// CSRF対策 ////////////////////////
// トークン作成
function MakeToken() {
    $token = sha1(@session_id()). '-'. md5(@session_id());

    return $token;
}

// トークンセット
function SetToken($token=null) {
    $session = new PublicSetting\Session();

    if (!isset($token)) {
        $token = MakeToken();
    }
    $session->Add('token', $token);

    @session_regenerate_id();

}

// トークンチェック
function CheckToken($tokenName='token', $errMessage='２度目以降のアクセスか、直接アクセスは禁止しています。<br/>', $pageMessage='<br /><a href=\'javascript:location.href = location;\'>前のページへ戻る</a>', $finishFlg=true) {
    $post = PublicSetting\GetPost();
    $get = PublicSetting\GetQuery();
    $session = new PublicSetting\Session();
    // $post['deb_flg'] = 1;
    if (isset($post['deb_flg'])) {
        echo 'デバッグ用<br/>';
        echo 'post: '. $post[$tokenName]. '<br/>';
        echo 'session: '. $session->Read($tokenName). '<br/><br/>';
    }
    if (!isset($post[$tokenName]) || $post[$tokenName] !== $session->Read($tokenName)) {
        echo $errMessage;
        echo $pageMessage;
        echo '        <div>';
         // 関数内で画面を完結させる場合はこちら
         if ($finishFlg === true) {
           require_once LAYOUT_DIR. '/footer.php';
           echo '</div>
             </body>
           </html>';
           die;
         } else {
           return false;
         }
    }

    return true;

}
