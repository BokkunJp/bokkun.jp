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
