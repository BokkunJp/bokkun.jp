<?php
/*
 * 必要なPHPファイルを一括で読み込む。
 */
// 必要なファイルの読み込み
require_once(__DIR__. "/Function/UA.php");
echo "<script src='//code.jquery.com/jquery-3.4.0.min.js'></script>";
echo "<script src='//code.jquery.com/ui/1.12.0/jquery-ui.min.js' integrity='sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E='crossorigin='anonymous'></script>";
echo "<script src='https: //cdn.jsdelivr.net/npm/vue'></script>";


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
