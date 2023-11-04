<?php
namespace Common\Important;

$tokenPath = new \Path(COMMON_DIR);
$tokenPath->addArray(['Trait', 'CommonTrait.php']);
require_once $tokenPath->get();

use common\Setting;

/////////////// CSRF対策 ////////////////////////
/**
 * Tokenを操作するためのクラス
 */
class Token {

    private string $tokenName, $tokenValue, $tokenPost;
    private bool $checkSetting;
    private ?\Common\Important\Session $session;
    private ?array $posts;

    use \CommonTrait;

    /**
     * Token関連のセッション操作を行う
     *
     * @param string $tokenName               トークン名
     * @param \common\Important\Session $session        操作対象のセッション
     * @param boolean $checkSetting           トークンを設置するかどうか
     */
    function __construct(string $tokenName, \Common\Important\Session $session, bool $checkSetting = false)
    {
        $this->tokenName = $tokenName;
        $this->session = $session;
        $this->tokenValue = (string)$session->read($tokenName);
        $this->tokenPost = \Common\Important\Setting::getPost($this->tokenName);
        $this->checkSetting = $checkSetting;
    }
    /**
     * Set
     * トークンの生成・上書き
     *
     * @return string
     */
    public function set(): void
    {
        // トークンを設定(上書き)
        $this->tokenValue = $this->createRandom(SECURITY_LENG) . '-' . $this->createRandom(SECURITY_LENG, "random_bytes");

        if ($this->checkSetting) {
            $this->getTag();
        }

        $this->session->write($this->tokenName, $this->tokenValue);
    }

    /**
     * check
     *
     * Post値とトークンのチェック
     * @param  string $tokenName
     * @param  boolean $chkFlg
     *
     * @return bool
     */
    public function check(): bool
    {
        if (!isset($this->tokenPost)
            || is_null($this->tokenPost)
            || $this->tokenPost === false
            || is_null($this->session->read($this->tokenName))
            || !hash_equals($this->session->read($this->tokenName), $this->tokenPost)
        ) {
            return false;
        }

        return true;
    }

    /**
     * getTag
     *
     * トークンのHTML要素の取得または出力
     * @param boolean $getFlg
     *
     * @return null|string
     */
    public function getTag(bool $getFlg = false): ?string
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
     * debug
     * デバッグ用
     *
     * @return void
     */
    public function debug(): void
    {
            echo 'post: ' . $this->tokenPost . '<br/>';
            echo 'session: ' . $this->session->read($this->tokenName) . '<br/><br/>';
    }
}
