<?php
/**
 * AddPath
 * 既存のパスに新たな要素を追加する
 *
 * @param  mixed $local
 * @param  mixed $addpath
 * @param  bool $lastSeparator
 * @param  string $separator
 *
 * @return string
 */
function AddPath($local, $addpath, $lastSeparator=true,  $separator=DIRECTORY_SEPARATOR) {
  if (mb_substr($local, -1) == $separator) {
    $first = '';
  } else {
    $first = $separator;
  }
  if ($lastSeparator == true) {
    $last = $separator;
  } else {
    $last = '';
  }

  $local .= $first. $addpath. $last;    // パス追加 + パス結合

  $local = htmlspecialchars($local);    // XSS対策

  return $local;
}

/**
 * Sanitize
 * ヌルバイト対策 (POST, GET)
 *
 * @param  mixed $arr
 *
 * @return array|mixed
 */
function Sanitize($arr) {
    if (is_array($arr) ){
    return array_map('Sanitize', $arr);
    }
    return str_replace("\0", "", $arr);     //ヌルバイトの除去
}

/**
 * CreateRandom
 * 指定した長さ x2の乱数を生成
 *
 * @param  int $length
 * @param  string $type
 *
 * @return string
 */
function CreateRandom(int $length, string $type='security') {
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