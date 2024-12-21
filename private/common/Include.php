<?php

/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */

use Private\Important;

require_once COMMON_DIR . DIRECTORY_SEPARATOR . "Include.php";

$base = new \Common\Important\Setting();
// 必要なファイルの一括読み込み
$pwd = PRIVATE_DIR_LIST['Component'] . '/';
includeFiles($pwd);

use Private\Important\CustomTagCreate as OriginTag;

//JSファイル
includeDirectories(PRIVATE_DIR_LIST['Component']);

//JQuery
require_once PRIVATE_COMMON_DIR . "/Load/include.php";

// subdirectory内のphpファイルの読み込み (指定ディレクトリのみ)
$subDirectryReadList = ['image', 'delete'];
if (searchData(basename(getcwd()), $subDirectryReadList)) {
    $subdirectoryPath = new \Path(getcwd());
    $subdirectoryPath->add('subdirectory');
    includeFiles($subdirectoryPath->get());
}
// 必要なjsファイルの読み込み
includeJsFiles('common');

$timePath = new \Path('common', '/');
$timePath->add('time');
includeJsFiles(rtrim($timePath->get(), '/'));
$jsTitle = createClient('private');
includeJsFiles(basename($jsTitle));

// traitファイルの読み込み
$traitPath = new \Path(PRIVATE_COMMON_DIR);
$traitPath->add('Trait');
includeFiles($traitPath->get());
