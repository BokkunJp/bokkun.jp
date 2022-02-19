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
function SetToken($token = null, $tokenName = 'token')
{
    $session = new PublicSetting\Session();

    if (!isset($token)) {
        $token = MakeToken();
    }
    $session->Write($tokenName, $token);
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
function CheckToken($tokenName = 'token')
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
        return false;
    }

    return true;
}
