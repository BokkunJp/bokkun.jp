<?php

/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */

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
IncludeJSFiles('common');
IncludeJSFiles(AddPath('common', 'time'));
$jsTitle = basename(getcwd());
IncludeJSFiles($jsTitle);

// traitファイルの読み込み
IncludeFiles(AddPath(PUBLIC_COMMON_DIR, 'Trait'));

/**
 * IncludeDirctories
 *
 *    対象ディレクトリ内のファイルをディレクトリごと一括で読み込む
 *    (ディレクトリが複数あった場合、ディレクトリ毎それぞれのファイルを読み込む)
 *
 * @param string  $pwd:ディレクトリまでのパス
 * @param string  [$extension:拡張子]
 *
 */

function IncludeDirctories($pwd = '', $extension = 'php', $ret = false, Array $classLoad = []) {
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
 * IncludeFiles
 *     対象ディレクトリ内のファイルを一括で読み込む
 *     (対象ディレクトリ内にはファイルのみがある前提)
 *
 * @param string  $pwd:ディレクトリまでのパス
 * @param string  [$extension:拡張子]
 * @param boolean [$ret:出力フラグ]
 * @param string [$classLoad:クラスが定義されたファイル名の配列]
 *
 * @return void
 */
function IncludeFiles($pwd, $extension = 'php', $ret = false, Array $classLoad=[]) {
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd) || is_null($extension)) {
        return null;
    }

    // クラスを読み込む場合は、spl_auto_registerを使う
    if (!empty($classLoad)) {
        // print_r("<script>alert('クラスをロードしました。');</script>");
        return spl_autoload_register(function () use ($pwd, $classLoad) {
            while (($name = current($classLoad)) !== false) {
                if (!is_readable(AddPath($pwd, "{$name}.php", false))) {
                    user_error("指定されたファイルが存在しません。");
                }
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

/**
 *  IncludeJSFiles
 *      対象ディレクトリ内のJSファイルを一括で読み込み、HTMLのscriptタグとして出力する
 *      引数：
 * @param string $pwd:ディレクトリまでのパス
 *          (JSファイルが所定の場所に置いてあることを前提とする)
 * @param string  $className:クラス名
 * @param boolean [$ret:出力フラグ]
 *
 */
function IncludeJSFiles($pwd, $className='', $ret = true) {
    $src = new OriginTag();
    $base = new PublicSetting\Setting();
    $jsFiles = IncludeFiles(AddPath(PUBLIC_JS_DIR, $pwd), 'js', $ret);
    if ($jsFiles === NULL) {
        return null;
    } else if (is_array($jsFiles)) {
        foreach ($jsFiles as $_jsFile) {
            $src->ReadJS(AddPath(AddPath($base->GetUrl('', 'js'), $pwd), $_jsFile, false), $className);
            $src->ExecTag(true);
        }
    } else {
        $jsFile = $jsFiles;
        $src->ReadJS(AddPath(AddPath($base->GetUrl('', 'js'), $pwd), $jsFile, false), $className);
        $src->ExecTag(true);
    }
}
