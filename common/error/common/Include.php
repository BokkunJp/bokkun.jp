<?php
/*
 * 必要なPHPファイルを一括で読み込む。
 */
// 必要なファイルの読み込み
require_once(__DIR__. "/Function/UA.php");

/**
 * こちらの機能はエラーページでは使用しない
 */
// $pwd = getcwd(). '/subdirectory/';
// // ディレクトリ存在チェック (subdirectoryが存在しない時は処理を停止する)
// if (!file_exists($pwd)) {
//     return null;
// }

// $dirList = scandir($pwd);
// foreach ($dirList as $_dirList) {
//     // phpファイルのみ許可
//     if (strpos($_dirList, '.php')) {
//         require_once $pwd. $_dirList;
//     }
// }
