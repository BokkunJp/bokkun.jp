<?php

require_once COMMON_DIR . DIRECTORY_SEPARATOR . "Include.php";

$base = new \Common\Important\Setting();
// 必要なファイルの一括読み込み
$pwd = PRIVATE_DIR_LIST['Component'] . '/';
includeFiles($pwd);

//JSファイル
includeDirectories(PRIVATE_DIR_LIST['Component']);

//JQuery
require_once PRIVATE_COMMON_DIR . "/Load/Include.php";

// subdirectory内のphpファイルの読み込み (指定ディレクトリのみ)
$subDirectryReadList = ['image', 'delete'];
if (searchData(basename(getcwd()), $subDirectryReadList)) {
    $subdirectoryPath = new \Path(getcwd());
    $subdirectoryPath->add('subdirectory');
    includeFiles($subdirectoryPath->get());
}
// 必要なjsファイルの読み込み(ajaxの処理の時は読み込まない)
if (!isset($ajaxFlg)) {
    includeClientFiles('common', 'private', 'js');

    $timePath = new \Path('common', '/');
    $timePath->add('time');
    includeClientFiles(rtrim($timePath->get(), '/'), 'private', 'js');
    $jsTitle = createClient('private');
    includeClientFiles(basename($jsTitle), 'private', 'js');
}

// traitファイルの読み込み
$traitPath = new \Path(PRIVATE_COMMON_DIR);
$traitPath->add('Trait');
includeFiles($traitPath->get());
