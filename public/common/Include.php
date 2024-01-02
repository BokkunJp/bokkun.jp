<?php

/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */

// 必要なファイルの一括読み込み
$commonDir = new Path(COMMON_DIR);
$commonDir->setPathEnd();
$commonDir->add('Include.php');
require_once $commonDir->get();
$pwd = PUBLIC_COMPONENT_DIR . '/';
includeFiles($pwd);

use Public\Important\CustomTagCreate as OriginTag;

//JSファイル
require_once PUBLIC_COMMON_DIR . "/Load/include.php";
// subdirectory内のphpファイルの読み込み
$directoryPath = new \Path(getcwd());
$directoryPath->add('subdirectory');
includeFiles($directoryPath->get());
includeDirectories(PUBLIC_COMPONENT_DIR);

// 必要なjsファイルの読み込み
includeJsFiles('common');
$timePath = new \Path('common');
$timePath->add('time');
includeJsFiles($timePath->get());
$jsTitle = basename(getcwd());
includeJsFiles($jsTitle);

// traitファイルの読み込み
$traitPath = new \Path(PUBLIC_COMMON_DIR);
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
    $base = new \Public\Important\Setting();
    $jsPath = PUBLIC_DIR_LIST['js']. $pwd;

    $jsFiles = includeFiles($jsPath, 'js', $ret);
    if (is_array($jsFiles)) {
        foreach ($jsFiles as $_jsFile) {
            $dirPath = new \Path($base->getUrl('', 'js'), '/');
            $dirPath->addArray([$pwd, $_jsFile]);
            $src->readJs($dirPath->get(), $className);
            $src->execTag(true);
        }
    }
}
