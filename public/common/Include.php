<?php

/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */
$base = new PublicSetting\Setting();

// 必要なファイルの一括読み込み
$pwd = PUBLIC_COMPONENT_DIR . '/';
IncludeFiles($pwd);

use \PublicTag\CustomTagCreate as OriginTag;
//JSファイル
require_once PUBLIC_COMMON_DIR . "/Load/include.php";
// subdirectory内のphpファイルの読み込み
IncludeFiles(AddPath(getcwd(), 'subdirectory'));
IncludeDirctories(PUBLIC_COMPONENT_DIR);

// 必要なjsファイルの読み込み
$src = new OriginTag();

$jsFiles = IncludeFiles(AddPath(PUBLIC_JS_DIR, 'common', true, '/'), 'js', true, '/');
foreach ($jsFiles as $_jsFile) {
    $src->ReadJS(AddPath(AddPath($base->GetUrl('', 'js'), 'common', false, '/'), $_jsFile, false, '/'), 'common', '/');
    $src->TagExec(true);
}

$jsFiles = IncludeFiles(AddPath(AddPath(PUBLIC_JS_DIR, 'common', true, '/'), 'time', true, '/'), 'js', true);
foreach ($jsFiles as $_jsFile) {
    $src->ReadJS(AddPath(AddPath(AddPath($base->GetUrl('', 'js'), 'common', false, '/'), 'time', true, '/'), $_jsFile, false, '/'), 'time',true , '/');
    $src->TagExec(true);
}

// 個別ディレクトリのjsファイルの読み込み
$jsTitle = basename(getcwd());
$jsFiles = IncludeFiles(AddPath(PUBLIC_JS_DIR, $jsTitle), 'js', true);
if (is_null($jsFiles)) {
    return;
}

foreach ($jsFiles as $_jsFile) {
    $src->ReadJS(AddPath(AddPath($base->GetUrl('', 'js'), $jsTitle, false), $_jsFile, false), $jsTitle);
    $src->TagExec(true);
}


IncludeDirctories(addPath(PUBLIC_JS_DIR, basename(getcwd())));


/*
 *      対象ディレクトリ内のファイルをディレクトリごと一括で読み込む
 *      (ディレクトリが複数あった場合、ディレクトリ毎それぞれのファイルを読み込む)
 *      引数：
 *          $pwd:ディレクトリまでのパス
 *          $extension:拡張子
 *
 */

function IncludeDirctories($pwd = '', $extension = 'php', $ret = false) {
    // パスの指定がない場合は、カレントディレクトリ一覧を取得
    if (empty($pwd)) {
        $pwd = getcwd();
        // パスの指定がある場合は、カレントディレクトリを(現在のものを保存したうえで)書き換える
    } else if ($pwd != getcwd()) {
        $localPath = getcwd();            // 現在のファイルパスを保管しておく
        chdir($pwd);                      // カレントディレクトリを指定のものに変更
    }

    $dirList = scandir($pwd);           // ファイルリスト取得
    foreach ($dirList as $_dirList) {
        if (is_dir($_dirList) && !is_numeric(strpos($_dirList, '.'))) {
            IncludeFiles(AddPath($pwd, $_dirList), $extension, false);
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

/*
 *      対象ディレクトリ内のファイルを一括で読み込む
 *      (対象ディレクトリ内にはファイルのみがある前提)
 *      引数：
 *          $pwd:ディレクトリまでのパス
 *          $extension:拡張子
 *
 */

function IncludeFiles($pwd, $extension = 'php', $ret = false) {
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd) || is_null($extension)) {
        return null;
    }

    $dirList = scandir($pwd);           // ファイルリスト取得
    $extension = '.' . $extension;       // 検索用

    $retList = [];
    foreach ($dirList as $_dirList) {
        // 指定した拡張子のファイルのみ許可
        if (strpos($_dirList, $extension) != false) {
            if ($ret === true) {
                $retList[] = $_dirList;
            } else {
                require_once $pwd . $_dirList;
            }
        }
    }

    // 出力ありの場合は、ファイルリストを出力して終了
    if ($ret === true) {
        if (empty($retList)) {
            $retList = [];
        }
        return $retList;
    }
}

/*
 *      対象ディレクトリ内のJSファイルを一括で読み込み、HTMLのscriptタグとして出力する
 *      引数：
 *          $pwd:ディレクトリまでのパス
 *          $extension:拡張子
 *
 */
function IncludeJSFiles($pwd, $ret = true) {
$src = new OriginTag();
$base = new PublicSetting\Setting();
$jsFiles = IncludeFiles(AddPath(PUBLIC_JS_DIR, 'common'), 'js', true);
foreach ($jsFiles as $_jsFile) {
    $src->ReadJS(AddPath(AddPath($base->GetUrl('', 'js'), 'common'), $_jsFile, false), '', 'common');
    $src->TagExec(true);
}

    }