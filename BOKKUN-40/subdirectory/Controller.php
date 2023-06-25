<?php
$posts = public\Setting::GetPosts();
$session = new public\Session();
$token = new \Public\Token('xml-token', $session, true);
$token->Check();
if ($token->Check()) {
    // $script->Alert("不正な操作を検知しました。");
    return false;
}
$file = public\Setting::GetFiles();
if (!isset($file['xml']) || $file['xml']['error'] || $file['xml']['type'] !== "application/octet-stream") {
    return -1;
}

$xml = simplexml_load_file($file['xml']['tmp_name']);

$xmlClass = new simplexml_parse($xml);
var_dump($xmlClass->GetAll());
var_dump($xmlClass->GetChildren('library_visual_scenes'));
