<?php
namespace Common;

require_once AddPath(AddPath(COMMON_DIR, 'Trait'), 'CommonTrait.php', false);

use commonSetting\Setting;

/////////////// CSRF対策 ////////////////////////
/**
 * Tokenを操作するためのクラス
 */
class Token {

    private string $tokenName, $tokenValue;
    private bool $checkSetting;
    private ?\CommonSetting\Session $session;
    private ?array $posts;

    use \CommonTrait;

    /**
     * Token関連のセッション操作を行う
     *
     * @param string $tokenName                トークン名
     * @param \CommonSetting\Session $session 操作対象のセッション
     * @param boolean $checkSetting           トークンを設置するかどうか
     */
    function __construct(string $tokenName, \CommonSetting\Session $session, bool $checkSetting = false)
    {
        $this->tokenName = $tokenName;
        $this->session = $session;
        $this->posts = Setting::GetPosts();
        $this->checkSetting = $checkSetting;
    }
    /**
     * MakeToken
     * トークン作成
     *
     * @return string
     */
    public function SetToken(): void
    {
        $this->tokenValue = $this->CreateRandom(SECURITY_LENG) . '-' . $this->CreateRandom(SECURITY_LENG, "random_bytes");

        if ($this->checkSetting) {
            $this->GetTokenTag();
        }

        $this->session->Write($this->tokenName, $this->tokenValue);
    }

    /**
     * CheckToken
     *
     * Post値とセッション値のチェック
     *
     *
     * @param  string $tokenName
     * @param  boolean $chkFlg
     *
     * @return bool
     */
    public function CheckToken(): bool
    {
        if (is_null($this->posts[$this->tokenName])
            || $this->posts[$this->tokenName] === false
            || is_null($this->session->Read($this->tokenName))
            || !hash_equals($this->session->Read($this->tokenName), $this->posts[$this->tokenName])
        ) {
            return false;
        }

        return true;
    }

    public function GetTokenTag()
    {
        echo "<input type='hidden' name={$this->tokenName} value='{$this->tokenValue}' />";
    }

    /**
     * DebugToken
     * デバッグ用
     *
     * @return void
     */
    public function DebugToken()
    {
            echo 'post: ' . $this->posts[$this->tokenName] . '<br/>';
            echo 'session: ' . $$this->session->Read($this->tokenName) . '<br/><br/>';
    }
}
