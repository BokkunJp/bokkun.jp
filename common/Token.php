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
    private bool $isTokenSet;
    private $session;
    private ?array $posts;

    use \CommonTrait;

    /**
     * Token関連のセッション操作を行う
     *
     * @param string $tokenName              トークン名
     * @param $session                       操作対象のセッション
     * @param boolean $isTokenSet           トークンを設置するかどうか
     */
    function __construct(string $tokenName, $session, bool $isTokenSet = false)
    {
        $this->tokenName = $tokenName;
        $this->session = $session;
        $this->tokenValue = (string)$session->read($tokenName);
        $this->tokenPost = \Common\Important\Setting::getPost($this->tokenName);
        $this->isTokenSet = $isTokenSet;
    }

    /**
     * create
     *
     * @param string $message 平文
     * @return string
     */
    protected function create(string $message): string
    {
        return $this->tokenValue = hash_hmac("sha3-512", $message, 'csrf-key');
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
        $this->tokenValue = $this->create($this->createRandom(SECURITY_LENG)). '-'. $this->create($this->createRandom(SECURITY_LENG));

        if ($this->isTokenSet) {
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
     * @return bool
     */
    public function check(): bool
    {
        $sessionData = $this->session->read($this->tokenName);
        $csrfChkFlg = true;

        if (
            !is_string($sessionData)
            || !is_string($this->tokenPost)
            || !hash_equals($sessionData, $this->tokenPost)
        ) {
            $csrfChkFlg = false;
        }

        return $csrfChkFlg;
    }

    /**
     * getTag
     *
     * トークンのHTML要素の取得または出力
     * 
     * @param boolean $getFlg
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
    private function debug(): void
    {
            echo 'post: ' . $this->tokenPost . '<br/>';
            echo 'session: ' . $this->session->read($this->tokenName) . '<br/><br/>';
    }
}
