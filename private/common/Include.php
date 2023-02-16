<?php

/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */

use private\Setting;

$base = new Setting();
// 必要なファイルの一括読み込み
$pwd = PRIVATE_COMPONENT_DIR . '/';
IncludeFiles($pwd);

use PrivateTag\CustomTagCreate as OriginTag;

//JSファイル
IncludeDirctories(PRIVATE_COMPONENT_DIR);

//JQuery
require_once PRIVATE_COMMON_DIR . "/Load/include.php";

// subdirectory内のphpファイルの読み込み (指定ディレクトリのみ)
$subDirectryReadList = ['IMAGE'];
if (SearchData(basename(getcwd()), $subDirectryReadList)) {
    IncludeFiles(AddPath(getcwd(), 'subdirectory'));
}
// 必要なjsファイルの読み込み
IncludeJSFiles('common');
IncludeJSFiles(AddPath('common', 'time'));
$jsTitle = CreateClient('private');
IncludeJSFiles(basename($jsTitle));

// traitファイルの読み込み
IncludeFiles(AddPath(PRIVATE_COMMON_DIR, 'Trait'));

/**
 * IncludeDirctories
 *
 * 対象ディレクトリ内のファイルをディレクトリごと一括で読み込む
 *
 * @param string $pwd                   ディレクトリまでのパス
 * @param string $extension             拡張子
 * @param boolean $ret                  結果格納用
 * @param array $classLoad              クラス読み込み用配列
 *
 * @return null|string|array
 */
function IncludeDirctories($pwd = '', $extension = 'php', $ret = false, array $classLoad=[])
{
    // パスの指定がない場合は、カレントディレクトリ一覧を取得
    if (empty($pwd)) {
        $pwd = getcwd();
    // パスの指定がある場合は、カレントディレクトリを(現在のものを保存したうえで)書き換える
    } elseif ($pwd != getcwd()) {
        $localPath = getcwd();            // 現在のファイルパスを保管しておく
        chdir($pwd);                      // カレントディレクトリを指定のものに変更
    }

    $dirList = scandir($pwd);           // ファイルリスト取得
    foreach ($dirList as $_dirList) {
        if (is_dir($_dirList) && !is_numeric(strpos($_dirList, '.'))) {
            IncludeFiles(AddPath($pwd, $_dirList), $extension, $ret, $classLoad);
        }
    }
    if (isset($localPath)) {
        chdir($localPath);                // カレントディレクトリを元のパスに戻す
    }

    // 出力ありの場合は、ディレクトリリストを出力して終了
    if ($ret === true) {
        return $dirList;
    }
}

/**
 *    対象ディレクトリ内のファイルを一括で読み込む
 *    (対象ディレクトリ内にはファイルのみがある前提)
 *
 * @param string $pwd                   ディレクトリまでのパス
 * @param string $extension             拡張子
 * @param boolean $ret                  結果格納用
 * @param array $classLoad              クラス読み込み用配列
 *
 * @return null|array
 */
function IncludeFiles($pwd, $extension = 'php', $ret = false, array $classLoad=[])
{
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd) || is_null($extension)) {
        return null;
    }

    // クラスを読み込む場合は、spl_auto_registerを使う
    if (!empty($classLoad)) {
        return spl_autoload_register(function () use ($pwd, $classLoad) {
            while ($name = current($classLoad)) {
                require_once AddPath($pwd, "{$name}.php", false);
                next($classLoad);
            }
        });
    }

    $dirList = scandir($pwd);           // ファイルリスト取得
    $extension = '.' . $extension;       // 検索用

    $retList = [];
    foreach ($dirList as $_dirList) {
        // 指定した拡張子のファイルのみ許可
        if (strpos($_dirList, $extension) != false) {
            if ($ret === true) {
            // 出力ありの場合は、ファイルリストを配列に追加
                $retList[] = $_dirList;
            } else {
                require_once $pwd . $_dirList;
            }
        }
    }

    return $retList;
}

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
    $jsFiles = IncludeFiles(AddPath(PRIVATE_JS_DIR, $pwd), 'js', $ret);
    if (is_array($jsFiles)) {
        foreach ($jsFiles as $_jsFile) {
            $src->ReadJS(AddPath(AddPath($base->GetUrl('', 'js'), $pwd, lastSeparator:false, separator:'/'), $_jsFile, false), $className);
            $src->ExecTag(true);
        }
    }
}
