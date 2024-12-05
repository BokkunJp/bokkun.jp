<?php
    $request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';

    $data = "PHP: ";
    $posts = Public\Important\Setting::getPosts();
    $session = new Public\Important\Session('python');
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
