<?php
$posts = PublicSetting\Setting::GetPosts();
$tokenCheck = CheckToken('xml-token');
if (!$tokenCheck) {
    return -1;
}
$file = PublicSetting\Setting::GetFiles();
if ($file['xml']['error']) {
    return -1;
}

$xml = simplexml_load_file($file['xml']['tmp_name']);

$xmlClass = new simplexml_parse($xml);
var_dump($xmlClass->GetAll());
var_dump($xmlClass->GetChildren('library_visual_scenes'));
