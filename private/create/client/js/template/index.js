// DOM読み込み
$(function() {
    Main(); // jQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function Main() {
    alert('jQuery動作確認');
}

// 全体読み込み
// $(window).on('load', function() {
//     // 画像処理用
// });

/*
 * 参考： JQueryを使わない場合のDOM読み込み

onload = function() {
    Main();
}

 */