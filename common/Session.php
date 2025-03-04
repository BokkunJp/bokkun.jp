<?php
namespace Common\Important;

// Trait読み込み
$sessionTraitPath = new \Path(__DIR__);
$sessionTraitPath->addArray(['Trait', 'SessionTrait.php']);
require_once $sessionTraitPath->get();
$sessionTraitPath = new \Path(__DIR__);
$sessionTraitPath->addArray(['Trait', 'CommonTrait.php']);
require_once $sessionTraitPath->get();

// セッションクラス
class Session
{
    use \CommonTrait;
    use \SessionTrait;

    private array $session;
    private ?string $sessionName = null, $type = null;
    private const ACCESSIBLE_TYPE = ['private', 'public'];

    /**
     * construct
     * 
     * セッション名が指定されている場合はそのセッションを取得、指定されていない場合は全体のセッションを取得
     *
     * @param string|null $sessionName
     */
    public function __construct(?string $sessionName = null)
    {
        if (!is_null($sessionName)) {
            $this->sessionName = $sessionName;
            $this->read($this->sessionName);
        } else {
            $this->read();
        }
    }

    /**
     * start
     * 
     * セッション開始
     *
     * @return void
     */
    protected function start(): void
    {
        if (!isset($_SESSION) || session_status() === PHP_SESSION_DISABLED) {
            if (PHP_OS === 'WINNT') {
                $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'), 2). "/var/session/";
                if (!is_dir($sessionDir)) {
                    mkdir($sessionDir, 0755, true);
                    $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/session/";
                    if (!is_dir($sessionDir)) {
                        mkdir($sessionDir, 0755, true);
                    } else {
                        $sessionDir .= '/session/';
                    }
                }
                session_save_path($sessionDir);
            }
            session_start();
            session_regenerate_id();
        }
    }

    /**
     * setType
     * 
     * タイプをセット
     *
     * @param string $type
     * 
     * @return void
     */
    protected function setType(string $type): void
    {
        if ($this->searchData($type, self::ACCESSIBLE_TYPE)) {
            $this->type = $type;
        }
    }

    /**
     * getType
     * 
     * タイプを取得
     *
     * @return ?string
     */
    protected function getType(): ?string
    {
        return $this->type;
    }

    /**
     * setSessionName
     * 
     * セッション名をセット
     *
     * @param string|null $sessionName
     * @param string|null $type
     * 
     * @return void
     */
    protected function setSessionName(?string $sessionName): void
    {

        $this->sessionName = $sessionName;
    }

    /**
     * setSessionName
     * 
     * セッション名をセット
     *
     * @return string
     */
    protected function getSessionName(): string
    {

        return $this->sessionName;
    }

    /**
     * load
     * 
     * スーパーグローバル変数のセッション配列から既存のセッションデータを取得する
     *
     * @param string|null $elm
     * @return mixed
     */
    private function load(?string $elm = null): array
    {
        $ret = null;
        $type = $this->getType();

        if (!is_null($type) && isset($_SESSION[$type])) {
            $session = $_SESSION[$type];
        } elseif (is_null($type)) {
            $session = $_SESSION;
        } else {
            $session = null;
        }

        if ($elm && isset($session[$elm])) {
            $ret = $session[$elm];
        } elseif (is_null($elm)) {
            $ret = $session;
        }

        // 配列以外の不正なデータが入った場合はnullに
        if (!is_array($ret)) {
            $ret = [];
        }

        return $ret;
    }

    /**
     * save
     * 
     * スーパーグローバル変数のセッション配列へデータを保存
     *
     * @return void
     */
    private function save(): void
    {
        $type = $this->getType();

        if ($type) {
            // タイプごとのセッション管理
            if ($this->sessionName) {
                $_SESSION[$type][$this->sessionName] = $this->session;
            } else {
                $_SESSION[$type] = array_merge($_SESSION[$type] ?? [], $this->session);
            }
        } else {
            // タイプがない場合、全体のセッションにマージ
            $_SESSION = array_merge($_SESSION, $this->session);
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
        if (!empty($handle) && method_exists($this, $handle)) {
            $this->$handle();
        }
        $this->add($tag, $message);

        $this->save();
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
        $writeProcess = fn($childData) => $childData ?? [$childId => $data];

        $childSessionData = $this->commonProcessArray($parentId, $childId, $writeProcess);

        $sessionName = $this->getSessionName();

        // 親データの構造を準備
        $parentResultData = [
            $childId => $data,
        ];

        $type = $this->getType();
        if (!is_null($type)) {
            // タイプがある場合、既存のセッションデータを取得
            $existingData = $this->load($sessionName);

            // 既存の親データと比較し、異なる場合のみ更新
            if (isset($existingData[$parentId]) && $existingData[$parentId] !== [$childId => $childSessionData]) {
                $this->write($parentId, array_merge($existingData[$parentId], $parentResultData));
            } else {
                // 元データがない場合は新たに書き込み
                $this->write($parentId, [$childId => $data]);
            }
        } else {
            // タイプがない場合、トップレベルのセッションに保存
            $existingData = $this->load($parentId);
            if ($existingData !== [$childId => $childSessionData]) {
                $this->write($parentId, [$childId => $data]);
            }
        }
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
    public function read(string|int|Null $sessionElm = null): mixed
    {
        $this->start();

        $returnData = false;

        // 要素名が指定されている場合はセッション内の要素名のデータを、指定されていない場合はセッションそのものを取得
        if (isset($this->sessionName)) {
            $this->session = $this->load($this->sessionName);
        } else {
            $this->session = $this->load();
        }

        // データ取得
        if (isset($sessionElm) && isset($this->session[$sessionElm])) {
            $returnData = $this->session[$sessionElm];
        } elseif (is_null($sessionElm)) {
            $returnData = $this->session;
        }

        return $returnData;
    }

    /**
     * delete
     *
     * セッションの削除
     *
     * @param string|int $sessionElm 削除する要素名
     *
     * @return void
     */
    public function delete(string|int|Null $sessionElm = null): void
    {
        if (!isset($_SESSION)) {
            user_error('Session is already deleted.');
            exit;
        }

        // セッションプロパティを削除
        if (!is_null($sessionElm) && isset($this->session[$sessionElm])) {
            unset($this->session[$sessionElm]);
        } else {
            $this->finaryDestroy();
            unset($this->session);
            $this->session = [];
        }

        // セッション配列側を更新
        if (!is_null($this->type) && !is_null($this->sessionName)) {
            unset($_SESSION[$this->type][$this->sessionName]);
            $_SESSION[$this->type][$this->sessionName] = $this->session;
        } else if (!is_null($this->type)) {
            unset($_SESSION[$this->type]);
            $_SESSION[$this->type] = $this->session;
        } else {
            unset($_SESSION[$this->sessionName]);
            $_SESSION[$this->sessionName] = $this->session;
        }
    }

    /**
     * judge
     *
     * セッション内のデータの有無を判定
     *
     * @param string|int $id 判定対象のセッションID
     *
     * @return bool
     */
    public function judge(string|int $id): bool
    {
        // セッションをセット
        if (isset($this->sessionName) || !is_null($this->sessionName)) {
            $nowSession = $this->load($this->sessionName);
        } else {
            $nowSession = $this->session;
        }

        // セッションデータの判定
        return isset($nowSession[$id]);
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
    public function view(mixed $id = null): void
    {
        if ($id === null  || !is_array($this->session)) {
            var_dump($this->session);
        } elseif ($this->judge($id) === true) {
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
    public function onlyView(string|int $tag): void
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
    protected function finaryDestroy(): void
    {

        // Note: セッション情報だけでなくセッションを破壊する。
        session_unset();


        // 最終的に、セッションを破壊する
        session_destroy();
    }
}
