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
    if (is_null($arr)) {
        return $arr;
    }

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
function CreateClient($target, $src = '')
{
  if (empty($src)) {
    $srcPath = getcwd();
  } else {
    $srcPath = $src;
  }

  $clientPath = "";
  $clientAry = [];

  while (1) {
    $clientAry[] = basename($srcPath);
    $srcPath = dirname($srcPath);
    if (strcmp(basename($srcPath), $target)) {
        break;
    }
  }
  $clientAry = array_reverse($clientAry);

  foreach ($clientAry as $_client) {
    $clientPath = AddPath($clientPath, $_client);
  }

  return $clientPath;
}

/**
 * CheckToken
 * Post値とセッション値のチェック
 *
 *
 * @param  string $tokenName
 * @param  boolean $chkFlg
 *
 * @return bool
 */
function CheckSession($SessionName, $chkFlg)
{
    $input = CommonSetting\Setting::GetPost($SessionName);
    $session = new CommonSetting\Session();

    if ($chkFlg === true) {
        echo 'デバッグ用<br/>';
        echo 'post: ' . $input . '<br/>';
        echo 'session: ' . $session->Read($SessionName) . '<br/><br/>';
    }

    if (is_null($input) || !hash_equals($session->Read($SessionName), $input)) {
        return false;
    }

    return true;
}
