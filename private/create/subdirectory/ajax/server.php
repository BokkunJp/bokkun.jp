<?php

header("Content-Type: application/json; charset=UTF-8");
define("DS", DIRECTORY_SEPARATOR);

require_once dirname(__DIR__, 3) . DS . "common" . DS . "ajax-require.php";
require_once AddPath(getcwd(), 'include.php', false);

// tokenチェック
$checkToken = CheckToken();

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($checkToken === false) {
    $data = ['src' => true];
    $json = json_encode($data);
    echo $json;
    exit;
}

// Post値の検証
$data = SearchData($post['page'], NOT_DELETE_FILE_LIST);

$json = json_encode($data);
echo $json;
