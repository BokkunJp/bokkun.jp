<?php

/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */

use Private\Important;

require_once COMMON_DIR . DIRECTORY_SEPARATOR . "Include.php";

$base = new \Common\Important\Setting();
// 必要なファイルの一括読み込み
$pwd = PRIVATE_DIR_LIST['component'] . '/';
includeFiles($pwd);

use Private\Important\CustomTagCreate as OriginTag;

//JSファイル
includeDirectories(PRIVATE_DIR_LIST['component']);

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

/**
 * 対象ディレクトリ内のJSファイルを一括で読み込む
 *
 * @param string $pwd                   ディレクトリまでのパス(JSファイルが所定の場所に置いてあることを前提とする)
 * @param string $extension             拡張子
 * @param boolean $ret                  結果格納用
 * @param array $classLoad              クラス読み込み用配列
 *
 * @return void
 */
function includeJsFiles($pwd, $className = '', $ret = true, $classLoad = false): void
{
    $src = new OriginTag();
    $base = new Private\Important\Setting();
    $privateJsDir = new \Path(PRIVATE_DIR_LIST['js']);
    $privateJsDir->add($pwd);
    $jsFiles = includeFiles($privateJsDir->get(), 'js', $ret);
    if (is_array($jsFiles)) {
        $jsUrl = new \Path($base->getUrl('js'), '/');
        $jsUrl->add($pwd);
        foreach ($jsFiles as $_jsFile) {
            $jsFilePath = new \Path($jsUrl->get(), '/');
            $jsFilePath->setPathEnd();
            $jsFilePath->add($_jsFile);
            $src->readJs($jsFilePath->get());
            $src->execTag(true);
        }
    }
}
