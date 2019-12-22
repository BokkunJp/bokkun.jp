<?php
/////////////// CSRF対策 ////////////////////////
/**
 * MakeToken
 * トークン作成
 *
 * @return string
 */
function MakeToken()
{
    $token = CreateRandom(SECURITY_LENG) . '-' . CreateRandom(SECURITY_LENG, "random_bytes");

    return $token;
}


/**
 * SetToken
 * トークンセット
 *
 * @param  mixed $token
 *
 * @return void
 */
function SetToken($token = null)
{
    $session = new PublicSetting\Session();

    if (!isset($token)) {
        $token = MakeToken();
    }
    $session->Add('token', $token);
}

/**
 * CheckToken
 * トークンチェック
 *
 * @param  mixed $tokenName
 * @param  mixed $errMessage
 * @param  mixed $pageMessage
 * @param  mixed $finishFlg
 *
 * @return bool
 */
function CheckToken($tokenName = 'token', $finishFlg = true, $errMessage = '２度目以降のアクセスか、直接アクセスは禁止しています。<br/>', $pageMessage = '<br /><a href=\'javascript:location.href = location;\'>前のページへ戻る</a>')
{
    $post = PublicSetting\Setting::GetPosts();
    $session = new PublicSetting\Session();

    // $post['deb_flg'] = 1;
    if (isset($post['deb_flg'])) {
        echo 'デバッグ用<br/>';
        echo 'post: ' . $post[$tokenName] . '<br/>';
        echo 'session: ' . $session->Read($tokenName) . '<br/><br/>';
    }
    if (!isset($post[$tokenName]) || $post[$tokenName] !== $session->Read($tokenName)) {
        // 画面を完結させる場合はこちら
        if ($finishFlg === true) {
            require_once PUBLIC_LAYOUT_DIR . '/header.php';
            echo $errMessage;
            echo $pageMessage;
            echo '        <div>';
            require_once PUBLIC_LAYOUT_DIR . '/footer.php';
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
