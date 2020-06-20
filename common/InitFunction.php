<?php
// 既存のパスに新たな要素を追加する
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

// ヌルバイト対策 (POST, GET)
function Sanitize($arr) {
    if (is_array($arr) ){
    return array_map('Sanitize', $arr);
    }
    return str_replace("\0", "", $arr);     //ヌルバイトの除去
}

/**
 * CreateClient
 * 所定のディレクトリまでのディレクトリ群を走査し、パスを生成する。
 *
 * @param  string $target
 *
 * @return bool
 */
function CreateClient($src = '', $target = 'private')
{
  if (empty($src)) {
    $srcPath = getcwd();
  } else {
    $srcPath = $src;
  }

  $clientPath = "";
  $clientAry = [];

  while (strcmp(basename($srcPath), $target)) {
    $clientAry[] = basename($srcPath);
    $srcPath = dirname($srcPath);
  }

  $clientAry = array_reverse($clientAry);

  foreach ($clientAry as $_client) {
    $clientPath = AddPath($clientPath, $_client);
  }

  return $clientPath;
}