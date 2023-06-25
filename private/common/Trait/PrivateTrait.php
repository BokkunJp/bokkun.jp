<?php

$traitPath = new \Path(COMMON_DIR);
$traitPath->Add('Trait');
IncludeFiles($traitPath->Get());

trait PrivateTrait
{
    use CommonTrait;
    /**
     * FindFileName
     *
     * 親ディレクトリ・カレントディレクトリ以外のファイルを検索する
     *
     * @param  string $str
     *
     * @return bool
     */
    private function FindFileName(string $str): bool
    {
        if (preg_match('/^\.$/', $str) || preg_match('/^\.\.$/', $str)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * DeleteData
     *
     * 対象のパスのディレクトリに、指定したファイルが存在するか調べる
     *
     * @param  string $dirPath
     * @param string $select
     *
     * @return bool
     */
    public function ValidateData(string $dirPath, string $select): bool
    {
        $dirArray = scandir($dirPath);

        return SearchData($select, $dirArray);
    }

    /**
     * DeleteData
     *
     * 対象のパスのディレクトリとファイルを削除する
     *
     * @param  string $dirPath
     *
     * @return bool
     */
    private function DeleteData(string $select): bool
    {
        $dirPath = \Path::AddPathStatic(getcwd(), $select, false);
        return system("rm -rf $dirPath");
    }

    /**
     * CopyData
     *
     * 対象のパスのディレクトリとファイルを複製する
     *
     * @param  string $srcPath
     * @param  string $copyName
     * @param  bool $dpAuthFlg
     *
     * @return bool
     */
    private function CopyData(string $srcPath, string $copyName, bool $dpAuthFlg = true): bool
    {
        $dstPath = \Path::AddPathStatic(dirname($srcPath), $copyName);

        if (is_dir($srcPath)) {
            // コピー元にファイルがある場合は、ファイルを走査してコピー
            if (!is_dir($dstPath)) {
                mkdir($dstPath);
            } else {
                if ($dpAuthFlg === false) {
                    return -1;
                }
            }

            foreach (scandir($srcPath) as $_file) {
                if ((FindFileName($_file))) {
                    if (is_readable(\Path::AddPathStatic($srcPath, $_file, false))) {
                        copy(\Path::AddPathStatic($srcPath, $_file, false), \Path::AddPathStatic($dstPath, $_file, false));
                    } else {
                        if (is_dir(\Path::AddPathStatic($srcPath, $_file, false))) {
                            if (!is_dir(\Path::AddPathStatic($dstPath, $_file, false))) {
                                mkdir(\Path::AddPathStatic($dstPath, $_file, false));
                            }
                            CopySubData(\Path::AddPathStatic($srcPath, $_file, false), \Path::AddPathStatic($dstPath, $_file, false));
                        }
                    }
                }
            }
        } else {
            // コピー元にファイルがない場合は終了
            return false;
        }

        return true;
    }

    /**
     * CopySubData
     *
     * 対象のパスの子階層のディレクトリとファイルを複製する
     *
     * @param  string $srcPath
     * @param  string $copyName
     *
     * @return bool
     */
    private function CopySubData(string $srcPath, string $dstPath): bool
    {
        // 主階層のディレクトリがコピー先にない場合は作成
        if (!is_dir($dstPath)) {
            mkdir($dstPath);
        }

        foreach (scandir($srcPath) as $_file) {
            if ((FindFileName($_file))) {
                if (is_readable(\Path::AddPathStatic($srcPath, $_file, false))) {
                    copy(\Path::AddPathStatic($srcPath, $_file, false), \Path::AddPathStatic($dstPath, $_file, false));
                } else {
                    if (is_dir(\Path::AddPathStatic($dstPath, $_file, false))) {
                        if (!is_dir(\Path::AddPathStatic($dstPath, $_file, false))) {
                            mkdir(\Path::AddPathStatic($dstPath, $_file, false));
                        }
                    }
                    CopySubData(\Path::AddPathStatic($srcPath, $_file, false), \Path::AddPathStatic($dstPath, $_file, false));
                }
            }
        }

        return true;
    }

    /**
     * GetNotDelFileList
     *
     * 削除不可ディレクトリリストを配列で取得する。
     *
     * @return array
     */
    private function GetNotDelFileList(): array
    {
        return NOT_DELETE_FILE_LIST;
    }

    /**
     * CountReset
     *
     * ログアウト画面を表示して、セッションを切断する。
     *
     * @return void
     */
    public function Logout(): void
    {
        echo "<div align='center'><strong>ログアウトしました。</strong></div>";

        // セッションの破棄
        $session = new private\Session();
        $session->FinaryDestroy();
    }
}
