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

$verObj = scandir($dirPath);

if (is_null($set->GetPost('select_log'))) {

    // 添え字がないので添え字を振り直し
    $verObj = array_values($verObj);

    $jsonData = json_encode($verObj);

    echo $jsonData;
} else {

    $srcName = $set->GetPost('select_log');
    if ($verPath === '---') {
        $contents = '正しいバージョンが選択されていません。';
    } else {
        $srcFile = AddPath($dirPath, $srcName, false);

        // // ソースの読み込み
        $contents = file_get_contents($srcFile, FILE_USE_INCLUDE_PATH);
    }

    $data = ['log-view'=> nl2br(htmlentities($contents))];
    $json = json_encode($data); // データをJSON形式にエンコードする

    echo $json; // 結果を出力
}
