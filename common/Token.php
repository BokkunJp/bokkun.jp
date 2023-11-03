<?php
namespace common;

$tokenPath = new \Path(COMMON_DIR);
$tokenPath->AddArray(['Trait', 'CommonTrait.php']);
require_once $tokenPath->Get();

use common\Setting;

/////////////// CSRF対策 ////////////////////////
/**
 * Tokenを操作するためのクラス
 */
class Token {

    private string $tokenName, $tokenValue, $tokenPost;
    private bool $checkSetting;
    private ?\common\Session $session;
    private ?array $posts;

    use \CommonTrait;

    /**
     * Token関連のセッション操作を行う
     *
     * @param string $tokenName               トークン名
     * @param \common\Session $session        操作対象のセッション
     * @param boolean $checkSetting           トークンを設置するかどうか
     */
    function __construct(string $tokenName, \common\Session $session, bool $checkSetting = false)
    {
        $this->tokenName = $tokenName;
        $this->session = $session;
        $this->tokenValue = (string)$session->Read($tokenName);
        $this->tokenPost = Setting::GetPost($this->tokenName);
        $this->checkSetting = $checkSetting;
    }
    /**
     * Set
     * トークンの生成・上書き
     *
     * @return string
     */
    public function Set(): void
    {
        // トークンを設定(上書き)
        $this->tokenValue = $this->createRandom(SECURITY_LENG) . '-' . $this->createRandom(SECURITY_LENG, "random_bytes");

        if ($this->checkSetting) {
            $this->GetTag();
        }

        $this->session->Write($this->tokenName, $this->tokenValue);
    }

    /**
     * Check
     *
     * Post値とトークンのチェック
     * @param  string $tokenName
     * @param  boolean $chkFlg
     *
     * @return bool
     */
    public function Check(): bool
    {
        if (!isset($this->tokenPost)
            || is_null($this->tokenPost)
            || $this->tokenPost === false
            || is_null($this->session->Read($this->tokenName))
            || !hash_equals($this->session->Read($this->tokenName), $this->tokenPost)
        ) {
            return false;
        }

        return true;
    }

    /**
     * GetTag
     *
     * トークンのHTML要素の取得または出力
     * @param boolean $getFlg
     *
     * @return null|string
     */
    public function GetTag(bool $getFlg = false): ?string
    {
        if ($getFlg === false) {
        echo "<input type='hidden' name={$this->tokenName} value='{$this->tokenValue}' />";
        $result = null;
        } else {
            $result = "<input type='hidden' name={$this->tokenName} value='{$this->tokenValue}' />";
        }

        return $result;
    }

    /**
     * DebugToken
     * デバッグ用
     *
     * @return void
     */
    public function DebugToken(): void
    {
            echo 'post: ' . $this->tokenPost . '<br/>';
            echo 'session: ' . $this->session->Read($this->tokenName) . '<br/><br/>';
    }
}
