<?php

header("Content-Type: application/json; charset=UTF-8");
define("DS", DIRECTORY_SEPARATOR);

require_once dirname(__DIR__, 3) . DS . "common" . DS . "ajax-require.php";
require_once AddPath(getcwd(), 'include.php', false);
IncludeFiles(AddPath(dirname(__DIR__), ''));

// tokenチェック
$checkToken = CheckToken('select-token');

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($checkToken === false) {
    $data = ['src' => true];
    $json = json_encode($data);
    echo $json;
    exit;
}

// Post値の検証
if (!isset($post['type'])) {
    $postValid = false;
} else {
    $postValid = ValidateData(PUBLIC_IMAGE_DIR, $post['type']);
}

// 不正なPostが入った場合は、セッションに保存した情報かデフォルトページを参照する
if ($postValid === false) {
    // デフォルトのページ(IMAGE)もない場合はエラーを出力して処理を中断。
    $defaultValid = ValidateData(PUBLIC_IMAGE_DIR, DEFAULT_IMAGE);
    if ($defaultValid === false) {
        $data = ['error' => '', 'src-view' => '必要なディレクトリがありません。'];
        $json = json_encode($data);
        echo $json;
        exit;
    }

    if ($session->Judge('image-view-directory')) {
        // セッションに情報が保存されている場合はその情報を参照する
        $post['type'] = $session->Read('image-view-directory');
    } else {
        // セッションに情報がない場合はデフォルトページを参照する
        $post['type'] = DEFAULT_IMAGE;
    }
}

// セッションの内容を更新
$session->Write('image-view-directory', $post['type']);

// 画像群を取得して、フロント処理側に返却
$img = ReadImage(ajaxFlg:true);
$json = json_encode($img);
echo $json;
