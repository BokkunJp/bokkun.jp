<?php
header("Content-Type: application/json; charset=UTF-8");
define("DS", DIRECTORY_SEPARATOR);

require_once dirname(__DIR__, 2) . DS . "common" . DS . "ajax-require.php";

use PrivateSetting\Setting;

// tokenチェック
$checkToken = CheckToken();

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($checkToken === false) {
    $data = ['src' => '', 'src-view' => '不正な操作を検知しました。再読み込みしてください。'];
    $json = json_encode($data);
    echo $json;
    exit;
}

$set = new Setting();

$saveObj = $set->GetPost('save');
$editObj = $set->GetPost('edit');
$srcName = $set->GetPost('dir_name');
$selectObj = $set->GetPost('select_directory');


// パスをセット
$srcPath = AddPath(dirname(__DIR__, 3), $srcName, false);


// 第2ディレクトリの選択
if ($selectObj !== false) {
    $srcPath = AddPath($srcPath, $selectObj, false);
    if (is_dir($srcPath)) {
        $dataList = scandir($srcPath);
        $notList = ['templates_c'];
        foreach ($dataList as $_key => $_data) {
            foreach ($notList as $_nList) {
                if ($_data === $_nList) {
                    unset($dataList[$_key]);
                }
            }
        }
        $contents = array_values($dataList);
        $viewContents = $contents;
    } else {
        $contents = $viewContents = $selectObj;
    }
} else if ($srcName !== false && $srcName !== '---') {
// ディレクトリの選択
    $dataList = scandir($srcPath);
    $notList = [];
    foreach ($dataList as $_key => $_data) {
        foreach ($notList as $_nList) {
            if ($_data === $_nList) {
                unset($dataList[$_key]);
            }
        }
    }
    $contents = array_values($dataList);
    $viewContents = $contents;
} else {
    $contents = $viewContents = ['err' => 'ディレクトリが存在しません。'];
}
// ディレクトリ選択時は、データをそのまま出力
$data = $contents;

// ソースの保存・書き込み共通処理
if ($saveObj !== false || $editObj !== false) {
    $selectSrc = AddPath($srcPath, AddPath($set->GetPost('directory'), $set->GetPost('subdirectory'), false), false);

    if (is_dir($selectSrc)) {
        $srcFile = AddPath($selectSrc, $set->GetPost('file'), false);
    } else {
        $srcFile = $selectSrc;
    }
}

// ソースの保存
if ($saveObj  !== false && $set->GetPost('directory') != '---') {

    if (file_exists($srcFile) && is_file($srcFile)) {
        $srcObj = $set->GetPost('input');
        file_put_contents($srcFile, $srcObj);
        $contents='';
    }
}

// ソースの読み込み
if ($editObj !== false && $set->GetPost('directory') != '---') {
    if (file_exists($srcFile) && is_file($srcFile)) {
        $contents = htmlentities(file_get_contents($srcFile));
        $viewContents = nl2br($contents);
    } else {
        $contents = '';
        $viewContents = 'ファイルが存在しません。';
    }
    $data = ['src'=> $contents, 'src-view' => $viewContents];
} else {
    $contents = '';
    $viewContents = 'ファイルが存在しません。';
}
// var_dump($contents);die;

$json = json_encode($data); // データをJSON形式にエンコードする

echo $json; // 結果を出力