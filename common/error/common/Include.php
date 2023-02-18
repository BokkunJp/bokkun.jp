<?php
/*
 * 必要なPHPファイルを一括で読み込む。
 */
// 必要なファイルの読み込み
require_once(__DIR__ . "/Function/UA.php");

// jQuery, Vue.js
echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>";
echo "<script src='https://code.jquery.com/ui/1.13.1/jquery-ui.min.js' integrity='sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=' crossorigin='anonymous'></script>";

if (ErrorSetting\Setting::GetServerName() === 'bokkun.jp.local') {
    echo "<script src='//unpkg.com/vue@3.2.47/dist/vue.global.js'></script>";
} else {
    echo "<script src='//jp.vuejs.org/js/vue.min.js'></script>";
}


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
