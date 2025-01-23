<?php

header("Content-Type: application/json; charset=UTF-8");
define("DS", DIRECTORY_SEPARATOR);
require_once dirname(__DIR__, 3) . DS . "common" . DS . "ajax-require.php";
includeFiles(__DIR__. '/');
includeFiles(dirname(__DIR__). '/');

// Postセット
$post = Private\Important\Setting::getPosts();

// セッションセット
$session = new Private\Important\Session('image');

// tokenチェック
$selectToken = new Private\Important\Token('select-token', $session, true);
// 不正tokenの場合は、エラーを出力して処理を中断。
if ($selectToken->check() === false) {
    $data = ['error' => true, 'error-view' => '不正な遷移です。リロードしてください。'];
    $json = json_encode($data);
    echo $json;
    exit;
}

// Post値の検証
if (!isset($post['type'])) {
    $postValid = false;
} else {
    $postValid = validateData(PUBLIC_DIR_LIST['image'], $post['type']);
}

// 不正なPostが入った場合は、セッションに保存した情報かデフォルトページを参照する
if ($postValid === false) {
    if ($session->judge('image-view-directory')) {
        // セッションに情報が保存されている場合はその情報を参照する
        $post['type'] = $session->read('image-view-directory');
    } else {
        // セッションに情報がない場合はデフォルトページを参照する
        $post['type'] = DEFAULT_IMAGE;
    }
}

// セッションの内容を更新
$session->write('image-view-directory', $post['type']);
$session->write('delete-image-view-directory', $post['type']);

// 画像群を取得して、フロント処理側に返却
$img = readImage(ajaxFlg:true);

// 不正postの場合はエラー表示用のフラグを立てる
if (!$postValid) {
    $img['select-notice'] = true;
}

$json = json_encode($img);
echo $json;
