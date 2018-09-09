<?php
// 既存のパスに新たな要素を追加する
function AddPath($local, $addpath, $lastSeparator=true) {
  if (!is_string($local)) {
    $local = (string)$local;
  }
  if (!is_string($addpath)) {
    $addpath = (string)$addpath;
  }
  if (mb_substr($local, -1) == DIRECTORY_SEPARATOR) {
    $first = '';
  } else {
    $first = DIRECTORY_SEPARATOR;
  }
  if ($lastSeparator == true) {
    $last = DIRECTORY_SEPARATOR;
  } else {
    $last = '';
  }

  return $local .= $first. $addpath. $last;
}
