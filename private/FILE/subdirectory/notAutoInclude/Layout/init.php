<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(dirname(dirname(dirname(__DIR__)))). '/common/Setting.php';
require_once PRIVATE_COMMON_DIR. "/Include.php";
// タイトルの初期設定
if (isset($homepageTitle)) {
    $title = htmlspecialchars($homepageTitle);
} else {
    $title = htmlspecialchars(basename(__DIR__));
}
// 文字列からディレクトリ部分の文字列を切り取る関数
function StrExtraction($str, $target) {
    $count = strpos($str, $target);
    return mb_strcut($str, $count);
}

