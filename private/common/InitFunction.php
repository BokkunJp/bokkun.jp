<?php
$commonInitFunctionPath = dirname(dirname(__DIR__));
$commonInitFunctionPath = $commonInitFunctionPath. DIRECTORY_SEPARATOR. 'common';
$commonInitFunctionPath = $commonInitFunctionPath. DIRECTORY_SEPARATOR. 'InitFunction.php';
require_once $commonInitFunctionPath;
/**
 * CreateRandom
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
 * 親ディレクトリ・カレントディレクトリ以外のファイルを検索する
 *
 * @param  string $str
 *
 * @return bool|string
 */
function FindFileName($str)
{
    if (preg_match('/^.$/', $str) || preg_match('/^..$/', $str)) {
        return false;
    } else {
        return $str;
    }
}

/**
 * DeleteData
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
            } else if ((FindFileName($_file) && !is_file($_file))) {
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
