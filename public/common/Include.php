<?php
/*
 * Subdirectoryディレクトリ以下のPHPファイルを一括で読み込む。
 */
 $base = new PublicSetting\Setting();
// 必要なファイルの個別読み込み
require_once("Word/Message.php");
// 必要なファイルの一括読み込み
$pwd = FUNCTION_DIR. '/';
IncludeFiles($pwd);
use CustomTagCreate as OriginTag;
$test = new OriginTag();
$href = $test->SetHref('aaa.js', 'test', 'class');

// echo $href;
// $js = $test->ReadJS('aaa.js', 'test', 'class');
// echo $js;
// $create->SetHref($http.$bread['path'], $bread['title'], 'breadCrumbList');
$pwd = getcwd(). '/subdirectory/';
IncludeFiles($pwd);

/*
 *      対象ディレクトリ内のファイルを一括で読み込む
 *      引数：
 *          $pwd:ディレクトリまでのパス
 *          $extension:拡張子
 *
 */
function IncludeFiles($pwd, $extension='php', $ret=false) {
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd)  || is_null($extension)) {
        return null;
    }

    $dirList = scandir($pwd);           // ファイルリスト取得
    $extension = '.'. $extension;       // 検索用

    $retList = [];
    foreach ($dirList as $_dirList) {
        // 指定した拡張子のファイルのみ許可
        if (strpos($_dirList, $extension) != false) {
            if ($ret === true) {
                $retList[] = $_dirList;
            } else {
                require_once $pwd. $_dirList;
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
