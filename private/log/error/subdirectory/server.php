<?php
header("Content-Type: application/json; charset=UTF-8");

define ("DS", DIRECTORY_SEPARATOR);
require_once dirname(__DIR__, 3). DS. "common". DS . "ajax-require.php";
use PrivateSetting\Setting;

$set = new Setting();

// エラーパスをセット
$errPath = dirname(__DIR__);
$errPath = CreateClient('log', $errPath);

    //選択したバージョンの指定
$verPath = $set->GetPost('ver');
$dirPath = rtrim(dirname(__DIR__, 6), "\\") . $errPath. $verPath;

$srcName = $set->GetPost('select_log');

$errCode=null;
if ($verPath === '---') {
    $errCode=1;
} else if ($srcName === null) {
    $errCode = 2;
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

    switch ($errCode) {
        case 1:
            $contents = 'バージョンまたはファイルを選択してください。';
        break;
            case 2:
            $contents = 'バージョンまたはファイルの選択が不正です。';
        break;
        default:
            // ソースの読み込み
            $verObj = scandir($dirPath);
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
