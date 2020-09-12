<?php
header("Content-Type: application/json; charset=UTF-8");

define ("DS", DIRECTORY_SEPARATOR);
require_once dirname(__DIR__, 3). DS. "common". DS . "ajax-require.php";
use PrivateSetting\Setting;

$set = new Setting();


// ログパスをセット
$dirPath = rtrim(dirname(__DIR__, 6), "\\");
$dirPath = AddPath($dirPath, 'log');

$srcName = $set->GetPost('select_log');

$accCode=null;
if ($srcName === '---') {
    $accCode=1;
} else if ($srcName === false) {
    $accCode = 2;
}

if ($srcName === false) {
    switch ($srcName) {
        case 1:
            $contents = 'バージョンまたはファイルを選択してください。';
            $data = ['log-view' => nl2br(htmlentities($contents))];
            $jsonData = json_encode($data); // データをJSON形式にエンコードする
            break;
        case 2:
            $contents = 'バージョンまたはファイルの選択が不正です。';
            $data = ['log-view' => nl2br(htmlentities($contents))];
            $jsonData = json_encode($data); // データをJSON形式にエンコードする
            break;
        default:
            // 添え字がないので添え字を振り直し
            $verObj = scandir($dirPath);
            $verObj = array_values($verObj);
            $jsonData = json_encode($verObj);
        break;
    }

    echo $jsonData;
} else {
    $srcFile = AddPath($dirPath, $srcName, false);

    switch ($accCode) {
        case 1:
            $contents = 'バージョンまたはファイルを選択してください。';
        break;
            case 2:
            $contents = 'バージョンまたはファイルの選択が不正です。';
        break;
        default:
            if (file_exists($srcFile)) {
                $contents = file_get_contents($srcFile, FILE_USE_INCLUDE_PATH);
            } else {
                $contents = 'ファイルが存在しません。';
            }
        break;
    }

    $data = ['log-view'=> nl2br(htmlentities($contents))];
    $json = json_encode($data); // データをJSON形式にエンコードする

    echo $json; // 結果を出力
}
