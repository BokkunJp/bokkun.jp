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
    if (!is_string($arr)) {
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

/**
 * filter_input_fix
 * $_SERVER, $_ENVのための、filter_input代替処理。
 *
 *
 * @param  mixed $type
 * @param  mixed $variable_name
 * @param  int $filter
 * @param  mixed|null $options
 *
 * @return bool
 */
function filter_input_fix($type, $variable_name, $filter = FILTER_DEFAULT, $options = NULL )
{
    $checkTypes =[
        INPUT_GET,
        INPUT_POST,
        INPUT_COOKIE
    ];

    if ($options === NULL) {
        // No idea if this should be here or not
        // Maybe someone could let me know if this should be removed?
        $options = FILTER_NULL_ON_FAILURE;
    }

    if (in_array($type, $checkTypes) || filter_has_var($type, $variable_name)) {
        return filter_input($type, $variable_name, $filter, $options);
    } else if ($type == INPUT_SERVER && isset($_SERVER[$variable_name])) {
        return filter_var($_SERVER[$variable_name], $filter, $options);
    } else if ($type == INPUT_ENV && isset($_ENV[$variable_name])) {
        return filter_var($_ENV[$variable_name], $filter, $options);
    } else {
        return NULL;
    }
}