<?php
namespace Common\Important;

// Trait読み込み
$sessionTraitPath = new \Path(__DIR__);
$sessionTraitPath->addArray(['Trait', 'SessionTrait.php']);
require_once $sessionTraitPath->get();

// セッションクラス (新)
class Session
{
    use \SessionTrait;

    private $init;
    private $session;

    public function __construct()
    {
        $this->read();
        $this->init = $this->session;
    }

    private function sessionStart()
    {
        if (!isset($_SESSION) || session_status() === PHP_SESSION_DISABLED) {
    if (PHP_OS === 'WINNT') {
        $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/";
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0755);
            $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/session/";
            if (!is_dir($sessionDir)) {
                mkdir($sessionDir, 0755);
            } else {
                $sessionDir .= '/session/';
            }
        }
        session_save_path($sessionDir);
    }
            session_start();
        } else {
            // セッションが定義されている場合は更新
            session_regenerate_id();
        }
    }

    /**
     * add
     *
     * セッションの追加
     *
     * @param [Strging] $sessionElm
     * @param [mixed] $sessionVal
     * @return void
     */
    private function add(string|int $sessionElm, mixed $sessionVal): void
    {
        $this->session[$sessionElm] = $sessionVal;
        $_SESSION[$sessionElm] = $this->session[$sessionElm];
    }

    /**
     * セッションの書き込み
     *
     * @param string|int $tag
     * @param mixed $message
     * @param ?string $handle
     *
     * @return void
     */
    public function write(string|int $tag, mixed $message, ?string $handle = null): void
    {
        if (!empty($handle)) {
            $this->$handle();
        }
        $this->add($tag, $message);
    }

    /**
     * writeArray
     *
     * セッション配列の更新
     *
     * @param string|int $parentId
     * @param string|int $childId
     * @param mixed $data
     *
     * @return void
     */
    public function writeArray(string|int $parentId, string|int $childId, mixed $data): void
    {
        $ret = null;

        $writeProccess = function ($childData) {
            if ($childData) {
                return $childData;
            }
        };

        $ret = $this->commonProcessArray($parentId, $childId, $writeProccess);

        if (empty($ret)) {
            $ret = [];
        }

        $ret[$childId] = $data;
        $this->write($parentId, $ret);
    }

    /**
     * read
     *
     * セッションの読み込み
     *
     * @param string|int $sessionElm
     *
     * @return mixed
     */
    public function read(string|int $sessionElm = null): mixed
    {
        if (!isset($_SESSION)) {
            $this->sessionStart();
        }

        $this->session = $_SESSION;

        if (isset($sessionElm)) {
            if (!isset($this->session[$sessionElm])) {
                return null;
            }
            return $this->session[$sessionElm];
        } else {
            return $this->session;
        }
    }

    /**
     * delete
     *
     * セッションの削除
     *
     * @param string|int $sessionElm
     *
     * @return void
     */
    public function delete(string|int $sessionElm = null): void
    {
        if (!isset($_SESSION)) {
            user_error('Session is already deleted.');
            exit;
        }
        if (isset($sessionElm)) {
            unset($this->session[$sessionElm]);
            $_SESSION = $this->session;
        } else {
            unset($this->session);
            $this->session = $this->init;
        }
    }

    /**
     * judge
     *
     * セッション判定用
     *
     * @param string|int $id
     *
     * @return mixed
     */
    public function judge(string|int $id = null): mixed
    {
        $ret = true;
        if (!isset($id)) {
            $ret = null;
        }

        if (!isset($this->session[$id])) {
            $ret = false;
        }

        return $ret;
    }

    /**
     * view
     *
     * セッション閲覧用
     *
     * @param ?mixed $id
     *
     * @return void
     */
    public function view(mixed $id = null)
    {
        $judge = $this->judge($id);
        if ($judge === null) {
            var_dump($this->session);
        } elseif ($judge === true) {
            print_r($this->session[$id]);
        }
    }

    /**
     * onlyView
     *
     * セッション参照後、該当のセッションを削除する
     *
     * @param string|int $tag
     * @return void
     */
    public function onlyView(string|int $tag)
    {
        if ($this->judge($tag) === true) {
            $this->view($tag);
            $this->delete($tag);
        }
    }

    /**
     * finaryDestroy
     *
     * セッションの完全な破棄
     *
     * @return void
     */
    public function finaryDestroy()
    {

        // Note: セッション情報だけでなくセッションを破壊する。
        session_unset();


        // 最終的に、セッションを破壊する
        session_destroy();
    }
}
