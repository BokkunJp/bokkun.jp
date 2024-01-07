<?php
/**
 * Path
 *
 * Pathを設定・追加するためのクラス。
 */

class Path {
    public ?\Path $prev = null;
    private string $path;
    private ?bool $lastSeparator;
    private array $last;

    function __construct(
        \Path|string $path,
        private string $separator = '/',
    ) {

        if ($path instanceof \Path) {
            $path = $path->get();
        }

        $next = '';
        if (str_ends_with($path, $this->separator) === false) {
            $next = $this->separator;
        }

        $this->set($path. $next);
        $this->setType("directory");

        $this->separate();
    }

    /**
     * SetType
     *
     * タイプ(ファイル,ディレクトリ)のセットを行う
     *
     * @param ?string $type タイプ(file or dhirectory or null)
     *
     * @return void
     */
    private function setType(?string $type = null): void
    {
        // タイプ指定あり
        if (!is_null($type)) {
            switch (strtolower($type)) {
                case 'file':
                    $this->lastSeparator = false;
                    break;
                default:
                    $this->lastSeparator = true;
                    break;
            }
        } else {
            // タイプ指定なし(判定を反転させる)
            $this->lastSeparator = !$this->lastSeparator;
        }
    }

    /**
     * SetPathEnd
     *
     * タイプのフラグをファイル用に設定する
     *
     * @return void
     */
    public function setPathEnd(): void
    {
        $this->setType('file');
    }

    /**
     * editSeparator
     *
     * セパレータを入れ替える
     *
     * @param string $separatior セパレータ
     *
     * @return void
     */
    public function editSeparator(string $separatior): void
    {
        $this->set(str_replace($this->separator, $separatior, $this->path));
        $this->separator = $separatior;
    }

    /**
     * set
     *
     * パスをセットする
     *
     * @param string $path
     *
     * @return void
     */
    private function set(string $path = ""): void
    {
        $this->path = $path;
    }

    /**
     * Get
     *
     * パスを取得する。
     *
     * @return string
     */
    public function get(): string
    {
        return $this->path;
    }

    /**
     * getInitial
     *
     * 加工前のパスを取得する。
     *
     * @return string
     */
    public function getInitial(): string
    {
        return $this->prev->path;
    }

    /**
     * add
     *
     * パスを追記する。
     *
     * @param string $addPath
     *
     * @return ?string
     */
    public function add(
        string $addPath,
        bool $saveFlg = true,
    ): ?string
    {
        if ($this->lastSeparator == true) {
            $last = $this->separator;
        } else {
            $last = '';
        }

        $path = htmlspecialchars($this->path. $addPath. $last);    // パスの結合＋XSS対策

        if ($saveFlg) {
            $this->set($path);
            $this->separate();

            return null;
        } else {
            return $path;
        }
    }

    /**
     * addArray
     *
     * 配列に登録したパスをまとめてセットする。
     *
     * @param array $pathList
     * @param boolean $initialFlg
     *
     * @return void
     */
    public function addArray(array $pathList, bool $initialFlg = false): void
    {
        $oldSeparator = $this->lastSeparator;
        $this->setType("dicrectory");
        // 初期化フラグがオンの場合はパスを空文字で初期化
        if ($initialFlg) {
            $this->set("");
        }

        foreach ($pathList as $path) {
            if ($path === end($pathList)) {
                $this->setPathEnd();
            }
            $this->add($path);
        }
        $this->setType($oldSeparator);

        $this->separate();
    }

    public function replace(int $str)
    {

    }

    /**
     * Back
     *
     * 指定した階層分パスを戻す。
     * (デフォルト指定は1。)
     *
     * @param integer $depth 階層
     * @return void
     */
    public function back(int $depth = 1)
    {
        $this->path = dirname($this->path, $depth);
    }

    public function existFile()
    {
        if (is_file($this->path)) {
            $this->back();
        }
    }

    private function separate()
    {
        if (is_file($this->path)) {
            $this->last['file'] = $this->path;
            $this->last['directory'] = basename(dirname($this->path));
        } else {
            $this->last['file'] = null;
            $this->last['directory'] = basename($this->path);
        }
    }

    public function export(string $type)
    {
        switch ($type) {
            case 'directory':
            case 'file':
                return $this->last[$type];
            break;
            default:
                return null;
        }
    }

    /**
     * reset
     *
     * クラスをリセットする。
     *
     * @return void
     */
    public function reset(): void
    {
        $this->beforeMarshal();
        $this->separator = $this->prev->separator;
        $this->lastSeparator = $this->prev->lastSeparator;
        $this->path = $this->prev->path;
        $this->prev = null;
    }

    /**
     * save
     *
     * 実行前処理
     * (パス変更前のクラスを保存する)
     *
     * @return void
     */
    private function beforeMarshal()
    {
        $this->prev = $this;
    }

    public function afterMarshal(?Callable  $callBack = null): void
    {
        // 処理
        if (!is_null($callBack)) {
            $callBack($this, $this->prev);

        }
        var_dump($this->prev);

        if (isset($this->prev)) {
            var_dump("階層移動");
            $this->prev->afterMarshal($callBack);
        }
    }

    public function outArray(): array
    {
        return explode($this->separator, $this->path);
    }

}
