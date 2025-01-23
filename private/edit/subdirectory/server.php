<?php

header("Content-Type: application/json; charset=UTF-8");
define("DS", DIRECTORY_SEPARATOR);

require_once dirname(__DIR__, 2) . DS . "common" . DS . "ajax-require.php";

// tokenチェック
$session = new \Private\Important\Session('edit-page');
$editSrcToken = new \Private\Important\Token('edit-src-token', $session);
if ($editSrcToken->check() === false) {
    $data = ['src' => '', 'src-view' => '不正な操作を検知しました。再読み込みしてください。'];
    $json = json_encode($data);
    echo $json;
    exit;
}

$set = new \Private\Important\Setting();

$saveObj = $set->getPost('save');
$editObj = $set->getPost('edit');
$srcName = $set->getPost('dir_name');
$selectObj = $set->getPost('select_directory');

$sets = $set->getPosts();

// パスをセット
$srcPath = new \Path(dirname(__DIR__, 3));
if ($srcName !== false) {
    $srcPath = $srcPath->add($srcName, false);
}


// 第2ディレクトリの選択
if ($selectObj !== false) {
    $srcPath = new \Path($srcPath);
    $srcPath = $srcPath->add($selectObj, false);
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
} elseif ($srcName !== false && $srcName !== '---') {
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
    $selectSrc = new \Path($srcPath);
    $selectSrc->add($set->getPost('directory'));
    $selectSrc->setPathEnd();
    $selectSrc->add($set->getPost('subdirectory'));
    $selectSrc = $selectSrc->get();

    if (is_dir($selectSrc)) {
        $srcFile = new \Path($selectSrc);
        $srcFile->setPathEnd();
        $srcFile = $srcFile->add($set->getPost('file'), false);
    } else {
        $srcFile = $selectSrc;
    }
}

// ソースの保存
if ($saveObj  !== false && $set->getPost('directory') != '---') {
    if (file_exists($srcFile) && is_file($srcFile)) {
        $srcObj = $set->getPost('input');
        file_put_contents($srcFile, $srcObj);
        $contents='';
    }
}

// ソースの読み込み
if ($editObj !== false && $set->getPost('directory') != '---') {
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
