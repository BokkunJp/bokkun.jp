<?php

header("Content-Type: application/json; charset=UTF-8");

define("DS", DIRECTORY_SEPARATOR);
require_once dirname(__DIR__, 3). DS. "common". DS . "ajax-require.php";

$set = new Private\Important\Setting();

// エラーパスをセット
$errPath = dirname(__DIR__);
$errPath = createClient('log', $errPath);

    //選択したバージョンの指定
$verPath = $set->getPost('ver');
$dirPath = rtrim(dirname(__DIR__, 6), "\\") . $errPath. $verPath;

$srcName = $set->getPost('select_log');

$errCode=null;
if ($srcName === '') {
    $errCode = 1;
} elseif ($srcName === null) {
    $errCode = 2;
}

// ディレクトリ名選択時
if ($srcName === false) {
    // 添え字がないので添え字を振り直し
    if (is_dir($dirPath)) {
        $verObj = scandir($dirPath);
        $verObj = array_values($verObj);
    } else {
        if ($verPath === '---') {
            $errCode = 1;
        } else {
            $errCode = 2;
        }
    }
    switch ($errCode) {
        case 1:
            $contents = 'バージョンまたはファイルを選択してください。';
            break;
        case 2:
            $contents = 'バージョンまたはファイルの選択が不正です。';
            break;
        default:
            break;
    }

    if (!empty($errCode)) {
        $verObj = ['log-view' => nl2br(htmlentities($contents))];
    }

    $jsonData = json_encode($verObj); // データをJSON形式にエンコードする

// エラーファイル名選択時
} else {
    $srcPath = new \Path($dirPath);
    $srcPath->setPathEnd();
    $srcPath->add($srcName);
    $srcFile = $srcPath->get();
    if (!$errCode && !is_file($srcFile)) {
        $errCode = 2;
    }

    switch ($errCode) {
        case 1:
            $contents = 'バージョンまたはファイルを選択してください。';
        break;
            case 2:
            $contents = 'バージョンまたはファイルの選択が不正です。';
        break;
        default:
            $contents = file_get_contents($srcFile, FILE_USE_INCLUDE_PATH);
        break;
    }

    $data = ['log-view'=> nl2br(htmlentities($contents))];
    $jsonData = json_encode($data); // データをJSON形式にエンコードする
}

echo $jsonData;
