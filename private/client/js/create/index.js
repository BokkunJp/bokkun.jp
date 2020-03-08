// DOM読み込み
$(function() {
    Main(); // JQueryによるメイン処理
});

/* JQueryによる処理の流れ
 *  引数：
 *  戻り値：
 */
function Main ()
{
    $('button[name="delete"]').on('click', function (e)
    {
        if (!confirm( '本当に削除しますか？' ) )
        {
            return false;
        }
    } );
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