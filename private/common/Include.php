<?php
require_once("Component/Tag.php");
require_once("Component/UA.php");                 // 必要なディレクトリの読み込み
/*
 *      対象ディレクトリ内のファイルを一括で読み込む
 *      引数：
 *          $pwd:ディレクトリまでのパス
 *          $extension:拡張子
 *
 */
function IncludeFiles($pwd, $extension='php') {
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd)  || is_null($extension)) {
        return null;
    } else {
        echo $pwd;
    }

    $dirList = scandir($pwd);           // ファイルリスト構築
    $extension = '.'. $extension;       //検索用

    foreach ($dirList as $_dirList) {
        // 指定した拡張子のファイルのみ許可 (基本的にPHPのみ)
        if (strpos($_dirList, $extension)) {
            require_once $pwd. $_dirList;
        }
    }
}
