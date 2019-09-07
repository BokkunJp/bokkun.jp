<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(dirname(__DIR__)). '/common/Setting.php';
require_once PUBLIC_COMMON_DIR. "/Include.php";

// タイトルの初期設定
if (isset($homepageTitle)) {
    $title = htmlspecialchars($homepageTitle);
} else {
    $title = htmlspecialchars(basename(__DIR__));
}
