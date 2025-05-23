<?php

$request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';


if ($request === 'xmlhttprequest') {
    header("Content-Type: application/json; charset=UTF-8");
    $data = "Ajax: ";
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '/public/common' . DIRECTORY_SEPARATOR . 'InitFunction.php';
    require_once dirname(__DIR__, 2) . "/public/common/Setting.php";
    $posts = Public\Important\Setting::getPosts();
    foreach ($posts as $_key => $_post) {
        $data .= $_post;
        $data .= ',';
    }
    $data = rtrim($data, ',');
    $jsonData = json_encode($data); // データをJSON形式にエンコードする

    echo $jsonData;
} else {
    $data = "PHP: ";
    $posts = Public\Important\Setting::getPosts();
    $rangeCode = new RangeCode('message');
    $session = new Public\Important\Session('post');
    if (empty($posts)) {
        return -1;
    } elseif (empty($posts['data'])) {
        $data = "<span class='warning'>PHP:データがありません。<span/>";
        $session->write('output', $data);
        return null;
    }

    foreach ($posts as $_key => $_post) {
        $data .= $_post;
        $session->write('output', htmlspecialchars($data));
        $data .= ',';
    }
}
