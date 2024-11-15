<?php

$traitPath = new \Path(COMMON_DIR);
$traitPath->add('Trait');
includeFiles($traitPath->get());

trait PrivateTrait
{
    use CommonTrait;
    /**
     * findFileName
     *
     * 親ディレクトリ・カレントディレクトリ以外のファイルを検索する
     *
     * @param  string $str
     *
     * @return bool
     */
    private function findFileName(string $str): bool
    {
        if (preg_match('/^\.$/', $str) || preg_match('/^\.\.$/', $str)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * validateData
     *
     * 対象のパスのディレクトリに、指定したファイルが存在するか調べる
     *
     * @param  string $dirPath
     * @param string $select
     *
     * @return bool
     */
    public function validateData(string $dirPath, string $select): bool
    {
        $dirArray = scandir($dirPath);

        return $this->searchData($select, $dirArray);
    }

    /**
     * deleteData
     *
     * 対象のパスのディレクトリとファイルを削除する
     *
     * @param  string $dirPath
     *
     * @return bool
     */
    private function deleteData(string $select): bool
    {
        $dirPath = new Path(getcwd());
        $dirPath->setPathEnd();
        $dirPath = $this->add(getcwd(), $select, false);
        return system("rm -rf $dirPath");
    }

    /**
     * copyData
     *
     * 対象のパスのディレクトリとファイルを複製する
     *
     * @param  string $srcPath
     * @param  string $copyName
     * @param  bool $dpAuthFlg
     *
     * @return bool
     */
    private function copyData(string $srcPath, string $copyName, bool $dpAuthFlg = true): bool
    {
        $dirPath = new Path(getcwd());
        $dstPath = $dirPath->add(dirname($srcPath), $copyName, false);

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
                if ((findFileName($_file))) {
                    $srcFilePath = new \Path($srcPath);
                    $srcFilePath->setPathEnd();
                    $dstFilePath = new \Path($dstPath);
                    $dstFilePath->setPathEnd();

                    $srcFilePath = $srcFilePath->add($_file, false);
                    $dstFilePath = $dstFilePath->add($_file, false);
                    if (is_readable($srcFilePath)) {
                        copy($srcFilePath, $dstFilePath);
                    } else {
                        if (is_dir($srcFilePath)) {
                            if (!is_dir($dstFilePath)) {
                                mkdir($dstFilePath);
                            }
                            copySubData($srcFilePath, $dstFilePath);
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
     * copySubData
     *
     * 対象のパスの子階層のディレクトリとファイルを複製する
     *
     * @param  string $srcPath
     * @param  string $copyName
     *
     * @return bool
     */
    private function copySubData(string $srcPath, string $dstPath): bool
    {
        // 主階層のディレクトリがコピー先にない場合は作成
        if (!is_dir($dstPath)) {
            mkdir($dstPath);
        }

        foreach (scandir($srcPath) as $_file) {
            if ((findFileName($_file))) {
                    $srcFilePath = new \Path($srcPath);
                    $srcFilePath->setPathEnd();
                    $dstFilePath = new \Path($dstPath);
                    $dstFilePath->setPathEnd();

                    $srcFilePath = $srcFilePath->add($_file, false);
                    $dstFilePath = $dstFilePath->add($_file, false);
                if (is_readable($srcFilePath)) {
                    copy($srcFilePath, $dstFilePath);
                } else {
                    if (is_dir($dstFilePath)) {
                        if (!is_dir($dstFilePath)) {
                            mkdir($dstFilePath);
                        }
                    }
                    copySubData($srcFilePath, $dstFilePath);
                }
            }
        }

        return true;
    }

    /**
     * getNotDelFileList
     *
     * 削除不可ディレクトリリストを配列で取得する。
     *
     * @return array
     */
    private function getNotDelFileList(): array
    {
        return NOT_DELETE_FILE_LIST;
    }

    /**
     * logout
     *
     * ログアウト画面を表示して、セッションを切断する。
     *
     * @return void
     */
    public function logout(): void
    {
        echo "<div align='center'><strong>ログアウトしました。</strong></div>";

        // セッションの破棄
        $session = new Private\Important\Session();
        $session->delete();
    }
}
