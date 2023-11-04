<?php
$posts = Public\Important\Setting::getPosts();
$session = new Public\Important\Session();
$token = new \Public\Important\Token('xml-token', $session, true);
$token->check();
if ($token->check()) {
    // $script->alert("不正な操作を検知しました。");
    return false;
}
$file = Public\Important\Setting::getFiles();
if (!isset($file['xml']) || $file['xml']['error'] || $file['xml']['type'] !== "application/octet-stream") {
    return -1;
}

$xml = simplexml_load_file($file['xml']['tmp_name']);

$xmlClass = new simplexml_parse($xml);
var_dump($xmlClass->getAll());
var_dump($xmlClass->getChildren('library_visual_scenes'));
