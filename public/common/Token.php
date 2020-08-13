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
    $session->Write('token', $token);
}

/**
 * CheckToken
 * トークンチェック
 *
 * @param  string $tokenName
 * @param  boolean $chkFlg
 *
 * @return bool
 */
function CheckToken($tokenName = 'token', $chkFlg=false)
{
    $checkToken = CheckSession($tokenName, $chkFlg);

    return $checkToken;
}
