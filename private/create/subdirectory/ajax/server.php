<?php

header("Content-Type: application/json; charset=UTF-8");

require_once dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "ajax-require.php";

// Post値取得
$post = \Private\Important\Setting::getPosts();
// tokenチェック
$session = new \Private\Important\Session('create-page');
$createToken = new \Private\Important\Token('edit-token', $session);
if ($createToken->check() === false) {
    $data = ['src' => true];
    $json = json_encode($data);
    echo $json;
    exit;
}

// Post値の検証
$data = searchData($post['page'], NOT_DELETE_FILE_LIST);

$json = json_encode($data);
echo $json;
