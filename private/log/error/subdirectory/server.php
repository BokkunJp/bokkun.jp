<?php
header("Content-Type: application/json; charset=UTF-8");
define ("DS", DIRECTORY_SEPARATOR);
require_once dirname(__DIR__, 2). DS. "common". DS . "ajax-require.php";
use PrivateSetting\Setting;

$set = new Setting();

$saveObj = $set->GetPost('save');
$srcName = $set->GetPost('select');
$srcFile = dirname(__DIR__, 3) . DS . 'public' . DS . $srcName. DS . 'design.php';


if (isset($saveObj)) {
    // ソースの保存
    $srcObj = $set->GetPost('input');
    file_put_contents($srcFile, $srcObj);
    $contents='';
}

// ソースの読み込み
$contents = file_get_contents($srcFile);
// var_dump($contents);die;

$data = ['src'=> htmlentities($contents), 'src-view' => nl2br(htmlentities($contents))];
$json = json_encode($data); // データをJSON形式にエンコードする

echo $json; // 結果を出力