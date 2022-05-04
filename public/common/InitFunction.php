<?php

$commonInitFunctionPath = dirname(__DIR__, 2);
$commonInitFunctionPath = $commonInitFunctionPath . DIRECTORY_SEPARATOR . 'common';
$commonInitFunctionPath = $commonInitFunctionPath . DIRECTORY_SEPARATOR . 'InitFunction.php';
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
function CreateRandom(int $length, string $type='security'): string
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
