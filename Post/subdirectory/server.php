<?php
$request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';


if ($request === 'xmlhttprequest') {
    header("Content-Type: application/json; charset=UTF-8");
    $data = "Ajax: ";
    require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '/public/common' . DIRECTORY_SEPARATOR . 'InitFunction.php';
    require_once dirname(__DIR__,2) . "/public/common/Setting.php";
    $posts = PublicSetting\Setting::GetPosts();
    foreach ($posts as $_key => $_post) {
        $data .= $_post;
        $data .= ',';
    }
    $data = rtrim($data, ',');
    $jsonData = json_encode($data); // データをJSON形式にエンコードする

    echo $jsonData;
} else {
    $data = "PHP: ";
    $posts = PublicSetting\Setting::GetPosts();
    $session = new PublicSetting\Session();
    if (empty($posts)) {
        return -1;
    } else if (empty($posts['data'])) {
        $data = "<span class='warning'>PHP:データがありません。<span/>";
        $session->Write('output', $data);
        return NULL;
    }

    foreach ($posts as $_key => $_post) {
        $data .= $_post;
        $session->Write('output', htmlspecialchars($data));
        $data .= ',';
    }
}


