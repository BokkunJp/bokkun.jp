<?php
/**
 * Path
 *
 * Pathを設定・追加するためのクラス。
 */
require_once __DIR__. DIRECTORY_SEPARATOR. "Cache.php";

class Path {
    public ?\Path $prev = null;
    private string $path;
    private ?bool $lastSeparator;
    private ?\Cache $cache;
    private array $last;

    function __construct(
        \Path|string $path,
        private string $separator = DIRECTORY_SEPARATOR,
    ) {

        if ($path instanceof \Path) {
            $path = $path->Get();
        }

        $next = '';
        if (str_ends_with($path, $this->separator) === false) {
            $next = $this->separator;
        }

        $this->Set($path. $next);
        $this->SetType("directory");
        $this->cache = null;

        $this->Separate();
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
    private function SetType(?string $type = null): void
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
    public function SetPathEnd(): void
    {
        $this->SetType('file');
    }

    /**
     * EditSeparator
     *
     * セパレータを入れ替える
     *
     * @param string $separatior セパレータ
     *
     * @return void
     */
    public function EditSepartor(string $separatior): void
    {
        $this->Set(str_replace($this->separator, $separatior, $this->path));
        $this->separator = $separatior;
    }

    /**
     * Set
     *
     * パスをセットする
     *
     * @param string $path
     *
     * @return void
     */
    private function Set(string $path = ""): void
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
    public function Get(): string
    {
        return $this->path;
    }

    /**
     * GetInit
     *
     * 加工前のパスを取得する。
     *
     * @return string
     */
    public function GetInit(): string
    {
        return $this->prev->path;
    }

    /**
     * Add
     *
     * パスを追記する。
     *
     * @param string $addPath
     *
     * @return ?string
     */
    public function Add(
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
            $this->Set($path);
            $this->Separate();

            return null;
        } else {
            return $path;
        }
    }

    /**
     * AddArray
     *
     * 配列に登録したパスをまとめてセットする。
     *
     * @param array $pathList
     * @param boolean $initialFlg
     *
     * @return void
     */
    public function AddArray(array $pathList, bool $initialFlg = false): void
    {
        $oldSeparator = $this->lastSeparator;
        $this->SetType("dicrectory");
        // 初期化フラグがオンの場合はパスを空文字で初期化
        if ($initialFlg) {
            $this->Set("");
        }

        foreach ($pathList as $path) {
            if ($path === end($pathList)) {
                $this->SetPathEnd();
            }
            $this->Add($path);
        }
        $this->SetType($oldSeparator);

        $this->Separate();
    }

    public function Replace(int $str)
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
    public function Back(int $depth = 1)
    {
        $this->path = dirname($this->path, $depth);
    }

    public function ExistFile()
    {
        if (is_file($this->path)) {
            $this->Back();
        }
    }

    private function Separate()
    {
        if (is_file($this->path)) {
            $this->last['file'] = $this->path;
            $this->last['directory'] = basename(dirname($this->path));
        } else {
            $this->last['file'] = null;
            $this->last['directory'] = basename($this->path);
        }
    }

    public function Export(string $type)
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
     * Reset
     *
     * クラスをリセットする。
     *
     * @return void
     */
    public function Reset(): void
    {
        $this->BeforeMarshal();
        $this->separator = $this->prev->separator;
        $this->lastSeparator = $this->prev->lastSeparator;
        $this->path = $this->prev->path;
        $this->prev = null;
    }

    /**
     * Save
     *
     * 実行前処理
     * (パス変更前のクラスを保存する)
     *
     * @return void
     */
    private function BeforeMarshal()
    {
        $this->prev = $this;
    }

    public function AfterMarshal(?Callable  $callBack = null): void
    {
        // 処理
        if (!is_null($callBack)) {
            $callBack($this, $this->prev);

        }
        var_dump($this->prev);

        if (isset($this->prev)) {
            var_dump("階層移動");
            $this->prev->AfterMarshal($callBack);
        }
    }

    public function OutArray(): array
    {
        return explode($this->separator, $this->path);
    }

}
