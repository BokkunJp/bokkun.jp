<?php

header("Content-Type: application/json; charset=UTF-8");

define("DS", DIRECTORY_SEPARATOR);
require_once dirname(__DIR__, 3). DS. "common". DS . "ajax-require.php";

$set = new Private\Important\Setting();


// ログパスをセット
$dirPath = rtrim(dirname(__DIR__, 6), "\\");
$dirPath = new \Path($dirPath);
$dirPath->setPathEnd();
$dirPath->add('log');
$dirPath = $dirPath->get();

$srcName = $set->getPost('select_log');

$errCode=null;

$result = validateData($dirPath, $srcName);


if ($srcName === '---') {
    $errCode = 1;
} elseif ($result === false) {
    $errCode = 2;
}

if (!$errCode) {
    $srcPath = new \Path($dirPath);
    $srcPath->setPathEnd();
    $srcPath->add($srcName);
    $srcFile = $srcPath->get();
    if (file_exists($srcFile)) {
        $contents = file_get_contents($srcFile, FILE_USE_INCLUDE_PATH);
    } else {
        // ファイルの取得に失敗
        $contents = 'ログファイルの取得に失敗しました。';
    }
} else {
    // エラーパターン
    switch ($errCode) {
        case 1:
            $contents = 'ログファイルを選択してください。';
        break;
        case 2:
            $contents = 'ログファイルの選択が不正です。';
            // no break
        default:
        break;
    }
}

$data = ['log-view'=> nl2br(htmlentities($contents))];
$json = json_encode($data); // データをJSON形式にエンコードする

echo $json; // 結果を出力
