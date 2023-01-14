<?php
namespace Common\Token;

require_once AddPath(AddPath(COMMON_DIR, 'Trait'), 'CommonTrait.php', false);

use CommonSetting\Session;
use commonSetting\Setting;

/////////////// CSRF対策 ////////////////////////
class Token extends Session {

    private string $tokenName, $tokenValue;
    private bool $checkSetting;
    private ?array $posts;

    use \CommonTrait;

    function __construct(string $tokenName, bool $checkSetting = false)
    {
        $this->tokenName = $tokenName;
        $this->posts = Setting::GetPosts();
        $this->checkSetting = $checkSetting;
    }
    /**
     * MakeToken
     * トークン作成
     *
     * @return string
     */
    private function SetToken(): void
    {
        $this->tokenValue = $this->CreateRandom(SECURITY_LENG) . '-' . $this->CreateRandom(SECURITY_LENG, "random_bytes");

        if ($this->checkSetting) {
            $this->GetTokenTag();
        }

        $this->Write($this->tokenName, $this->tokenValue);
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
    private function CheckToken(): bool
    {
        if (is_null($this->posts[$this->tokenName])
            || $this->posts[$this->tokenName] === false
            || is_null($this->Read($this->tokenName))
            || !hash_equals($this->Read($this->tokenName), $this->posts[$this->tokenName])
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
     * トークンチェック後、トークンを更新する。
     * (csrfTagがtrueの場合はトークンタグも設置する)
     *
     * @param string $tokenName トークン名
     * @param string $newToken 新規トークン (nullの場合は関数内で生成して設置)
     *
     * @return bool トークンチェック結果
     */
    public function UpdateToken(): bool
    {

        if (!isset($this->posts[$this->tokenName])) {
            // 新規にTokenを生成してセット
            $this->SetToken();

            $checkToken = true;
        } else {
            $checkToken = $this->CheckToken();

            // Token更新
            $this->SetToken();
            unset($this->posts[$this->tokenName]);
        }


        if (!$checkToken) {
            return true;
        }

        return false;
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
            echo 'session: ' . $this->Read($this->tokenName) . '<br/><br/>';
    }
}
