<?php

$commonInitFunctionPath = dirname(dirname(__DIR__));
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
function CreateRandom(int $length, string $type = 'security')
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
            $bytes = mt_rand(0, $length);
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
 * 親ディレクトリ・カレントディレクトリ以外のファイルを検索する
 *
 * @param  string $str
 *
 * @return bool|string
 */
function FindFileName($str)
{
    $ret = true;
    if (preg_match('/^\.$/', $str) || preg_match('/^\.\.$/', $str)) {
        $ret = false;
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
function ValidateData(string $dirPath, ?string $select)
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
 * @param  string $dirPath
 *
 * @return bool
 */
function DeleteData($dirPath)
{
    if (is_dir($dirPath)) {
        foreach (scandir($dirPath) as $_file) {
            if (FindFileName($_file) && is_file($_file)) {
                unlink(AddPath($dirPath, $_file, false));
            } elseif ((FindFileName($_file) && !is_file($_file))) {
                if (file_exists(AddPath($dirPath, $_file))) {
                    DeleteData(AddPath($dirPath, $_file));
                } else {
                    unlink(AddPath($dirPath, $_file, false));
                }
            }
        }

        rmdir($dirPath);
    } else {
        return false;
    }

    return true;
}

/**
 * CopyData
 *
 * 対象のパスのディレクトリとファイルを複製する
 *
 * @param  string $srcPath
 * @param  string $copyName
 *
 * @return bool
 */
function CopyData($srcPath, $copyName, $dpAuthFlg=true)
{
    $dstPath = AddPath(dirname($srcPath), $copyName);

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
                if (is_file(AddPath($srcPath, $_file, false))) {
                    copy(AddPath($srcPath, $_file, false), AddPath($dstPath, $_file, false));
                } else {
                    if (is_dir(AddPath($srcPath, $_file, false))) {
                        if (!is_dir(AddPath($dstPath, $_file, false))) {
                            mkdir(AddPath($dstPath, $_file, false));
                        }
                        CopySubData(AddPath($srcPath, $_file, false), AddPath($dstPath, $_file, false));
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
function CopySubData($srcPath, $dstPath)
{
    // 主階層のディレクトリがコピー先にない場合は作成
    if (!is_dir($dstPath)) {
        mkdir($dstPath);
    }

    foreach (scandir($srcPath) as $_file) {
        if ((FindFileName($_file))) {
            if (is_file(AddPath($srcPath, $_file, false))) {
                copy(AddPath($srcPath, $_file, false), AddPath($dstPath, $_file, false));
            } else {
                if (is_dir(AddPath($dstPath, $_file, false))) {
                    if (!is_dir(AddPath($dstPath, $_file, false))) {
                        mkdir(AddPath($dstPath, $_file, false));
                    }
                }
                CopySubData(AddPath($srcPath, $_file, false), AddPath($dstPath, $_file, false));
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
function GetNotDelFileList()
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
function Logout()
{
    echo "<div align='center'><strong>ログアウトしました。</strong></div>";

    // セッションの破棄
    $session = new PrivateSetting\Session();
    $session->FinaryDestroy();
}
