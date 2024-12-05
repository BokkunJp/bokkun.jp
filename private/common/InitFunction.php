<?php

$commonInitFunctionPath = dirname(__DIR__, 2);
$commonInitFunctionPath = $commonInitFunctionPath. DIRECTORY_SEPARATOR. 'common';
$commonInitFunctionPath = $commonInitFunctionPath. DIRECTORY_SEPARATOR. 'InitFunction.php';
require_once $commonInitFunctionPath;

/**
 * findFileName
 *
 * ファイル形式かチェックする
 *
 * @param  string $str
 * @param bool $existFlg
 *
 * @return bool
 */
function findFileName(string $str, bool $rootOnly = true, bool $existFlg = false): bool
{
    $ret = true;
    if (preg_match('/^\.$/', $str) || preg_match('/^\.\.$/', $str)) {
        $ret = false;
    }

    if (!$rootOnly) {
        if (!preg_match("/(.*)\.(.*)/", $str)) {
            $ret = false;
        }

        if ($existFlg) {
            if (!is_file($str)) {
                $ret = false;
            }
        }
    }

    return $ret;
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
function validateData(string $dirPath, ?string $select): bool
{
    $dirArray = scandir($dirPath);

    return searchData($select, $dirArray);
}

/**
 * deleteData
 *
 * 対象のパスのディレクトリとファイルを削除する
 * (ディレクトリ内にディレクトリがある場合、そのディレクトリも削除対象となる)
 *
 * @param string $path 対象データまでのパス
 * @param  string $select 対象データ名
 *
 * @return bool
 */
function deleteData(string $path, string $select): bool
{
    $delPath = new \Path($path);
    $delPath->add($select);
    if (!is_dir($delPath->get())) {
        $delPath = new \Path($path);
        $delPath->setPathEnd();
        $delPath->add($select);
    }

    if (PHP_OS === 'WIN32' || PHP_OS === 'WINNT') {
        $command = "rd /s /q \"{$delPath->get()}\"";
    } else {
        $command = "rm -rf \"{$delPath->get()}\"";
    }

    $commandResult = system($command);

    return $commandResult;
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
function copyData(string $srcPath, string $copyName, bool $dpAuthFlg = true): bool
{
    $dstPath = new \Path(dirname($srcPath));
    $dstPath->add($copyName);

    if (is_dir($srcPath)) {
        // コピー元にファイルがある場合は、ファイルを走査してコピー
        if (!is_dir($dstPath->get())) {
            mkdir($dstPath->get());
        } elseif ($dpAuthFlg === false) {
            return -1;
        }

        foreach (scandir($srcPath) as $_file) {
            if ((findFileName($_file))) {
                $filePath = new \Path($srcPath);
                $filePath->setPathEnd();
                $filePath->add($_file);
                if (is_file($filePath->get())) {
                    $dstPath->setPathEnd();
                    $dstPath->add($_file);
                    copy($filePath->get(), $dstPath->get());
                    $dstPath = new \Path(dirname($dstPath->get()));
                } else {
                    if (is_dir($filePath->get())) {
                        if (is_file($dstPath->get())) {
                            $dstPath = new \Path(dirname($dstPath->get()));
                        }
                        $dstPath = new \Path($dstPath->get());
                        $dstPath->setPathEnd();
                        $dstPath->add($_file);
                        if (!is_dir($dstPath->get())) {
                            mkdir($dstPath->get());
                        }
                        copySubData($filePath->get(), $dstPath->get());
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
function copySubData(string $srcPath, string $dstPath): bool
{
    // 主階層のディレクトリがコピー先にない場合は作成
    if (!is_dir($dstPath)) {
        mkdir($dstPath);
    }

    foreach (scandir($srcPath) as $_file) {
        $dstFilePath = new \Path($dstPath);
        $dstFilePath->setPathEnd();
        $dstFilePath->add($_file);

        if ((findFileName($_file))) {
            $filePath = new \Path($srcPath);
            $filePath->setPathEnd();
            $filePath->add($_file);
            if (is_file($filePath->get())) {
                copy($filePath->get(), $dstFilePath->get());
            } else {
                if (is_dir($dstFilePath->get())) {
                    if (!is_dir($dstFilePath->get())) {
                        mkdir($dstFilePath->get());
                    }
                }
                copySubData($filePath->get(), $dstFilePath->get());
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
function getNotDelFileList(): array
{
    return NOT_DELETE_FILE_LIST;
}

/**
 * logout
 *
 * ログアウト画面を表示して、ログインに関するセッション値を削除する。
 *
 * @return void
 */
function logout(): void
{
    echo "<div align='center'><strong>ログアウトしました。</strong></div>";

    // 管理側のログインセッションの削除
    $session = new Private\Important\Session('login');
    $session->delete();
}

/**
 * logout
 *
 * ログアウト画面を表示して、セッションを切断する。
 *
 * @return void
 */
function logoutWithSessionReset(): void
{
    echo "<div align='center'><strong>ログアウトしました。(セッションも全削除済)</strong></div>";

    // 全セッションの破棄
    $session = new Common\Important\Session();
    $session->delete();
    unset($session);
}
