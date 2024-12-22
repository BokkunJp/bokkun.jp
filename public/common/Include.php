<?php

// 必要なファイルの一括読み込み
$commonDir = new Path(COMMON_DIR);
$commonDir->setPathEnd();
$commonDir->add('Include.php');
require_once $commonDir->get();
$pwd = PUBLIC_COMPONENT_DIR . '/';
includeFiles($pwd);

//JSファイル
require_once PUBLIC_COMMON_DIR . "/Load/include.php";
// subdirectory内のphpファイルの読み込み
$directoryPath = new \Path(getcwd());
$directoryPath->add('subdirectory');
includeFiles($directoryPath->get());
// Componentディレクトリ内のファイルを一括読み込み
includeDirectories(PUBLIC_COMPONENT_DIR);

// 必要なjsファイルの読み込み
includeClientFiles('common', 'public', 'js');
$timePath = new \Path('common');
$timePath->add('time');
includeClientFiles($timePath->get(), 'public', 'js');
$jsTitle = basename(getcwd());
includeClientFiles($jsTitle, 'public', 'js');

// traitファイルの読み込み
$traitPath = new \Path(PUBLIC_COMMON_DIR);
$traitPath->add('Trait');
includeFiles($traitPath->get());
