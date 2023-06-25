<?php

$commonInitFunctionPath = dirname(__DIR__, 2);
$commonInitFunctionPath = $commonInitFunctionPath. DIRECTORY_SEPARATOR. 'common';
$commonInitFunctionPath = $commonInitFunctionPath. DIRECTORY_SEPARATOR. 'InitFunction.php';
require_once $commonInitFunctionPath;

/**
 * CreateRandom
 *
 * 指定した長さ x2の乱数を生成
 *
 * @param  int $length
 * @param  string $type
 *
 * @return string
 */
function CreateRandom(int $length, string $type = 'security'): string
{
    switch ($type) {
        case 'security':
            $bytes = bin2hex(openssl_random_pseudo_bytes($length));
            break;
        case 'sha1':
            $bytes = sha1(CreateRandom($length, 'mt_rand'));
            break;
        case 'md5':
            $bytes = md5(CreateRandom($length, 'mt_rand'));
            break;
        case 'uniq':
            $bytes = uniqid(CreateRandom($length, 'mt_rand'));
            break;
        case 'mt_rand':
            $bytes = (string)mt_rand(0, $length);
            break;
        case 'random_bytes':
            $bytes = bin2hex(random_bytes($length));
            break;
        default:
            $bytes = CreateRandom($length);
            break;
    }
    return $bytes;
}

/**
 * FindFileName
 *
 * ファイル形式かチェックする
 *
 * @param  string $str
 * @param bool $existFlg
 *
 * @return bool
 */
function FindFileName(string $str, bool $rootOnly = true, bool $existFlg = false): bool
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
 * ValidateData
 *
 * 対象のパスのディレクトリに、指定したファイルが存在するか調べる
 *
 * @param  string $dirPath
 * @param string $select
 *
 * @return bool
 */
function ValidateData(string $dirPath, ?string $select): bool
{
    $dirArray = scandir($dirPath);

    return SearchData($select, $dirArray);
}

/**
 * DeleteData
 *
 * 対象のパスのディレクトリとファイルを削除する
 * (ディレクトリ内にディレクトリがある場合、そのディレクトリも削除対象となる)
 *
 * @param string $path 対象データまでのパス
 * @param  string $select 対象データ名
 *
 * @return bool
 */
function DeleteData(string $path, string $select): bool
{
    $delPath = new \Path($path);
    $delPath->Add($select);
    if (!is_dir($delPath->Get())) {
        $delPath = new \Path($path);
        $delPath->SetPathEnd();
        $delPath->Add($select);
    }

    if (PHP_OS === 'WIN32' || PHP_OS === 'WINNT') {
        $commandResult = system("rd /s /q {$delPath->Get()}");
    } else {
        $commandResult = system("rm -rf {$delPath->Get()}");
    }

    return $commandResult;
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
function CopyData(string $srcPath, string $copyName, bool $dpAuthFlg = true): bool
{
    $dstPath = new \Path(dirname($srcPath));
    $dstPath->Add($copyName);

    if (is_dir($srcPath)) {
        // コピー元にファイルがある場合は、ファイルを走査してコピー
        if (!is_dir($dstPath->Get())) {
            mkdir($dstPath->Get());
        } elseif ($dpAuthFlg === false) {
            return -1;
        }

        foreach (scandir($srcPath) as $_file) {
            if ((FindFileName($_file))) {
                $filePath = new \Path($srcPath);
                $filePath->SetPathEnd();
                $filePath->Add($_file);
                if (is_file($filePath->Get())) {
                    $dstPath->SetPathEnd();
                    $dstPath->Add($_file);
                    copy($filePath->Get(), $dstPath->Get());
                    $dstPath = new \Path(dirname($dstPath->Get()));
                } else {
                    if (is_dir($filePath->Get())) {
                        if (is_file($dstPath->Get())) {
                            $dstPath = new \Path(dirname($dstPath->Get()));
                        }
                        $dstPath = new \Path($dstPath->Get());
                        $dstPath->SetPathEnd();
                        $dstPath->Add($_file);
                        if (!is_dir($dstPath->Get())) {
                            mkdir($dstPath->Get());
                        }
                        CopySubData($filePath->Get(), $dstPath->Get());
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
function CopySubData(string $srcPath, string $dstPath): bool
{
    // 主階層のディレクトリがコピー先にない場合は作成
    if (!is_dir($dstPath)) {
        mkdir($dstPath);
    }

    foreach (scandir($srcPath) as $_file) {
        $dstFilePath = new \Path($dstPath);
        $dstFilePath->SetPathEnd();
        $dstFilePath->Add($_file);

        if ((FindFileName($_file))) {
            $filePath = new \Path($srcPath);
            $filePath->SetPathEnd();
            $filePath->Add($_file);
            if (is_file($filePath->Get())) {
                copy($filePath->Get(), $dstFilePath->Get());
            } else {
                if (is_dir($dstFilePath->Get())) {
                    if (!is_dir($dstFilePath->Get())) {
                        mkdir($dstFilePath->Get());
                    }
                }
                CopySubData($filePath->Get(), $dstFilePath->Get());
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
function GetNotDelFileList(): array
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
function Logout(): void
{
    echo "<div align='center'><strong>ログアウトしました。</strong></div>";

    // セッションの破棄
    $session = new private\Session();
    $session->FinaryDestroy();
    unset($session);
}
