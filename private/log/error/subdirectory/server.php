<?php
header("Content-Type: application/json; charset=UTF-8");

//直接のページ遷移を阻止
$request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
if ($request !== 'xmlhttprequest') {
    exit;
}

define ("DS", DIRECTORY_SEPARATOR);
require_once dirname(__DIR__, 3). DS. "common". DS . "ajax-require.php";
use PrivateSetting\Setting;

$set = new Setting();

// エラーパスをセット
$errPath = dirname(__DIR__);
$errPath = CreateClient($errPath);

    //選択したバージョンの指定
$verPath = $set->GetPost('ver');
$dirPath = rtrim(dirname(__DIR__, 6), "\\") . $errPath. $verPath;

$errCode=null;
if ($verPath === '---') {
    $errCode=1;
}

if ($set->GetPost('select_log') === '') {
    $errCode = 2;
}


if (is_null($set->GetPost('select_log'))) {
    switch ($errCode) {
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
    $srcName = $set->GetPost('select_log');
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
