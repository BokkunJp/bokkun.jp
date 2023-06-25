<?php

/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */

// 必要なファイルの一括読み込み
$pwd = PUBLIC_COMPONENT_DIR . '/';
IncludeFiles($pwd);

use PublicTag\CustomTagCreate as OriginTag;

//JSファイル
require_once PUBLIC_COMMON_DIR . "/Load/include.php";
// subdirectory内のphpファイルの読み込み
$directoryPath = new \Path(getcwd());
$directoryPath->Add('subdirectory');
IncludeFiles($directoryPath->Get());
IncludeDirectories(PUBLIC_COMPONENT_DIR);

// 必要なjsファイルの読み込み
IncludeJSFiles('common');
$timePath = new \Path('common');
$timePath->Add('time');
IncludeJSFiles($timePath->Get());
$jsTitle = basename(getcwd());
IncludeJSFiles($jsTitle);

// traitファイルの読み込み
$traitPath = new \Path(PUBLIC_COMMON_DIR);
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
    $base = new public\Setting();
    $jsPath = PUBLIC_JS_DIR. $pwd;

    $jsFiles = IncludeFiles($jsPath, 'js', $ret);
    if (is_array($jsFiles)) {
        foreach ($jsFiles as $_jsFile) {
            $dirPath = new \Path($base->GetUrl('', 'js'), '/');
            $dirPath->AddArray([$pwd, $_jsFile]);
            $src->ReadJS($dirPath->Get(), $className);
            $src->ExecTag(true);
        }
    }
}
