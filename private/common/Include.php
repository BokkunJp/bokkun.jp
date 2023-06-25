<?php

/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */

use private\Setting;

require_once COMMON_DIR . DIRECTORY_SEPARATOR . "Include.php";

$base = new Setting();
// 必要なファイルの一括読み込み
$pwd = PRIVATE_COMPONENT_DIR . '/';
IncludeFiles($pwd);

use PrivateTag\CustomTagCreate as OriginTag;

//JSファイル
IncludeDirectories(PRIVATE_COMPONENT_DIR);

//JQuery
require_once PRIVATE_COMMON_DIR . "/Load/include.php";

// subdirectory内のphpファイルの読み込み (指定ディレクトリのみ)
$subDirectryReadList = ['IMAGE', 'delete'];
if (SearchData(basename(getcwd()), $subDirectryReadList)) {
    $subdirectoryPath = new \Path(getcwd());
    $subdirectoryPath->Add('subdirectory');
    IncludeFiles($subdirectoryPath->Get());
}
// 必要なjsファイルの読み込み
IncludeJSFiles('common');

$timePath = new \Path('common', '/');
$timePath->Add('time');
IncludeJSFiles(rtrim($timePath->Get(), '/'));
$jsTitle = CreateClient('private');
IncludeJSFiles(basename($jsTitle));

// traitファイルの読み込み
$traitPath = new \Path(PRIVATE_COMMON_DIR);
$traitPath->Add('Trait');
IncludeFiles($traitPath->Get());

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
function IncludeJSFiles($pwd, $className = '', $ret = true, $classLoad = false): void
{
    $src = new OriginTag();
    $base = new private\Setting();
    $privateJsDir = new \Path(PRIVATE_JS_DIR);
    $privateJsDir->Add($pwd);
    $jsFiles = IncludeFiles($privateJsDir->Get(), 'js', $ret);
    if (is_array($jsFiles)) {
        $jsUrl = new \Path($base->GetUrl('', 'js'), '/');
        $jsUrl->Add($pwd);
        foreach ($jsFiles as $_jsFile) {
            $jsFilePath = new \Path($jsUrl->Get(), '/');
            $jsFilePath->SetPathEnd();
            $jsFilePath->Add($_jsFile);
            $src->ReadJS($jsFilePath->Get());
            $src->ExecTag(true);
        }
    }
}
