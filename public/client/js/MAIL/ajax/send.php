<?php

$get = $_GET;
$post = $_POST;

if (empty($post)) {
    return;
}
header('Content-type: text/javascript; charset=utf-8');
echo key($post);
file_get_contents('./test.txt');
// file_put_contents('./test.txt', 1);
unset($get);
unset($post);
