// DOM読み込み
$(function () {
    Main(); // JQueryによるメイン処理
    console.log($.fn.jquery);
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function Main() {
    //  alert('jQuery動作確認');
    var url = location.href;
    var subdirectory = 'subdirectory/notInclude/';
    var ajax = AjaxMain(url, subdirectory, 'Server.php', 'GET');
 }

/*
 * 参考： 
 
 // DOM読み込み
 // $(function() {
 //    Main();     // メイン処理
 // });
 
 // 全体読み込み (画像まで読み込んでから実行)
 // $(window).on('load', function() {
 // });
 //    Main();     // メイン処理
 
 // JQueryを使わない場合のDOM読み込み
 onload = function() {
 //    Main();     // メイン処理
 }
 */