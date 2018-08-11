<?php
if (isset($_SERVER['HTTPS'])) {
    $http = '//';
} else {
    $http = 'http://';
}
$domain = $_SERVER['SERVER_NAME'];

$url = $http.$domain;
$private = $url. '/private/';

// 定数などの定義
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('API_DIR', DOCUMENT_ROOT. '/API');
define('COMMON_DIR', __DIR__);
define('PUBLIC_DIR', DOCUMENT_ROOT. '/public');
define('PRIVATE_DIR', DOCUMENT_ROOT. '/private');
define('FUNCTION_DIR', COMMON_DIR. '/Function');
define('SAMPLE_DIR', COMMON_DIR. '/Sample');
define('LAYOUT_DIR', SAMPLE_DIR. '/Layout');

$Agent = $_SERVER['HTTP_USER_AGENT'];
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
}

function GetSelf_Admin() {
    return $_SERVER['PHP_SELF'];
}

function GetPrevPage_Admin() {
    return filter_input(INPUT_SERVER, 'HTTP_REFERER');
}


function getURI_Admin() {
    return $_SERVER['REQUEST_URI'];
}
